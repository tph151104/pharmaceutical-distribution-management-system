@extends('layouts.wholesale')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Đơn hàng của tôi</h5>
            <div class="text-muted small">Theo dõi trạng thái các đơn hàng đã đặt</div>
        </div>
        <a href="{{ route('wholesale.catalog') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Đặt hàng mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th class="text-end">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end pe-3">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donHangs as $dh)
                            <tr>
                                <td class="ps-3 fw-medium">
                                    <a href="{{ route('wholesale.orders.show', $dh->ma_don_hang) }}" class="text-decoration-none">{{ $dh->ma_don_hang }}</a>
                                </td>
                                <td class="text-muted small">{{ $dh->ngay_dat->format('d/m/Y') }}</td>
                                <td class="text-end fw-semibold">{{ number_format($dh->tong_tien) }}đ</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $dh->mauTrangThai }}">{{ $dh->tenTrangThai }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('wholesale.orders.show', $dh->ma_don_hang) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Xem
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Bạn chưa có đơn hàng nào.
                                    <div class="mt-2"><a href="{{ route('wholesale.catalog') }}">Bắt đầu mua sắm</a></div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donHangs->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $donHangs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
