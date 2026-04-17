@extends('layouts.app')

@section('title', 'Kiểm hàng & Nhập kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('imports.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
                <h1 class="content-header-title mb-0">Kiểm Hàng Phiếu <strong>{{ $phieuNhap->ma_phieu_nhap }}</strong></h1>
            </div>
            <div class="text-muted small">
                Kiểm tra sản phẩm thực tế, điều chỉnh lô/HSD nếu sai lệch, chụp ảnh làm chứng và xác nhận nhập kho.
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="bi bi-file-earmark-text me-1"></i> Thông tin chứng từ</h6>
                    <div class="row g-2 small">
                        <div class="col-4 text-muted">Mã chứng từ:</div>
                        <div class="col-8 fw-semibold">{{ $phieuNhap->ma_phieu_nhap }}</div>
                        <div class="col-4 text-muted">Nhà cung cấp:</div>
                        <div class="col-8 fw-semibold">{{ $phieuNhap->nhaCungCap->ten_ncc ?? 'N/A' }}</div>
                        <div class="col-4 text-muted">Tên người lập phiếu nhập: </div>
                        <div class="col-8 fw-semibold">{{ $phieuNhap->nguoiLap->ho_ten_nd ?? 'N/A' }}</div>
                        <div class="col-4 text-muted">Ngày ghi trên vé:</div>
                        <div class="col-8">{{ $phieuNhap->ngay_nhap->format('d/m/Y') }}</div>
                        <div class="col-4 text-muted">Trạng thái:</div>
                        <div class="col-8">
                            @if($phieuNhap->trang_thai_phieu_nhap == 'cho_nhap_kho')
                                <span class="badge bg-secondary">Chờ nhập kho</span>
                            @elseif($phieuNhap->trang_thai_phieu_nhap == 'da_nhap_kho')
                                <span class="badge bg-success">Thành công</span>
                            @elseif($phieuNhap->trang_thai_phieu_nhap == 'doi_hang_ve')
                                <span class="badge bg-warning">Đợi hàng về</span>
                            @endif
                        </div>
                        @if($phieuNhap->giay_to_lien_quan)
                        <div class="col-4 text-muted mt-2">Giấy tờ LQ:</div>
                        <div class="col-8 mt-2"><a href="{{ asset($phieuNhap->giay_to_lien_quan) }}" target="_blank" class="small"><i class="bi bi-file-earmark-pdf"></i> Xem tệp</a></div>
                        @endif
                        @if($phieuNhap->tieu_lieu_lien_quan)
                        <div class="col-4 text-muted mt-2">Tài liệu LQ:</div>
                        <div class="col-8 mt-2"><a href="{{ asset($phieuNhap->tieu_lieu_lien_quan) }}" target="_blank" class="small"><i class="bi bi-file-earmark-text"></i> Xem tệp</a></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <div class="text-muted small text-uppercase mb-2">Tổng hàng trên giấy</div>
                    <div class="h2 mb-0 text-primary">{{ number_format($phieuNhap->chiTiet->sum('so_luong_nhap')) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cho phép thao tác confirm nếu chưa duyệt thành công -->
    @if(in_array($phieuNhap->trang_thai_phieu_nhap, ['cho_nhap_kho', 'doi_hang_ve']))
        <form action="{{ route('imports.confirm', $phieuNhap->ma_phieu_nhap) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary"><i class="bi bi-box-seam me-1"></i> Đối chiếu chi tiết</h6>
                    <div class="d-flex gap-2">
                        <!-- Nút xác nhận hoàn tất -->
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc chắn muốn xác nhận nhập kho? Số lượng thực tế sẽ ghi nhận vào Tồn Kho.');">
                            <i class="bi bi-check2-circle me-1"></i> Hoàn tất & Nhập kho
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light small text-muted text-center">
                                <tr>
                                    <th width="200">Sản phẩm</th>
                                    <th>Số lượng theo CT</th>
                                    <th>Số Lô (Nội bộ)</th>
                                    <th>SL Sản Xuất</th>
                                    <th>Ngày SX / SĐK</th>
                                    <th>HSD</th>
                                    <th width="120">SL đang có</th>
                                    <th width="250">Ghi nhận hình ảnh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($phieuNhap->chiTiet as $index => $item)
                                    @php
                                        // Tìm dữ liệu tồn kho hiện tại để lấy ảnh
                                        $tonKey = $item->ma_thuoc . '_' . $item->so_lo;
                                        $tonModel = $tonKhos->get($tonKey);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                            <div class="small text-muted">{{ $item->thuoc->ma_thuoc ?? $item->ma_thuoc }}</div>
                                            <!-- Lưu key chìm để controller biết đang duyệt dòng nào -->
                                            <input type="hidden" name="items[{{$index}}][ma_thuoc]" value="{{ $item->ma_thuoc }}">
                                            <input type="hidden" name="items[{{$index}}][original_so_lo]" value="{{ $item->so_lo }}">
                                        </td>
                                        <td class="text-center small">
                                            <div class="text-muted">SL: <span class="fw-bold text-dark">{{ $item->so_luong_nhap }}</span></div>
                                            <div class="text-muted">Lô: {{ $item->so_lo }}</div>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{$index}}][so_lo]" class="form-control form-control-sm text-center bg-light" value="{{ old('items.'.$index.'.so_lo', $item->so_lo) }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{$index}}][so_lo_sx]" class="form-control form-control-sm text-center bg-light" value="{{ old('items.'.$index.'.so_lo_sx', $item->so_lo_sx) }}" readonly>
                                        </td>
                                        <td>
                                            <div class="mb-1"><span class="small text-muted">NSX:</span> <strong>{{ $item->ngay_san_xuat ? $item->ngay_san_xuat->format('d/m/Y') : 'N/A' }}</strong></div>
                                            <div><span class="small text-muted">SĐK:</span> <strong>{{ $item->so_dang_ky ?? 'N/A' }}</strong></div>
                                            <input type="hidden" name="items[{{$index}}][ngay_san_xuat]" value="{{ $item->ngay_san_xuat ? $item->ngay_san_xuat->format('Y-m-d') : '' }}">
                                            <input type="hidden" name="items[{{$index}}][so_dang_ky]" value="{{ $item->so_dang_ky }}">
                                        </td>
                                        <td>
                                            <input type="date" name="items[{{$index}}][han_su_dung]" class="form-control form-control-sm text-center" value="{{ old('items.'.$index.'.han_su_dung', $item->han_su_dung ? $item->han_su_dung->format('Y-m-d') : '') }}" required>
                                        </td>
                                        <td>
                                            <!-- Số lượng thực tế màu viền khác nhau nếu bé hơn số lượng CT -->
                                            <input type="number" name="items[{{$index}}][so_luong_thuc_te]" 
                                                class="form-control form-control-sm text-center fw-bold text-primary" 
                                                value="{{ old('items.'.$index.'.so_luong_thuc_te', $item->so_luong_thuc_te ?? 0) }}" min="{{ $item->so_luong_thuc_te ?? 0 }}" max="{{ $item->so_luong_nhap }}" required>   
                                        </td>
                                        <td>
                                            <!-- Chụp ảnh vấn đề phát sinh -->
                                            <div class="d-flex gap-1 mb-1">
                                                @if($tonModel && $tonModel->image1) <img src="{{ asset($tonModel->image1) }}" width="30" height="30" class="rounded object-fit-cover shadow-sm"> @endif
                                                <input type="file" name="image1_{{ $item->ma_thuoc }}_{{ $item->so_lo }}" class="form-control form-control-sm" style="font-size: 10px;" accept="image/*">
                                            </div>
                                            <div class="d-flex gap-1 mb-1">
                                                @if($tonModel && $tonModel->image2) <img src="{{ asset($tonModel->image2) }}" width="30" height="30" class="rounded object-fit-cover shadow-sm"> @endif
                                                <input type="file" name="image2_{{ $item->ma_thuoc }}_{{ $item->so_lo }}" class="form-control form-control-sm" style="font-size: 10px;" accept="image/*">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Form upload tài liệu chung của phiếu nhập -->
                    <div class="row g-3 p-3 bg-light border-top border-bottom-0 m-0">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold mb-1">Tải lên Giấy tờ liên quan (Tùy chọn)</label>
                            <input type="file" name="giay_to_lien_quan" class="form-control" accept="image/*,.pdf">
                            @if($phieuNhap->giay_to_lien_quan)
                                <div class="mt-1 small"><a href="{{ asset($phieuNhap->giay_to_lien_quan) }}" target="_blank"><i class="bi bi-file-earmark-check"></i> Đã tải lên tài liệu</a></div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold mb-1">Tải lên Tài liệu liên quan (Tùy chọn)</label>
                            <input type="file" name="tieu_lieu_lien_quan" class="form-control" accept="image/*,.pdf">
                            @if($phieuNhap->tieu_lieu_lien_quan)
                                <div class="mt-1 small"><a href="{{ asset($phieuNhap->tieu_lieu_lien_quan) }}" target="_blank"><i class="bi bi-file-earmark-check"></i> Đã tải lên tài liệu</a></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <!-- Nếu đã nhập xong, chỉ Read-only -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center text-success bg-success bg-opacity-10 py-4">
                <i class="bi bi-check-circle display-4 mb-2"></i>
                <h5 class="mb-0">Phiếu kiểm hàng đã hoàn tất nhập kho</h5>
                <p class="text-muted small mt-1">Các lô hàng thực tế đã được phản ánh vào hệ thống tồn kho.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h6 class="mb-0 text-primary"><i class="bi bi-box-seam me-1"></i> Đối chiếu chi tiết (Đã khóa)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light small text-muted text-center">
                            <tr>
                                <th width="200">Sản phẩm</th>
                                <th>Theo CT</th>
                                <th>Lô/ Số lô SX </th>
                                <th>NSX / SĐK / HSD</th>
                                <th>SL Thực tế</th>
                                <th>Diễn giải kết quả</th>
                                <th width="200">Ghi nhận hình ảnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phieuNhap->chiTiet as $item)
                                @php
                                    // Tìm dữ liệu tồn kho hiện tại để lấy ảnh
                                    $tonKey = $item->ma_thuoc . '_' . $item->so_lo;
                                    $tonModel = $tonKhos->get($tonKey);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                    </td>
                                    <td class="text-center small">
                                        <div class="text-muted">SL: <span class="fw-bold text-dark">{{ $item->so_luong_nhap }}</span></div>
                                    </td>
                                    <td class="text-center small">
                                        <div>Lô NB: <b>{{ $item->so_lo }}</b></div>
                                        <div>Lô SX: <b>{{ $item->so_lo_sx }}</b></div>
                                    </td>
                                    <td class="text-center small">
                                        <div>NSX: <b>{{ $item->ngay_san_xuat ? $item->ngay_san_xuat->format('d/m/Y') : 'N/A' }}</b></div>
                                        <div>SĐK: <b>{{ $item->so_dang_ky ?? 'N/A' }}</b></div>
                                        <div>HSD: {{ $item->han_su_dung ? $item->han_su_dung->format('d/m/Y') : '' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $item->so_luong_thuc_te < $item->so_luong_nhap ? 'bg-warning text-dark' : 'bg-success' }} fs-6">
                                            {{ $item->so_luong_thuc_te }}
                                        </span>
                                    </td>
                                    <td class="small text-muted text-center">
                                        @if($item->so_luong_thuc_te < $item->so_luong_nhap)
                                            <span class="text-danger">Thiếu {{ $item->so_luong_nhap - $item->so_luong_thuc_te }} so với C.Từ</span>
                                        @else
                                            <span class="text-success">Khớp số lượng</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">
                                            @if($tonModel && $tonModel->image1) <img src="{{ asset($tonModel->image1) }}" width="40" height="40" class="rounded object-fit-cover shadow-sm"> @endif
                                            @if($tonModel && $tonModel->image2) <img src="{{ asset($tonModel->image2) }}" width="40" height="40" class="rounded object-fit-cover shadow-sm"> @endif
                                            @if($tonModel && $tonModel->image3) <img src="{{ asset($tonModel->image3) }}" width="40" height="40" class="rounded object-fit-cover shadow-sm"> @endif
                                            @if(!$tonModel || (!$tonModel->image1 && !$tonModel->image2 && !$tonModel->image3))
                                                <span class="text-muted small">Không có</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
