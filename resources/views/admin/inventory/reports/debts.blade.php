@extends('layouts.app')

@section('title', 'Báo Cáo Công Nợ & Doanh Thu')

@push('scripts')
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Báo Cáo Công Nợ & Doanh Thu</h1>
            </div>
            <div class="text-muted small">
                Thống kê các khoản nợ phải thu (Khách Hàng), phải trả (Nhà Cung Cấp) và Doanh thu cửa hàng.
            </div>
        </div>
       
    </div>
@endsection

@section('content')
<div class="row mb-4">
    <!-- Chart: Doanh thu 6 tháng -->
    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-bar-chart-line me-2"></i>Doanh Thu 6 Tháng Gần Nhất</h6>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart: Bảng cân đối công nợ -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-danger"><i class="bi bi-pie-chart me-2"></i>Cân Đối Công Nợ</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div style="position: relative; height: 220px; width: 100%; display: flex; justify-content: center;">
                    <canvas id="debtPieChart"></canvas>
                </div>
                
                <div class="mt-4 w-100">
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Phải Thu (KH)</span>
                        <span class="text-success fw-bold">{{ number_format($tongPhaiThu) }}đ</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php
                            $totalDebt = $tongPhaiThu + $tongPhaiTra;
                            $percentThu = $totalDebt > 0 ? ($tongPhaiThu / $totalDebt) * 100 : 0;
                            $percentTra = $totalDebt > 0 ? ($tongPhaiTra / $totalDebt) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentThu }}%"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentTra }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2 small text-muted">
                        <span>Phải Trả (NCC)</span>
                        <span class="text-danger fw-bold">{{ number_format($tongPhaiTra) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Bộ lọc -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body pb-0">
                <form action="{{ route('reports.debts') }}" method="GET" class="row g-3 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-semibold">Đối tượng / Mã chứng từ</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tên KH, Tên NCC, Mã Phiếu..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-semibold">Loại</label>
                        <select name="loai" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="xuat" {{ request('loai') == 'xuat' ? 'selected' : '' }}>Phải Thu (KH)</option>
                            <option value="nhap" {{ request('loai') == 'nhap' ? 'selected' : '' }}>Phải Trả (NCC)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-semibold">Từ ngày</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-semibold">Đến ngày</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2 justify-content-end align-items-end">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Lọc</button>
                        @if(request()->has('search') || request()->has('loai') || request()->has('from_date'))
                            <a href="{{ route('reports.debts') }}" class="btn btn-light"><i class="bi bi-x-circle"></i></a>
                        @endif
                        <a href="{{ route('reports.debts.export', request()->query()) }}" class="btn btn-success" title="Xuất Excel">
                            <i class="bi bi-file-earmark-excel"></i> Xuất Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng Dữ liệu -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Chứng Từ</th>
                                <th>Ngày GD</th>
                                <th>Đối Tượng</th>
                                <th class="text-end">Tổng Tiền</th>
                                <th class="text-end">Đã Thanh Toán</th>
                                <th class="text-end pe-4">Còn Nợ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debts as $debt)
                                <tr>
                                    <td class="ps-4">
                                        @if($debt->loai_thanh_toan == 'nhap')
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle mb-1"><i class="bi bi-box-arrow-in-down me-1"></i>Phiếu Nhập</span><br>
                                            <strong class="text-dark">{{ $debt->ma_chung_tu }}</strong>
                                        @else
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mb-1"><i class="bi bi-box-arrow-up me-1"></i>Phiếu Xuất</span><br>
                                            <strong class="text-dark">{{ $debt->ma_chung_tu }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $debt->ngay_gd ? \Carbon\Carbon::parse($debt->ngay_gd)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if($debt->loai_thanh_toan == 'nhap')
                                            <div class="fw-semibold text-primary"><i class="bi bi-building me-1 text-muted"></i>{{ $debt->doi_tuong }}</div>
                                            <small class="text-muted">{{ $debt->sdt }}</small>
                                        @elseif($debt->loai_thanh_toan == 'xuat')
                                            <div class="fw-semibold text-primary"><i class="bi bi-person me-1 text-muted"></i>{{ $debt->doi_tuong }}</div>
                                            <small class="text-muted">{{ $debt->sdt }}</small>
                                        @else
                                            <span class="text-muted fst-italic">Không xác định</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-muted">{{ number_format($debt->tong_tien) }}đ</td>
                                    <td class="text-end text-success">{{ number_format($debt->so_tien_da_tra ?? 0) }}đ</td>
                                    <td class="text-end pe-4 fw-bold {{ $debt->loai_thanh_toan == 'nhap' ? 'text-danger' : 'text-warning' }}">
                                        {{ number_format($debt->so_tien_con_no) }}đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-check2-circle fs-1 text-success d-block mb-3"></i>
                                        Tuyệt vời! Không có khoản công nợ nào cần lưu ý lúc này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $debts->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ Doanh Thu Cột
    var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    var revenueLabels = {!! json_encode(array_reverse($revenueLabels)) !!};
    var revenueData = {!! json_encode(array_reverse($revenueData)) !!};

    var revenueChart = new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu',
                data: revenueData,
                backgroundColor: 'rgba(13, 110, 253, 0.85)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: function(value) {
                            if(value >= 1000000) return (value / 1000000) + 'M';
                            if(value >= 1000) return (value / 1000) + 'k';
                            return value;
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Biểu đồ Công Nợ Tròn
    var ctxDebt = document.getElementById('debtPieChart').getContext('2d');
    var debtChart = new Chart(ctxDebt, {
        type: 'doughnut',
        data: {
            labels: ['Phải Thu (Khách nợ mình)', 'Phải Trả (Mình nợ NCC)'],
            datasets: [{
                data: [{{ $tongPhaiThu }}, {{ $tongPhaiTra }}],
                backgroundColor: [
                    'rgba(25, 135, 84, 0.9)', // Success
                    'rgba(220, 53, 69, 0.9)'  // Danger
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.raw !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
