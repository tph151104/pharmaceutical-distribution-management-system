<?php

namespace App\Http\Controllers;

use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use App\Models\TonKho;
use App\Models\NhaCungCap;
use App\Models\Thuoc;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PhieuNhapController extends Controller
{
    /**
     * Danh sách phiếu nhập
     */
    public function index(Request $request)
    {
        $query = PhieuNhap::with('nhaCungCap')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai_phieu_nhap', $request->trang_thai);
        }

        $phieuNhaps = $query->paginate(15);
        return view('admin.inventory.imports.index', compact('phieuNhaps'));
    }

    /**
     * Mở form Lập phiếu nhập
     */
    public function create()
    {
        $nhaCungCaps = NhaCungCap::all();
        $thuocs = Thuoc::all();
        return view('admin.inventory.imports.create', compact('nhaCungCaps', 'thuocs'));
    }

    /**
     * Xử lý lưu Phiếu nhập (Quản lý lập phiếu)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_ncc' => 'required|exists:nha_cung_cap,ma_ncc',
            'ngay_nhap' => 'required|date',
            'chi_tiet' => 'required|array',
            'chi_tiet.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'chi_tiet.*.so_lo' => 'required|string',
            'chi_tiet.*.so_luong_nhap' => 'required|integer|min:1',
            'chi_tiet.*.don_gia_nhap' => 'required|numeric|min:0',
            'chi_tiet.*.han_su_dung' => 'required|date|after:today',
        ], [
            'chi_tiet.*.han_su_dung.after' => 'Hạn sử dụng phải lớn hơn ngày hiện tại.',
        ]);

        // Tự động sinh mã phiếu nhập PN_YYYYMMDD_0001
        $prefix = 'PN_' . date('Ymd') . '_';
        $latest = PhieuNhap::where('ma_phieu_nhap', 'LIKE', $prefix . '%')
            ->orderBy('ma_phieu_nhap', 'desc')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->ma_phieu_nhap, -4);
            $newNum = $num + 1;
        } else {
            $newNum = 1;
        }

        $maPN = $prefix . str_pad($newNum, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            // 1. Tạo phiếu nhập
            $tongTien = 0;
            foreach ($request->chi_tiet as $item) {
                $tongTien += $item['so_luong_nhap'] * $item['don_gia_nhap'];
            }

            $phieuNhap = PhieuNhap::create([
                'ma_phieu_nhap' => $maPN,
                'ma_ncc' => $request->ma_ncc,
                'nguoi_nhap' => 'USR001', // Tạm fix cứng vì chưa có module Auth
                'ngay_nhap' => $request->ngay_nhap,
                'tong_tien' => $tongTien,
                'trang_thai_tt' => 'chua_tt',
                'trang_thai_phieu_nhap' => 'doi_hang_ve', 
                'image1' => '',
                'image2' => '',
                'giay_to_lien_quan' => '',
                'tieu_lieu_lien_quan' => '',
            ]);

            // 2. Tạo chi tiết & Tồn kho
            foreach ($request->chi_tiet as $item) {
                ChiTietPhieuNhap::create([
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'ma_thuoc' => $item['ma_thuoc'],
                    'so_lo' => $item['so_lo'],
                    'so_lo_sx' => $item['so_lo_sx'], 
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_nhap' => $item['so_luong_nhap'],
                    'so_luong_thuc_te' => 0, // Mặc định 0 khi mới lập
                    'don_gia_nhap' => $item['don_gia_nhap'],
                    'thanh_tien' => $item['so_luong_nhap'] * $item['don_gia_nhap'],
                ]);

                // Tạo dòng tồn kho ở trạng thái chờ duyệt (đang nhập về)
                TonKho::create([
                    'ma_thuoc' => $item['ma_thuoc'],
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'so_lo' => $item['so_lo'],
                    'ngay_nhap_lo' => null, // Sẽ update khi hàng về thực tế
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_ton' => 0, // Hiện tại chưa có hàng vật lý
                    'so_luong_da_xuat' => 0,
                    'trang_thai_lo' => 'cho_duyet', 
                    'image1' => '',
                    'image2' => '',
                    'image3' => '',
                ]);
            }

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Lập phiếu nhập thành công. Chờ hàng về kho để kiểm tra.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi lập phiếu: ' . $e->getMessage()]);
        }
    }

    /**
     * Xác nhận hàng đã về kho (chuyển trạng thái từ doi_hang_ve sang cho_nhap_kho)
     */
    public function markArrived($id)
    {
        $phieuNhap = PhieuNhap::findOrFail($id);

        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return back()->withErrors(['error' => 'Chỉ có thể xác nhận hàng đã về kho đối với phiếu đang đợi hàng về.']);
        }

        $phieuNhap->trang_thai_phieu_nhap = 'cho_nhap_kho';
        $phieuNhap->save();

        return redirect()->route('imports.index')->with('success', 'Đã xác nhận hàng về kho! Vui lòng thực hiện kiểm hàng.');
    }

    /**
     * Mở form Sửa phiếu nhập (Chỉ khi trạng thái là doi_hang_ve)
     */
    public function edit($id)
    {
        $phieuNhap = PhieuNhap::with('chiTiet')->findOrFail($id);

        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return redirect()->route('imports.index')->withErrors(['error' => 'Không thể sửa phiếu nhập đã hàng về hoặc đã hoàn tất.']);
        }

        $nhaCungCaps = NhaCungCap::all();
        $thuocs = Thuoc::all();
        return view('admin.inventory.imports.edit', compact('phieuNhap', 'nhaCungCaps', 'thuocs'));
    }

    /**
     * Xử lý cập nhật phiếu nhập
     */
    public function update(Request $request, $id)
    {
        $phieuNhap = PhieuNhap::findOrFail($id);
        
        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return back()->withErrors(['error' => 'Không thể sửa phiếu này.']);
        }

        $request->validate([
            'ma_ncc' => 'required|exists:nha_cung_cap,ma_ncc',
            'ngay_nhap' => 'required|date',
            'chi_tiet' => 'required|array',
            'chi_tiet.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'chi_tiet.*.so_lo' => 'required|string',
            'chi_tiet.*.so_luong_nhap' => 'required|integer|min:1',
            'chi_tiet.*.don_gia_nhap' => 'required|numeric|min:0',
            'chi_tiet.*.han_su_dung' => 'required|date|after:today',
        ], [
            'chi_tiet.*.han_su_dung.after' => 'Hạn sử dụng phải lớn hơn ngày hiện tại.',
        ]);

        DB::beginTransaction();
        try {
            // Tính lại tổng tiền
            $tongTien = 0;
            foreach ($request->chi_tiet as $item) {
                $tongTien += $item['so_luong_nhap'] * $item['don_gia_nhap'];
            }

            // Cập nhật thông tin phiếu
            $phieuNhap->update([
                'ma_ncc' => $request->ma_ncc,
                'ngay_nhap' => $request->ngay_nhap,
                'tong_tien' => $tongTien,
            ]);

            // Xóa các chi tiết cũ và tồn kho cũ
            ChiTietPhieuNhap::where('ma_phieu_nhap', $id)->delete();
            TonKho::where('ma_phieu_nhap', $id)->delete();

            // Tạo lại chi tiết & Tồn kho nháp
            foreach ($request->chi_tiet as $item) {
                ChiTietPhieuNhap::create([
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'ma_thuoc' => $item['ma_thuoc'],
                    'so_lo' => $item['so_lo'],
                    'so_lo_sx' => $item['so_lo_sx'],
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_nhap' => $item['so_luong_nhap'],
                    'so_luong_thuc_te' => 0,
                    'don_gia_nhap' => $item['don_gia_nhap'],
                    'thanh_tien' => $item['so_luong_nhap'] * $item['don_gia_nhap'],
                ]);

                TonKho::create([
                    'ma_thuoc' => $item['ma_thuoc'],
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'so_lo' => $item['so_lo'],
                    'ngay_nhap_lo' => null,
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_ton' => 0,
                    'so_luong_da_xuat' => 0,
                    'trang_thai_lo' => 'cho_duyet', 
                    'image1' => '',
                    'image2' => '',
                    'image3' => '',
                ]);
            }

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Cập nhật phiếu nhập thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi cập nhật phiếu: ' . $e->getMessage()]);
        }
    }

    /**
     * Mở form Kiểm hàng (Show details)
     */
    public function show($id)
    {
        $phieuNhap = PhieuNhap::with(['nhaCungCap', 'chiTiet.thuoc'])->findOrFail($id);
        
        // Lấy danh sách tồn kho nháp liên quan để show ảnh (nếu có)
        $tonKhos = TonKho::where('ma_phieu_nhap', $id)->get()->keyBy(function($item) {
            return $item->ma_thuoc . '_' . $item->so_lo;
        });

        return view('admin.inventory.imports.inspect', compact('phieuNhap', 'tonKhos'));
    }

    /**
     * Lưu tạm công tác kiểm hàng (Nhân viên kho nhập số thực tế và tải ảnh)
     */
    public function saveDraft(Request $request, $id)
    {
        $phieuNhap = PhieuNhap::findOrFail($id);
        
        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                // Validate if quantity is larger than expected
                $expectedQuantity = ChiTietPhieuNhap::where('ma_phieu_nhap', $id)
                                          ->where('ma_thuoc', $item['ma_thuoc'])
                                          ->where('so_lo', $item['original_so_lo'])
                                          ->value('so_luong_nhap');
                                          
                if ($item['so_luong_thuc_te'] > $expectedQuantity) {
                    throw new \Exception("Số lượng nhập thực tế của lô {$item['original_so_lo']} ({$item['so_luong_thuc_te']}) không được vượt quá số lượng chứng từ ({$expectedQuantity}).");
                }

                // Dùng original_so_lo làm key để truy vấn vì có thể người dùng đã đổi so_lo mới
                $chiTiet = ChiTietPhieuNhap::where('ma_phieu_nhap', $id)
                                           ->where('ma_thuoc', $item['ma_thuoc'])
                                           ->where('so_lo', $item['original_so_lo'])
                                           ->first();
                $tonKho = TonKho::where('ma_phieu_nhap', $id)
                                ->where('ma_thuoc', $item['ma_thuoc'])
                                ->where('so_lo', $item['original_so_lo'])
                                ->first();

                if ($chiTiet && $tonKho) {
                    $newSoLo = $item['so_lo'];
                    if ($newSoLo != $item['original_so_lo']) {
                        $chiTietData = $chiTiet->toArray();
                        $tonKhoData = $tonKho->toArray();
                        
                        $chiTiet->delete();
                        $tonKho->delete();

                        $chiTietData['so_lo'] = $newSoLo;
                        $chiTietData['so_luong_thuc_te'] = $item['so_luong_thuc_te'];
                        $chiTietData['han_su_dung'] = $item['han_su_dung'];
                        ChiTietPhieuNhap::create($chiTietData);

                        $tonKhoData['so_lo'] = $newSoLo;
                        $tonKhoData['han_su_dung'] = $item['han_su_dung'];
                        
                        TonKho::create($tonKhoData);
                        $tonKho = TonKho::where('ma_phieu_nhap', $id)
                                        ->where('ma_thuoc', $item['ma_thuoc'])
                                        ->where('so_lo', $newSoLo)->first();
                    } else {
                        // Update thông thường
                        ChiTietPhieuNhap::where('ma_phieu_nhap', $id)
                                        ->where('ma_thuoc', $item['ma_thuoc'])
                                        ->where('so_lo', $item['original_so_lo'])
                                        ->update([
                                            'so_luong_thuc_te' => $item['so_luong_thuc_te'],
                                            'han_su_dung' => $item['han_su_dung']
                                        ]);
                                        
                        $tonKho->update([
                            'han_su_dung' => $item['han_su_dung']
                        ]);
                    }

                    // Xử lý upload ảnh sự cố lưu vào tồn kho
                    $thuocId = $item['ma_thuoc'];
                    $originalSoLo = $item['original_so_lo'];
                    for ($i = 1; $i <= 3; $i++) {
                        $fileKey = 'image' . $i . '_' . $thuocId . '_' . $originalSoLo;
                        if ($request->hasFile($fileKey)) {
                            $image = $request->file($fileKey);
                            $imageName = time() . '_' . $thuocId . '_' . $i . '.' . $image->extension();
                            $image->move(public_path('uploads/batches'), $imageName);
                            // Cập nhật lại vào TonKho (do mới tạo mới hoặc update tồn tại ở vòng if trên)
                            $tonKho->{'image'.$i} = 'uploads/batches/' . $imageName;
                        }
                    }
                    $tonKho->save();
                }
            }

            // Xử lý upload tài liệu ở phiếu nhập chung
            if ($request->hasFile('giay_to_lien_quan')) {
                $file1 = $request->file('giay_to_lien_quan');
                $name1 = time() . '_giayto.' . $file1->extension();
                $file1->move(public_path('uploads/batches'), $name1);
                $phieuNhap->giay_to_lien_quan = 'uploads/batches/' . $name1;
            }

            if ($request->hasFile('tieu_lieu_lien_quan')) {
                $file2 = $request->file('tieu_lieu_lien_quan');
                $name2 = time() . '_tieulieu.' . $file2->extension();
                $file2->move(public_path('uploads/batches'), $name2);
                $phieuNhap->tieu_lieu_lien_quan = 'uploads/batches/' . $name2;
            }

            // Đổi trạng thái từ "Đợi hàng về" -> "Chờ nhập kho" (Đã nhận hàng, đang kiểm/đã lưu nháp kiểm)
            if ($phieuNhap->trang_thai_phieu_nhap == 'doi_hang_ve') {
                 $phieuNhap->trang_thai_phieu_nhap = 'cho_nhap_kho';
            }

            $phieuNhap->save();

            DB::commit();
            
            if ($request->action == 'confirm') {
                return $this->processConfirm($id);
            }

            return back()->with('success', 'Đã lưu tạm thông tin kiểm hàng.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi lưu: ' . $e->getMessage()]);
        }
    }

    /**
     * Xác nhận hoàn tất nhập kho
     */
    private function processConfirm($id)
    {
        $phieuNhap = PhieuNhap::with('chiTiet')->findOrFail($id);
        
        $isMissing = false;
        
        DB::beginTransaction();
        try {
            foreach ($phieuNhap->chiTiet as $chiTiet) {
                // Kiểm tra xem tổng số lượng thực tế (tích luỹ) < chứng từ không
                if ($chiTiet->so_luong_thuc_te < $chiTiet->so_luong_nhap) {
                    $isMissing = true;
                }

                $tonKho = TonKho::where('ma_phieu_nhap', $id)
                                ->where('ma_thuoc', $chiTiet->ma_thuoc)
                                ->where('so_lo', $chiTiet->so_lo)
                                ->first();

                if ($tonKho) {
                    // Tổng số lượng đã từng được cộng vào kho cho lô này (kể cả phần đã bán)
                    $tongDaVaoKho = $tonKho->so_luong_ton + $tonKho->so_luong_da_xuat;
                    
                    // Lượng hàng mới về đợt này
                    $hangMoiVe = $chiTiet->so_luong_thuc_te - $tongDaVaoKho;

                    if ($hangMoiVe > 0) {
                        $tonTruoc = $tonKho->so_luong_ton;
                        $tonKho->so_luong_ton += $hangMoiVe;
                        $tonKho->trang_thai_lo = 'dang_ban';
                        $tonKho->ngay_nhap_lo = Carbon::now();
                        $tonKho->save();

                        // Ghi log nhập kho
                        \App\Services\InventoryLogService::logMovement(
                            $chiTiet->ma_thuoc,
                            $chiTiet->so_lo,
                            'USR001', // Tạm fix cứng vì chưa có Auth admin form
                            $id, // Mã phiếu nhập
                            'nhap',
                            'phieu_nhap',
                            $hangMoiVe,
                            $tonTruoc,
                            $tonKho->so_luong_ton,
                            $chiTiet->don_gia_nhap,
                            'Xác nhận hàng về kho từ phiếu nhập'
                        );
                    }
                }
            }

            // Chuyển trạng thái phiếu nhập
            if ($isMissing) {
                // Lùi trạng thái về đợi hàng về để chờ đợt giao tiếp theo
                $phieuNhap->trang_thai_phieu_nhap = 'doi_hang_ve';
            } else {
                $phieuNhap->trang_thai_phieu_nhap = 'da_nhap_kho';
            }
            $phieuNhap->save();

            DB::commit();

            $msg = $isMissing 
                ? 'Đã xác nhận lô hàng. Tuy nhiên, số lượng thực tế không đủ so với chứng từ, phiếu nhập được chuyển về "Đợi hàng về" để chờ đợt giao tiếp theo.'
                : 'Đã hoàn tất quy trình nhập kho toàn bộ chứng từ!';

            return redirect()->route('imports.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi xác nhận kho: ' . $e->getMessage()]);
        }
    }

    /**
     * Xoá phiếu nhập (Chỉ khi đang ở trạng thái 'doi_hang_ve')
     */
    public function destroy($id)
    {
        $phieuNhap = PhieuNhap::findOrFail($id);

        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return back()->withErrors(['error' => 'Chỉ có thể xoá phiếu nhập ở trạng thái đợi hàng về.']);
        }

        DB::beginTransaction();
        try {
            // Xoá tồn kho nháp
            TonKho::where('ma_phieu_nhap', $id)->delete();
            // Xoá chi tiết
            ChiTietPhieuNhap::where('ma_phieu_nhap', $id)->delete();
            // Xoá phiếu
            $phieuNhap->delete();

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Đã xoá phiếu nhập ' . $id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi xoá phiếu: ' . $e->getMessage()]);
        }
    }
}
