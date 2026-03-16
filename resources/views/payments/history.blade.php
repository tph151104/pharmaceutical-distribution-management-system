@extends('layouts.app')

@section('title', 'Lịch sử Thanh toán')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0">
                <a href="{{ route('payments.index') }}" class="text-decoration-none text-muted me-2"><i class="bi bi-arrow-left"></i></a>
                Lịch sử Thanh toán
            </h1>
            <p class="text-muted small mb-0 mt-1">Danh sách các giao dịch thanh toán đã thực hiện</p>
        </div>
        <div>
            <!-- Filters if needed -->
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 border-bottom-0">Ngày GD</th>
                            <th class="border-bottom-0">Mã Giao Dịch</th>
                            <th class="border-bottom-0">Loại Phiếu / Mã Phiếu</th>
                            <th class="border-bottom-0">Phương thức TT</th>
                            <th class="border-bottom-0 text-end">Số Tiền (VNĐ)</th>
                            <th class="pe-3 border-bottom-0 text-center">Trạng Thái Dư Nợ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($tx->ngay_thanh_toan)->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ $tx->created_at->format('H:i:s') }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-primary">{{ $tx->ma_thanh_toan }}</span>
                                </td>
                                <td>
                                    @if($tx->loai_thanh_toan == 'nhap')
                                        <span class="badge bg-danger-subtle text-danger-emphasis me-1">Chi Trả Nợ</span>
                                        <a href="{{ route('imports.inspect', $tx->ma_phieu_nhap) }}" class="text-decoration-none fw-medium">{{ $tx->ma_phieu_nhap }}</a>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis me-1">Thu Nợ KH</span>
                                        <a href="#" class="text-decoration-none fw-medium text-success">{{ $tx->ma_phieu_xuat }}</a>
                                    @endif
                                </td>
                                <td>{{ $tx->phuong_thuc_tt }}</td>
                                <td class="text-end fw-bold {{ $tx->loai_thanh_toan == 'nhap' ? 'text-danger' : 'text-success' }}">
                                    {{ $tx->loai_thanh_toan == 'nhap' ? '-' : '+' }}{{ number_format($tx->so_tien_tt) }}
                                </td>
                                <td class="pe-3 text-center">
                                    @if($tx->trang_thai_tt == 'da_du')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Hết nợ</span>
                                    @else
                                        <div class="small fw-medium text-warning">Còn nợ: {{ number_format($tx->so_tien_con_no) }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Không có lịch sử giao dịch thanh toán nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
