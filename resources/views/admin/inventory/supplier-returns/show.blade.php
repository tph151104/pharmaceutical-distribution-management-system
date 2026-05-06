@extends('layouts.app')

@section('title', 'Chi tiết Phiếu Trả Hàng Nhà Cung Cấp')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('supplier-returns.index') }}" class="btn btn-light btn-sm border shadow-sm">
                <i class="bi bi-arrow-left"></i> Trở về
            </a>
            <h1 class="content-header-title mb-0">Chi tiết Phiếu Trả NCC: <span class="text-primary">{{ $phieuTra->ma_phieu_tra_ncc }}</span></h1>
            
            @if($phieuTra->trang_thai === 'cho_duyet')
                <span class="badge bg-warning text-dark fs-6">Chờ duyệt</span>
            @elseif($phieuTra->trang_thai === 'da_duyet')
                <span class="badge bg-info text-white fs-6">Đã duyệt</span>
            @elseif($phieuTra->trang_thai === 'da_hoan_thanh')
                <span class="badge bg-success fs-6">Hoàn thành</span>
            @elseif($phieuTra->trang_thai === 'da_huy')
                <span class="badge bg-danger fs-6">Đã hủy</span>
            @endif
        </div>
        
        <div class="d-flex gap-2">
            @if($phieuTra->trang_thai === 'cho_duyet' && Auth::user()->hasRole(1, 5))
                <form action="{{ route('supplier-returns.approve', $phieuTra->ma_phieu_tra_ncc) }}" method="POST" onsubmit="return confirm('Xác nhận duyệt phiếu này? Hàng sẽ được trừ khỏi kho và hệ thống sẽ tạo khoản hoàn tiền NCC.');">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Duyệt Phiếu
                    </button>
                </form>
                
                <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-circle me-1"></i> Hủy Phiếu
                </button>
            @endif

            @if($phieuTra->trang_thai === 'da_duyet' && Auth::user()->hasRole(1, 5))
                <form action="{{ route('supplier-returns.complete', $phieuTra->ma_phieu_tra_ncc) }}" method="POST" onsubmit="return confirm('Xác nhận đã trả hàng xong cho NCC?');">
                    @csrf
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-check-all me-1"></i> Đánh dấu hoàn thành
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-2"></i>Thông tin chung</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ngày tạo:</span>
                        <span class="fw-semibold">{{ $phieuTra->ngay_tao ? $phieuTra->ngay_tao->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Người tạo:</span>
                        <span class="fw-semibold">{{ $phieuTra->nguoiTao->ho_ten_nd ?? $phieuTra->nguoi_tao }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tổng tiền:</span>
                        <span class="fw-bold text-danger">{{ number_format($phieuTra->tong_tien) }} đ</span>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <div class="text-muted mb-1">Lý do trả:</div>
                        <div class="fw-semibold text-dark p-2 bg-light rounded">{{ $phieuTra->ly_do_tra }}</div>
                    </div>
                    @if($phieuTra->ghi_chu)
                    <div>
                        <div class="text-muted mb-1">Ghi chú:</div>
                        <div class="small p-2 bg-light rounded text-muted">{!! nl2br(e($phieuTra->ghi_chu)) !!}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary"><i class="bi bi-building me-2"></i>Thông tin Nhà Cung Cấp</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 text-muted mb-1">Tên NCC:</div>
                        <div class="col-sm-9 fw-bold fs-5 mb-3">{{ $phieuTra->nhaCungCap->ten_ncc ?? $phieuTra->ma_ncc }}</div>
                        
                        <div class="col-sm-3 text-muted mb-1">Mã NCC:</div>
                        <div class="col-sm-9 fw-semibold mb-2">{{ $phieuTra->ma_ncc }}</div>
                        
                        <div class="col-sm-3 text-muted mb-1">Điện thoại:</div>
                        <div class="col-sm-9 mb-2">{{ $phieuTra->nhaCungCap->dien_thoai ?? 'N/A' }}</div>
                        
                        <div class="col-sm-3 text-muted mb-1">Địa chỉ:</div>
                        <div class="col-sm-9">{{ $phieuTra->nhaCungCap->dia_chi ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-primary"><i class="bi bi-box-seam me-2"></i>Chi tiết lô hàng trả</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th>#</th>
                            <th>Mã Thuốc</th>
                            <th>Tên Thuốc</th>
                            <th>Lô Hàng</th>
                            <th>Phiếu Nhập Gốc</th>
                            <th class="text-end">Đơn Giá Nhập</th>
                            <th class="text-end">SL Trả</th>
                            <th class="text-end">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phieuTra->chiTiet as $index => $ct)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-muted">{{ $ct->ma_thuoc }}</td>
                                <td class="fw-bold">{{ $ct->thuoc->ten_thuoc ?? 'N/A' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $ct->so_lo }}</span></td>
                                <td><a href="{{ route('imports.inspect', $ct->ma_phieu_nhap) }}" class="text-decoration-none">{{ $ct->ma_phieu_nhap }}</a></td>
                                <td class="text-end">{{ number_format($ct->don_gia) }}</td>
                                <td class="text-end fw-bold text-danger">{{ number_format($ct->so_luong_tra) }}</td>
                                <td class="text-end fw-bold">{{ number_format($ct->thanh_tien) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="7" class="text-end fw-bold">Tổng Cộng:</td>
                            <td class="text-end fw-bold text-danger fs-5">{{ number_format($phieuTra->tong_tien) }} đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Hủy Phiếu -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('supplier-returns.cancel', $phieuTra->ma_phieu_tra_ncc) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Hủy Phiếu Trả NCC</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do hủy phiếu <span class="text-danger">*</span></label>
                            <textarea name="ly_do_huy" class="form-control" rows="3" required placeholder="Nhập lý do hủy phiếu..."></textarea>
                        </div>
                        <div class="alert alert-warning mb-0 small">
                            <i class="bi bi-info-circle me-1"></i> Sau khi hủy, phiếu này sẽ không thể phục hồi và sẽ không trừ tồn kho.
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
