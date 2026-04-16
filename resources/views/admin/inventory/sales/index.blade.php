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
            <a href="{{ route('sales.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Xuất Excel
            </a>
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Lập phiếu xuất mới
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-3">
            <form action="{{ route('sales.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Mã phiếu / Khách hàng</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nhập từ khóa..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Trạng thái phiếu</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="dang_chuan_bi" {{ request('status') == 'dang_chuan_bi' ? 'selected' : '' }}>Đang chuẩn bị</option>
                        <option value="da_xuat_kho" {{ request('status') == 'da_xuat_kho' ? 'selected' : '' }}>Đã xuất kho</option>
                        <option value="da_van_chuyen" {{ request('status') == 'da_van_chuyen' ? 'selected' : '' }}>Đã vận chuyển</option>
                        <option value="da_huy" {{ request('status') == 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i> Lọc</button>
                    @if(request()->hasAny(['search', 'from_date', 'to_date', 'status']))
                        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
                    @endif
                </div>
            </form>
        </div>
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

