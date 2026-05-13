@extends('layouts.app')

@section('title', 'Chi tiết Phiếu xuất kho - FEFO')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('sales.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Trở về danh sách
            </a>
            <h1 class="h3 mb-0 text-gray-800 d-flex align-items-center gap-2">
                Chi tiết Phiếu xuất: {{ $phieuXuat->ma_phieu_xuat }}
                <span class="badge bg-{{ $phieuXuat->mauTrangThai }} fs-6">{{ $phieuXuat->tenTrangThai }}</span>
            </h1>
        </div>
        <div class="d-flex gap-2">
            @if($phieuXuat->trang_thai_phieu_xuat === 'dang_chuan_bi')
                <form action="{{ route('sales.destroy', $phieuXuat->ma_phieu_xuat) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa phiếu nháp này? Đơn hàng sẽ trở lại trạng thái Đã duyệt.')">
                        <i class="bi bi-trash"></i> Xóa phiếu nháp
                    </button>
                </form>
            @endif

            @if($phieuXuat->trang_thai_phieu_xuat === 'da_xuat_kho')
                <form action="{{ route('sales.revert', $phieuXuat->ma_phieu_xuat) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Bạn có chắc muốn đưa phiếu về trạng thái Đang chuẩn bị?\n\n⚠ Hành động này sẽ:\n• Hoàn trả toàn bộ tồn kho đã trừ\n• Xóa bản ghi thanh toán / công nợ liên quan\n• Xóa chi tiết phiếu xuất\n\nMọi thay đổi sẽ được ghi vào lịch sử kho.')">
                        <i class="bi bi-arrow-counterclockwise"></i> Đưa về Đang chuẩn bị
                    </button>
                </form>
                <form action="{{ route('sales.shipping', $phieuXuat->ma_phieu_xuat) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Xác nhận chuyển hàng này đi giao / gửi đơn vị vận chuyển?')">
                        <i class="bi bi-truck"></i> Bắt đầu vận chuyển
                    </button>
                </form>
            @endif

            @if(in_array($phieuXuat->trang_thai_phieu_xuat, ['da_xuat_kho', 'da_van_chuyen', 'da_hoan_thanh']))
                <a href="{{ route('sales.print', $phieuXuat->ma_phieu_xuat) }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="bi bi-printer"></i> In phiếu xuất
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="mb-0 fw-bold text-primary">Thông tin phiếu & Đơn hàng</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted w-25">Mã đơn hàng:</td>
                            <td class="fw-medium">
                                @if($phieuXuat->ma_don_hang)
                                    <a href="{{ route('admin.orders.show', $phieuXuat->ma_don_hang) }}">{{ $phieuXuat->ma_don_hang }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted w-25">Người tạo:</td>
                            <td class="fw-medium">{{ $phieuXuat->nguoiDung->ho_ten_nd ?? $phieuXuat->nguoi_tao_phieu }}</td>
                        </tr>
                        @if($phieuXuat->donHang && $phieuXuat->donHang->nguoi_duyet)
                        <tr>
                            <td class="text-muted w-25">Người duyệt đơn:</td>
                            <td class="fw-medium text-success">{{ $phieuXuat->donHang->nguoiDuyet->ho_ten_nd ?? $phieuXuat->donHang->nguoi_duyet }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Ngày xuất:</td>
                            <td class="fw-medium">{{ \Carbon\Carbon::parse($phieuXuat->ngay_xuat)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tổng tiền:</td>
                            <td class="fw-bold text-danger">{{ number_format($phieuXuat->tong_tien) }}đ</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="mb-0 fw-bold text-primary">Thông tin Khách hàng</h6>
                </div>
                <div class="card-body">
                    @if($phieuXuat->khachHang)
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted w-25">Mã KH:</td>
                                <td class="fw-medium">{{ $phieuXuat->khachHang->ma_kh }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tên Cơ sở:</td>
                                <td class="fw-medium">{{ $phieuXuat->khachHang->ten_kh }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Địa chỉ:</td>
                                <td class="fw-medium">{{ $phieuXuat->khachHang->dia_chi }}</td>
                            </tr>
                        </table>
                    @else
                        <div class="text-muted">Không có thông tin khách hàng.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($phieuXuat->trang_thai_phieu_xuat === 'dang_chuan_bi')
        <!-- FORMS CHO THỦ KHO -->
        <form action="{{ route('sales.confirm', $phieuXuat->ma_phieu_xuat) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">Phân bổ xuất kho (FEFO từ KV03)</h5>
                    <span class="badge bg-info-subtle text-info border border-info-subtle">Hệ thống gợi ý xuất lô cận date, chỉ áp dụng hàng tại Khu vực Sẵn sáng bán (KV03)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 30%;">Sản phẩm</th>
                                    <th class="text-center" style="width: 10%;">Yêu cầu</th>
                                    <th style="width: 60%;">Phân bổ Lô (FEFO)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fefoAllocation as $maThuoc => $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $item['thuoc']->ten_thuoc }}</div>
                                            <div class="small text-muted">{{ $maThuoc }} - ĐVT: {{ $item['thuoc']->don_vi_tinh }}</div>
                                            @if($item['thieu_hang'])
                                                <div class="text-danger small mt-1">
                                                    <i class="bi bi-exclamation-triangle"></i> Thiếu {{ $item['so_luong_thieu'] }} {{ $item['thuoc']->don_vi_tinh }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold fs-5">
                                            {{ $item['so_luong_can_xuat'] }}
                                        </td>
                                        <td>
                                            @if(empty($item['allocated']))
                                                <div class="text-muted small fst-italic py-2">Không có lô hàng hợp lệ</div>
                                            @else
                                                <table class="table table-sm table-bordered border-light mb-0 bg-light">
                                                    <thead>
                                                        <tr class="text-muted" style="font-size: 0.8rem;">
                                                            <th>Số lô</th>
                                                            <th>Hạn SD</th>
                                                            <th class="text-end" title="Tồn kho tại khu vực KV03">Tồn lô (KV03)</th>
                                                            <th style="width: 120px;" title="Số lượng sẽ xuất từ lô này">SL xuất thực tế</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($item['allocated'] as $al)
                                                            <tr>
                                                                <td class="align-middle fw-medium">{{ $al['so_lo'] }}</td>
                                                                <td class="align-middle text-primary">
                                                                    {{ \Carbon\Carbon::parse($al['han_su_dung'])->format('d/m/Y') }}
                                                                    @if(\Carbon\Carbon::parse($al['han_su_dung'])->isPast())
                                                                        <span class="badge bg-danger ms-1">Đã hết hạn</span>
                                                                    @endif
                                                                </td>
                                                                <td class="align-middle text-end">{{ number_format($al['so_luong_ton']) }}</td>
                                                                <td>
                                                                    <div class="input-group input-group-sm">
                                                                        <!-- Ẩn các trường không cần chỉnh sửa tay, truyền data lên server -->
                                                                        <input type="hidden" name="allocations[{{$maThuoc}}][{{$al['so_lo']}}][ma_phieu_nhap]" value="{{ $al['ma_phieu_nhap'] }}">
                                                                        <input type="hidden" name="allocations[{{$maThuoc}}][{{$al['so_lo']}}][don_gia]" value="{{ $item['don_gia'] }}">
                                                                        
                                                                        <input type="number" class="form-control form-control-sm text-end border-primary fw-bold text-primary" 
                                                                               name="allocations[{{$maThuoc}}][{{$al['so_lo']}}][so_luong_xuat]" 
                                                                               value="{{ $al['so_luong_xuat'] }}" 
                                                                               min="0" max="{{ $al['so_luong_ton'] }}" required>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="mb-0 fw-bold text-primary">Hồ sơ giao hàng đính kèm</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ảnh minh chứng 1 (Bắt buộc/Tùy chọn)</label>
                            <input class="form-control" type="file" name="image1" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ảnh minh chứng 2</label>
                            <input class="form-control" type="file" name="image2" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Giấy tờ chứng minh an toàn (PDF/Word/Ảnh)</label>
                            <input class="form-control" type="file" name="giay_to_an_toan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tài liệu tham chiếu khác</label>
                            <input class="form-control" type="file" name="tai_lieu_lien_quan">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 pb-5">
                <a href="{{ route('sales.index') }}" class="btn btn-light px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary px-5" onclick="return confirm('Khi xác nhận, số lượng hàng này sẽ bị trừ khỏi kho thực tế và công nợ sẽ được ghi nhận. Bạn có chắc chắn?')">
                    <i class="bi bi-box-seam me-2"></i> Xác nhận xuất kho
                </button>
            </div>
        </form>

    @else
        <!-- CHẾ ĐỘ CHỈ XEM CHO PHIẾU ĐÃ XUẤT -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h5 class="mb-0 fw-bold text-primary">Chi tiết hàng xuất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Sản phẩm</th>
                                <th>Số lô</th>
                                <th>Hạn SD</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end pe-4">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phieuXuat->chiTiet as $ct)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $ct->thuoc->ten_thuoc ?? $ct->ma_thuoc }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $ct->so_lo }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($ct->han_su_dung)->format('d/m/Y') }}</td>
                                    <td class="text-end">{{ number_format($ct->don_gia_ban) }}đ</td>
                                    <td class="text-center fw-bold">{{ $ct->so_luong_xuat }}</td>
                                    <td class="text-end pe-4 fw-medium">{{ number_format($ct->thanh_tien) }}đ</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold ps-4">Tổng cộng:</td>
                                <td class="text-end pe-4 fw-bold text-danger fs-5">{{ number_format($phieuXuat->tong_tien) }}đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- HỒ SƠ GIAO HÀNG (LUÔN HIỂN THỊ ĐỂ BIẾT CÓ HAY KHÔNG) -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h5 class="mb-0 fw-bold text-primary">Hồ sơ giao hàng đính kèm</h5>
            </div>
            <div class="card-body">
                @if($phieuXuat->image1 || $phieuXuat->image2 || $phieuXuat->giay_to_an_toan || $phieuXuat->tai_lieu_lien_quan)
                    <div class="row g-4">
                        @if($phieuXuat->image1)
                            <div class="col-md-3">
                                <a href="{{ asset($phieuXuat->image1) }}" target="_blank">
                                    <img src="{{ asset($phieuXuat->image1) }}" class="img-thumbnail" alt="Image 1">
                                </a>
                            </div>
                        @endif
                        @if($phieuXuat->image2)
                            <div class="col-md-3">
                                <a href="{{ asset($phieuXuat->image2) }}" target="_blank">
                                    <img src="{{ asset($phieuXuat->image2) }}" class="img-thumbnail" alt="Image 2">
                                </a>
                            </div>
                        @endif
                        @if($phieuXuat->giay_to_an_toan)
                            <div class="col-md-3">
                                <div class="card bg-light border-0 text-center py-4 h-100">
                                    <a href="{{ asset($phieuXuat->giay_to_an_toan) }}" target="_blank" class="text-decoration-none text-dark">
                                        <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                        <div class="mt-2 fw-medium">Giấy tờ an toàn</div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if($phieuXuat->tai_lieu_lien_quan)
                            <div class="col-md-3">
                                <div class="card bg-light border-0 text-center py-4 h-100">
                                    <a href="{{ asset($phieuXuat->tai_lieu_lien_quan) }}" target="_blank" class="text-decoration-none text-dark">
                                        <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                                        <div class="mt-2 fw-medium">Tài liệu khác</div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-folder-x fs-1 opacity-50 mb-2 d-block"></i>
                        Không có hình ảnh hoặc tài liệu nào được đính kèm lúc xuất kho.
                    </div>
                @endif
            </div>
        </div>

        <!-- THAO TÁC CẬP NHẬT TRẠNG THÁI (DÀNH CHO ADMIN) -->
        <div class="card shadow-sm border-0 mb-4 bg-light rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="mb-1 fw-bold text-dark">Tiến độ Giao Hàng & Trạng Thái</h6>
                    <small class="text-muted">Cập nhật nhanh trạng thái giao hàng hoặc hoàn tác nếu có sai sót.</small>
                </div>
                <div class="d-flex gap-2">
                    @if($phieuXuat->trang_thai_phieu_xuat == 'da_xuat_kho' || $phieuXuat->trang_thai_phieu_xuat == 'da_van_chuyen')
                        <form action="{{ route('sales.complete', $phieuXuat->ma_phieu_xuat) }}" method="POST" onsubmit="return confirm('Khi xác nhận Hoàn thành, 3 ngày sau đơn hàng sẽ chốt quyền đổi trả. Tiếp tục?');">
                            @csrf
                            <button type="submit" class="btn btn-success px-4 fw-medium"><i class="bi bi-check-circle me-2"></i>Xác nhận Đã Giao Hàng Xong</button>
                        </form>
                        
                        <!-- Hoàn tác nếu lỡ bấm xuất -->
                        <form action="{{ route('sales.revert', $phieuXuat->ma_phieu_xuat) }}" method="POST" onsubmit="return confirm('Chức năng này sẽ đưa hàng về lại Kho (Chờ xuất). Bạn có chắc?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-arrow-counterclockwise"></i></button>
                        </form>
                    @elseif($phieuXuat->trang_thai_phieu_xuat == 'da_hoan_thanh')
                        <div class="alert alert-success mb-0 px-4 py-2 border border-success border-opacity-50">
                            <i class="bi bi-check-circle-fill me-2"></i>Đơn hàng đã hoàn thành!
                        </div>
                        @if($phieuXuat->donHang && $phieuXuat->donHang->trang_thai_dh == 'dang_van_chuyen')
                        <form action="{{ route('sales.undoComplete', $phieuXuat->ma_phieu_xuat) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn Hoàn tác việc Đã giao hàng? Trạng thái sẽ trở về Đã xuất kho.');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger px-4 fw-medium"><i class="bi bi-arrow-return-left me-2"></i>Hoàn tác Đã Giao</button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@if($phieuXuat->trang_thai_phieu_xuat === 'dang_chuan_bi')
<script>
    // === AJAX Polling: Kiểm tra trạng thái phiếu xuất mỗi 5 giây ===
    // Nếu người khác đã xác nhận phiếu trước, sẽ hiện cảnh báo ngay lập tức
    (function() {
        const maPhieuXuat = @json($phieuXuat->ma_phieu_xuat);
        const checkUrl = "{{ route('sales.checkStatus', $phieuXuat->ma_phieu_xuat) }}";
        let polling = null;

        function kiemTraTrangThai() {
            fetch(checkUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                // Nếu trạng thái đã thay đổi (không còn là "đang chuẩn bị")
                if (data.trang_thai !== 'dang_chuan_bi') {
                    // Dừng polling
                    clearInterval(polling);

                    // Hiện banner cảnh báo nổi bật
                    let banner = document.createElement('div');
                    banner.className = 'alert alert-danger border-danger shadow-lg d-flex align-items-center gap-3';
                    banner.style.cssText = 'position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:9999; min-width:500px; animation: fadeInDown 0.5s;';
                    banner.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        <div>
                            <strong>⚠ Phiếu này đã được người khác xác nhận!</strong><br>
                            Trạng thái hiện tại: <span class="badge bg-danger">${data.ten_trang_thai}</span><br>
                            <small class="text-muted">Trang sẽ tự động tải lại sau 5 giây...</small>
                        </div>
                    `;
                    document.body.prepend(banner);

                    // Vô hiệu hóa nút xác nhận để không cho bấm nữa
                    let btnConfirm = document.querySelector('button[type="submit"]');
                    if (btnConfirm) {
                        btnConfirm.disabled = true;
                        btnConfirm.classList.add('btn-secondary');
                        btnConfirm.classList.remove('btn-primary');
                        btnConfirm.innerHTML = '<i class="bi bi-lock me-2"></i> Đã bị khóa';
                    }

                    // Tự động tải lại trang sau 5 giây
                    setTimeout(() => location.reload(), 5000);
                }
            })
            .catch(() => {
                // Lỗi mạng thì bỏ qua, lần sau sẽ thử lại
            });
        }

        // Bắt đầu polling mỗi 5 giây
        polling = setInterval(kiemTraTrangThai, 5000);
    })();
</script>

<style>
    @keyframes fadeInDown {
        from { opacity: 0; transform: translate(-50%, -30px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
</style>
@endif
@endsection

