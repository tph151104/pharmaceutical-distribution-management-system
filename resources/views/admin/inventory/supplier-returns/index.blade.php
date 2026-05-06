@extends('layouts.app')

@section('title', 'Danh sách phiếu trả hàng nhà cung cấp')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-box-arrow-right text-primary fs-4"></i>
                <h1 class="content-header-title mb-0">Trả Hàng Nhà Cung Cấp</h1>
            </div>
            <div class="text-muted small">
                Quản lý các phiếu trả hàng lỗi, quá hạn cho nhà cung cấp từ khu vực Chờ Xử Lý (KV04).
            </div>
        </div>
        <div>
            <a href="{{ route('transfers.index') }}" class="btn btn-primary d-inline-flex align-items-center shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tạo phiếu mới
            </a>
        </div>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('supplier-returns.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Mã Phiếu</label>
                    <input type="text" name="ma_phieu" class="form-control form-control-sm" placeholder="Tìm theo mã" value="{{ request('ma_phieu') }}">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Trạng thái</label>
                    <select name="trang_thai" class="form-select form-select-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="da_hoan_thanh" {{ request('trang_thai') == 'da_hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="da_huy" {{ request('trang_thai') == 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Từ ngày</label>
                    <input type="date" name="tu_ngay" class="form-control form-control-sm" value="{{ request('tu_ngay') }}">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Đến ngày</label>
                    <input type="date" name="den_ngay" class="form-control form-control-sm" value="{{ request('den_ngay') }}">
                </div>
                <div class="col-12 col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                    @if(request()->anyFilled(['ma_phieu', 'trang_thai', 'tu_ngay', 'den_ngay']))
                        <a href="{{ route('supplier-returns.index') }}" class="btn btn-light btn-sm mt-1">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th>Mã Phiếu</th>
                            <th>Nhà Cung Cấp</th>
                            <th>Ngày Tạo</th>
                            <th>Người Tạo</th>
                            <th class="text-end">Tổng Tiền</th>
                            <th class="text-center">Trạng Thái</th>
                            <th class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($phieuTras as $phieu)
                            <tr>
                                <td class="fw-semibold">{{ $phieu->ma_phieu_tra_ncc }}</td>
                                <td>{{ $phieu->nhaCungCap->ten_ncc ?? $phieu->ma_ncc }}</td>
                                <td>{{ $phieu->ngay_tao ? $phieu->ngay_tao->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $phieu->nguoiTao->ho_ten_nd ?? $phieu->nguoi_tao }}</td>
                                <td class="text-end fw-semibold text-danger">{{ number_format($phieu->tong_tien) }}</td>
                                <td class="text-center">
                                    @if($phieu->trang_thai === 'cho_duyet')
                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                    @elseif($phieu->trang_thai === 'da_duyet')
                                        <span class="badge bg-info text-white">Đã duyệt</span>
                                    @elseif($phieu->trang_thai === 'da_hoan_thanh')
                                        <span class="badge bg-success">Hoàn thành</span>
                                    @elseif($phieu->trang_thai === 'da_huy')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $phieu->trang_thai }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('supplier-returns.show', $phieu->ma_phieu_tra_ncc) }}" class="btn btn-sm btn-light border shadow-sm">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Chưa có phiếu trả nhà cung cấp nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($phieuTras->hasPages())
                <div class="card-footer bg-white border-top-0 pt-3">
                    {{ $phieuTras->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
