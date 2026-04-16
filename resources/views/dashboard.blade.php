@extends('layouts.app')

@section('title', 'Dashboard - Hệ thống phân phối thuốc tây')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Dashboard</h1>
                <span class="chip">
                    <i class="bi bi-activity"></i>
                    Trực tuyến
                </span>
            </div>
            <div class="text-muted small">
                Tổng quan nhập - xuất kho, tồn kho lô hàng, công nợ nhà cung cấp & khách hàng.
            </div>
        </div>

    </div>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Giá trị tồn kho</div>
                        <div class="h4 mb-0">₫ {{ number_format($giaTriTonKho, 0, ',', '.') }}</div>
                        <div class="small text-muted mt-1">
                            Tổng giá trị dược phẩm
                        </div>
                    </div>
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-safe2-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Lô sắp/đã hết hạn</div>
                        <div class="h4 mb-0">{{ $soLoSapHetHan }} lô</div>
                        <div class="small mt-1 {{ $soLoDaHetHan > 0 ? 'text-danger fw-bold' : 'text-warning' }}">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $soLoDaHetHan > 0 ? "Đã quá hạn: {$soLoDaHetHan} lô" : 'Cần xử lý trong 60 ngày' }}
                        </div>
                    </div>
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-alarm"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Doanh số bán sỉ (tháng)</div>
                        <div class="h4 mb-0">₫ {{ number_format($doanhSoThangNay, 0, ',', '.') }}</div>
                        <div class="small {{ $tyLeTangTruong >= 0 ? 'text-success' : 'text-danger' }} mt-1">
                            <i class="bi {{ $tyLeTangTruong >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} me-1"></i>
                            {{ $tyLeTangTruong >= 0 ? '+' : '' }}{{ number_format($tyLeTangTruong, 1, ',', '.') }}% so với tháng trước
                        </div>
                    </div>
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Việc cần xử lý</div>
                        <div class="h4 mb-0">{{ $donHangChoDuyet + $traHangChoDuyet + $phieuNhapDoiHang }} yêu cầu</div>
                        <div class="small text-muted mt-1">
                            Đơn hàng chờ: <span class="fw-semibold text-danger">{{ $donHangChoDuyet }}</span>,
                            Trả hàng chờ: <span class="fw-semibold text-danger">{{ $traHangChoDuyet }}</span>,
                            Đợi nhập kho: <span class="fw-semibold text-warning">{{ $phieuNhapDoiHang }}</span>
                        </div>
                    </div>
                    <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Biểu đồ nhập - xuất kho (6 tháng gần nhất)</div>
                        <div class="text-muted small">Tổng hợp giá trị nhập kho và xuất kho</div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="min-height: 300px; width: 100%;">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Cảnh báo lô sắp hết hạn</div>
                        <div class="text-muted small">Trong vòng 60 ngày tới</div>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis">24 lô</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush small">
                        @forelse($topLoHSD as $lo)
                            @php
                                $isExpired = \Carbon\Carbon::parse($lo->han_su_dung)->isPast();
                            @endphp
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div class="me-2">
                                    <div class="fw-semibold {{ $isExpired ? 'text-danger' : '' }}">{{ $lo->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                    <div class="text-muted">Lô: {{ $lo->so_lo }} • HSD: {{ \Carbon\Carbon::parse($lo->han_su_dung)->format('d/m/Y') }}</div>
                                </div>
                                <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-warning-subtle text-warning-emphasis' }}">Tồn: {{ $lo->so_luong_ton }}</span>
                            </div>
                        @empty
                            <div class="list-group-item px-0 text-muted">
                                <i class="bi bi-check-circle text-success me-1"></i>Tất cả các lô đều ở mức an toàn.
                            </div>
                        @endforelse
                        
                        <a href="{{ route('reports.stock') }}" class="small mt-2 d-inline-flex align-items-center text-decoration-none">
                            Xem tất cả danh sách hết hạn
                            <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('inventoryChart').getContext('2d');
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Doanh số xuất kho (VNĐ)',
                        data: chartData.sales,
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                        borderRadius: 4
                    },
                    {
                        label: 'Giá trị nhập kho (VNĐ)',
                        data: chartData.imports,
                        backgroundColor: 'rgba(13, 110, 253, 0.4)',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>
@endpush
