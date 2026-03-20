@extends('layouts.app')

@section('title', 'Danh sách phiếu xuất kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Phiếu xuất kho</h1>
            </div>
            <div class="text-muted small">
                Quản lý lộ trình xuất kho, phân bổ lô theo nguyên tắc FEFO và cập nhật trạng thái đơn hàng mua sỉ.
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Lập phiếu xuất mới
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="mb-3 d-flex gap-2 flex-wrap">
        <a href="{{ route('sales.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">Tất cả</a>
        <a href="{{ route('sales.index', ['status' => 'dang_chuan_bi']) }}" class="btn btn-sm {{ request('status') == 'dang_chuan_bi' ? 'btn-warning' : 'btn-outline-warning' }}">Đang chuẩn bị</a>
        <a href="{{ route('sales.index', ['status' => 'da_xuat_kho']) }}" class="btn btn-sm {{ request('status') == 'da_xuat_kho' ? 'btn-primary' : 'btn-outline-primary' }}">Đã xuất kho</a>
        <a href="{{ route('sales.index', ['status' => 'da_van_chuyen']) }}" class="btn btn-sm {{ request('status') == 'da_van_chuyen' ? 'btn-success' : 'btn-outline-success' }}">Đã vận chuyển</a>
        <a href="{{ route('sales.index', ['status' => 'da_huy']) }}" class="btn btn-sm {{ request('status') == 'da_huy' ? 'btn-danger' : 'btn-outline-danger' }}">Đã hủy</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Danh sách phiếu xuất kho
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">{{ $phieuXuats->total() }}</span> phiếu
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr class="small text-muted">
                        <th class="ps-3 text-nowrap">Mã phiếu</th>
                        <th class="text-nowrap">Ngày xuất</th>
                        <th class="text-nowrap">Khách hàng</th>
                        <th class="text-nowrap">Đơn hàng gốc</th>
                        <th class="text-nowrap text-end">Tổng tiền</th>
                        <th class="text-nowrap text-center">Trạng thái phiếu</th>
                        <th class="text-nowrap text-end pe-3">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($phieuXuats as $px)
                            <tr>
                                <td class="ps-3 fw-medium">
                                    <a href="{{ route('sales.show', $px->ma_phieu_xuat) }}" class="text-decoration-none">{{ $px->ma_phieu_xuat }}</a>
                                </td>
                                <td class="text-muted small">{{ $px->ngay_xuat ? \Carbon\Carbon::parse($px->ngay_xuat)->format('d/m/Y') : '' }}</td>
                                <td>
                                    <div class="fw-medium">{{ $px->khachHang->ten_kh ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if($px->ma_don_hang)
                                        <a href="{{ route('admin.orders.show', $px->ma_don_hang) }}" class="small">{{ $px->ma_don_hang }}</a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($px->tong_tien) }}đ</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $px->mauTrangThai }}">{{ $px->tenTrangThai }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('sales.show', $px->ma_phieu_xuat) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted small">
                                    Hiện chưa có phiếu xuất kho nào. Hệ thống tự động sinh phiếu xuất khi duyệt đơn hàng.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($phieuXuats->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $phieuXuats->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

