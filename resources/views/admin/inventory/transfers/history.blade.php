@extends('layouts.app')

@section('title', 'Lịch sử Luân chuyển Kho')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800 d-flex align-items-center">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Lịch sử Luân chuyển giữa các Kho
        </h2>
        <div>
            <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
            <a href="{{ route('transfers.export', request()->all()) }}" class="btn btn-success text-white">
                <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <!-- Filter Bar -->
        <div class="card-header bg-light py-3">
            <form action="{{ route('transfers.history') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1">Mã Phiếu Nhập gốc</label>
                    <input type="text" name="ma_phieu_nhap" value="{{ request('ma_phieu_nhap') }}" class="form-control" placeholder="VD: PN_2026...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1">Ngày chuyển</label>
                    <input type="date" name="ngay_chuyen" value="{{ request('ngay_chuyen') }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-funnel me-1"></i> Lọc kết quả
                    </button>
                    @if(request()->has('ma_phieu_nhap') || request()->has('ngay_chuyen'))
                        <a href="{{ route('transfers.history') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 text-secondary text-nowrap">Thời gian</th>
                        <th class="py-3 text-secondary text-nowrap">Mã Lô / Mã Phiếu Nhập</th>
                        <th class="py-3 text-secondary" style="min-width: 200px;">Thuốc</th>
                        <th class="py-3 text-secondary text-nowrap">Hành trình</th>
                        <th class="py-3 text-center text-secondary text-nowrap">Số lượng</th>
                        <th class="py-3 text-secondary">Lý do/Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $log)
                        <tr>
                            <td class="text-nowrap">
                                <span class="fw-medium text-dark">{{ $log->ngay_chuyen ? \Carbon\Carbon::parse($log->ngay_chuyen)->format('H:i') : '' }}</span>
                                <br>
                                <small class="text-muted">{{ $log->ngay_chuyen ? \Carbon\Carbon::parse($log->ngay_chuyen)->format('d/m/Y') : '' }}</small>
                            </td>
                            <td class="text-nowrap">
                                <div class="fw-bold text-dark">Lô: {{ $log->so_lo }}</div>
                                <div class="small text-muted">PN: {{ $log->ma_phieu_nhap }}</div>
                                <div class="small text-muted mt-1"><i class="bi bi-person me-1"></i>{{ $log->nguoiThucHien->ho_ten ?? $log->nguoi_thuc_hien }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-primary mb-1">
                                    {{ $log->tonKho->thuoc->ten_thuoc ?? 'N/A' }}
                                </div>
                                <div class="small text-muted">Mã: {{ $log->ma_thuoc }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light" style="min-width: 260px;">
                                    <span class="badge {{ $log->tu_khu_vuc ? 'bg-secondary' : 'bg-warning text-dark' }} text-truncate" style="max-width: 100px;">
                                        {{ $log->tuKhuVucKho->ten_khu_vuc ?? $log->tu_khu_vuc ?? 'Nhập mới' }}
                                    </span>
                                    <i class="bi bi-arrow-right text-muted mx-2"></i>
                                    <span class="badge bg-info text-dark border border-info-subtle text-truncate" style="max-width: 100px;">
                                        {{ $log->denKhuVucKho->ten_khu_vuc ?? $log->den_khu_vuc ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-success fs-5">
                                +{{ number_format($log->so_luong_chuyen) }}
                            </td>
                            <td>
                                <span class="text-muted small d-inline-block text-truncate w-100" style="max-width: 250px;" title="{{ $log->ly_do_chuyen }}">
                                    {{ $log->ly_do_chuyen ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                                <p class="mb-0 fw-medium">Chưa có dữ liệu lịch sử luân chuyển nào thoả điều kiện tìm kiếm.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($histories->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $histories->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
