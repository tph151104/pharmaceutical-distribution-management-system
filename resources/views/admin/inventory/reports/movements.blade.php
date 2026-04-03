@extends('layouts.app')

@section('title', 'Lịch Sử Xuất Nhập Kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Lịch Sử Xuất Nhập Kho</h1>
            </div>
            <div class="text-muted small">
                Tra cứu chi tiết các giao dịch làm biến động số lượng tồn kho (nhập, xuất, kiểm kho) theo thời gian.
            </div>
        </div>
        <div>
            <a href="{{ route('reports.movements.export', request()->query()) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Bộ lọc -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('reports.movements') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-semibold">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-semibold">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" value="{{ request('den_ngay') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-semibold">Loại giao dịch</label>
                        <select name="loai_gd" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="nhap" {{ request('loai_gd') == 'nhap' ? 'selected' : '' }}>Nhập kho</option>
                            <option value="xuat" {{ request('loai_gd') == 'xuat' ? 'selected' : '' }}>Xuất kho</option>
                            <option value="dieu_chinh" {{ request('loai_gd') == 'dieu_chinh' ? 'selected' : '' }}>Điều chỉnh</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-semibold">Tìm tên thuốc / Số lô</label>
                        <input type="text" name="search" class="form-control" placeholder="Nhập tên thuốc hoặc số lô..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i>Lọc</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng Dữ liệu -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-list-ul me-2"></i>Chi Tiết Giao Dịch</h5>
                <span class="badge bg-primary rounded-pill">Tổng: {{ $logs->total() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Thời gian</th>
                                <th>Mã Log</th>
                                <th>Người thao tác</th>
                                <th>Chứng từ</th>
                                <th>Thuốc - Lô</th>
                                <th>Loại</th>
                                <th class="text-end">SL Thay Đổi</th>
                                <th class="text-end">Tồn Trước</th>
                                <th class="text-end pe-4">Tồn Sau</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4">
                                        {{ $log->thoi_gian->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $log->thoi_gian->format('H:i:s') }}</small>
                                    </td>
                                    <td><small class="text-muted">{{ $log->ma_log }}</small></td>
                                    <td><strong>{{ $log->nguoiDung->ho_ten ?? $log->nguoi_thuc_hien }}</strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $log->ma_chung_tu }}</span><br>
                                        <small class="text-secondary">{{ $log->nguon_giao_dich }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-primary">{{ $log->thuoc->ten_thuoc ?? $log->ma_thuoc }}</div>
                                        <div class="small text-muted">Lô: {{ $log->so_lo }}</div>
                                        <div class="small text-muted">Đơn giá: {{ number_format($log->don_gia) }}đ</div>
                                    </td>
                                    <td>
                                        @if($log->loai_giao_dich == 'nhap')
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle"><i class="bi bi-arrow-down-left me-1"></i>Nhập</span>
                                        @elseif($log->loai_giao_dich == 'xuat')
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle"><i class="bi bi-arrow-up-right me-1"></i>Xuất</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="bi bi-shuffle me-1"></i>Điều chỉnh</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold {{ $log->loai_giao_dich == 'nhap' ? 'text-success' : ($log->loai_giao_dich == 'xuat' ? 'text-danger' : 'text-warning') }}">
                                        {{ $log->loai_giao_dich == 'nhap' ? '+' : ($log->loai_giao_dich == 'xuat' ? '-' : '') }}{{ number_format($log->so_luong) }}
                                    </td>
                                    <td class="text-end text-muted">{{ number_format($log->ton_truoc) }}</td>
                                    <td class="text-end pe-4 fw-semibold">{{ number_format($log->ton_sau) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Không có dữ liệu lịch sử nào phù hợp bộ lọc.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
