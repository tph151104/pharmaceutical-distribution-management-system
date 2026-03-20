@extends('layouts.app')

@section('title', 'Quản lý Đơn hàng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-box-seam text-primary me-2"></i>Quản lý Đơn hàng</h1>
            <p class="text-muted small mb-0 mt-1">Danh sách đơn đặt hàng từ khách hàng sỉ</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel
            </a>
        </div>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-3 d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">Tất cả</a>
        <a href="{{ route('admin.orders.index', ['status' => 'cho_xu_ly']) }}" class="btn btn-sm {{ request('status') == 'cho_xu_ly' ? 'btn-warning' : 'btn-outline-warning' }}">Chờ xử lý</a>
        <a href="{{ route('admin.orders.index', ['status' => 'da_duyet']) }}" class="btn btn-sm {{ request('status') == 'da_duyet' ? 'btn-info' : 'btn-outline-info' }}">Đã duyệt</a>
        <a href="{{ route('admin.orders.index', ['status' => 'dang_xuat_kho']) }}" class="btn btn-sm {{ request('status') == 'dang_xuat_kho' ? 'btn-primary' : 'btn-outline-primary' }}">Đang xuất kho</a>
        <a href="{{ route('admin.orders.index', ['status' => 'da_hoan_thanh']) }}" class="btn btn-sm {{ request('status') == 'da_hoan_thanh' ? 'btn-success' : 'btn-outline-success' }}">Hoàn thành</a>
        <a href="{{ route('admin.orders.index', ['status' => 'da_huy']) }}" class="btn btn-sm {{ request('status') == 'da_huy' ? 'btn-danger' : 'btn-outline-danger' }}">Đã hủy</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th class="text-end">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end pe-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donHangs as $dh)
                            <tr>
                                <td class="ps-3 fw-medium">
                                    <a href="{{ route('admin.orders.show', $dh->ma_don_hang) }}" class="text-decoration-none">{{ $dh->ma_don_hang }}</a>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $dh->khachHang->ten_kh ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $dh->khachHang->dien_thoai ?? '' }}</div>
                                </td>
                                <td class="text-muted small">{{ $dh->ngay_dat ? $dh->ngay_dat->format('d/m/Y') : '' }}</td>
                                <td class="text-end fw-semibold">{{ number_format($dh->tong_tien) }}đ</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $dh->mauTrangThai }}">{{ $dh->tenTrangThai }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.orders.show', $dh->ma_don_hang) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($dh->trang_thai_dh == 'cho_xu_ly')
                                            <form method="POST" action="{{ route('admin.orders.approve', $dh->ma_don_hang) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Duyệt đơn">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(!in_array($dh->trang_thai_dh, ['da_hoan_thanh', 'da_huy']))
                                            <form method="POST" action="{{ route('admin.orders.cancel', $dh->ma_don_hang) }}" class="d-inline" onsubmit="return confirm('Xác nhận hủy đơn hàng này?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hủy đơn">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Không có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donHangs->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $donHangs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
