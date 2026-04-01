@extends('layouts.wholesale')

@section('title', 'Lịch sử thanh toán - ' . $donHang->ma_don_hang)

@section('content')
    <div class="mb-4">
        <a href="{{ route('wholesale.orders.show', $donHang->ma_don_hang) }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i>Quay lại chi tiết đơn hàng
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Lịch sử thanh toán</h6>
                    <span class="badge bg-primary">{{ $transactions->count() }} giao dịch</span>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th class="ps-3">Mã GD</th>
                                        <th>Ngày thanh toán</th>
                                        <th>Phương thức</th>
                                        <th class="text-end">Số tiền</th>
                                        <th class="text-center">Minh chứng</th>
                                        <th class="text-end pe-3">Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $tt)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-semibold small text-primary">{{ $tt->ma_thanh_toan }}</div>
                                            </td>
                                            <td class="small">
                                                {{ \Carbon\Carbon::parse($tt->ngay_thanh_toan)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if($tt->phuong_thuc_tt == 'Chuyển khoản')
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                        <i class="bi bi-bank me-1"></i>{{ $tt->phuong_thuc_tt }}
                                                    </span>
                                                @elseif($tt->phuong_thuc_tt == 'Tiền mặt')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                        <i class="bi bi-cash me-1"></i>{{ $tt->phuong_thuc_tt }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border">{{ $tt->phuong_thuc_tt ?? 'N/A' }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                +{{ number_format($tt->so_tien_tt) }}đ
                                            </td>
                                            <td class="text-center">
                                                @if($tt->minh_chung_tt_image)
                                                    <a href="{{ Storage::url($tt->minh_chung_tt_image) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Xem minh chứng">
                                                        <i class="bi bi-image"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3 small text-muted" style="max-width: 150px;">
                                                {{ $tt->ghi_chu ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold ps-3">Tổng đã thanh toán:</td>
                                        <td class="text-end fw-bold text-success fs-5">{{ number_format($tongDaTra) }}đ</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Chưa có giao dịch thanh toán nào cho đơn hàng này.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Thông tin đơn hàng --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thông tin đơn hàng</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Mã đơn hàng:</span>
                        <span class="fw-bold">{{ $donHang->ma_don_hang }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Ngày đặt:</span>
                        <span>{{ $donHang->ngay_dat->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Trạng thái:</span>
                        <span class="badge bg-{{ $donHang->mauTrangThai }}">{{ $donHang->tenTrangThai }}</span>
                    </div>
                    @if($phieuXuat)
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Mã phiếu xuất:</span>
                        <span class="fw-medium">{{ $phieuXuat->ma_phieu_xuat }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tổng hợp công nợ --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i>Tổng hợp công nợ</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tổng tiền đơn hàng:</span>
                        <span class="fw-bold">{{ number_format($tongTien) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Đã thanh toán:</span>
                        <span class="text-success fw-bold">{{ number_format($tongDaTra) }}đ</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Còn nợ:</span>
                        <span class="fw-bold fs-5 {{ $conNo > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $conNo > 0 ? number_format($conNo) . 'đ' : 'Đã đủ ✓' }}
                        </span>
                    </div>

                    @if($tongTien > 0)
                        <div class="mt-3">
                            <div class="progress" style="height: 8px;">
                                @php $percent = min(100, ($tongDaTra / $tongTien) * 100); @endphp
                                <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                     style="width: {{ $percent }}%;" 
                                     title="{{ number_format($percent, 1) }}%"></div>
                            </div>
                            <div class="text-center small text-muted mt-1">{{ number_format($percent, 1) }}% đã thanh toán</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
