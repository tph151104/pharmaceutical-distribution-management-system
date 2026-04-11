@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - ' . $donHang->ma_don_hang)

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted me-2"><i class="bi bi-arrow-left"></i></a>
                Chi tiết đơn hàng
            </h1>
        </div>
        <span class="badge bg-{{ $donHang->mauTrangThai }} fs-6">{{ $donHang->tenTrangThai }}</span>
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

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Đơn hàng: {{ $donHang->ma_don_hang }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-3">Mã thuốc</th>
                                    <th>Tên thuốc</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-3">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donHang->chiTiet as $ct)
                                    <tr>
                                        <td class="ps-3 fw-medium text-primary">{{ $ct->ma_thuoc }}</td>
                                        <td>{{ $ct->thuoc->ten_thuoc ?? 'N/A' }}</td>
                                        <td class="text-end">{{ number_format($ct->don_gia) }}đ</td>
                                        <td class="text-center">{{ $ct->so_luong }}</td>
                                        <td class="text-end pe-3 fw-semibold">{{ number_format($ct->thanhTien) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="text-end pe-3 fw-bold text-primary fs-5">{{ number_format($donHang->tong_tien) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thông tin khách hàng</h6></div>
                <div class="card-body small">
                    <div class="mb-2"><strong>Tên:</strong> {{ $donHang->khachHang->ten_kh ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Loại:</strong> {{ $donHang->khachHang->loai_kh ?? '' }}</div>
                    <div class="mb-2"><strong>Điện thoại:</strong> {{ $donHang->khachHang->dien_thoai ?? '' }}</div>
                    <div class="mb-2"><strong>Địa chỉ:</strong> {{ $donHang->khachHang->dia_chi ?? '' }}</div>
                    <div><strong>Ngày đặt:</strong> {{ $donHang->ngay_dat ? $donHang->ngay_dat->format('d/m/Y H:i') : '' }}</div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thao tác</h6></div>
                <div class="card-body d-grid gap-2">
                    @if(Auth::guard('admin')->user()->hasRole(1, 3, 5))
                    @if($donHang->trang_thai_dh == 'cho_xu_ly')
                        <form method="POST" action="{{ route('admin.orders.approve', $donHang->ma_don_hang) }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-check-circle me-1"></i>Duyệt đơn hàng
                            </button>
                        </form>
                    @elseif($donHang->trang_thai_dh == 'da_duyet')
                        <a href="{{ route('sales.create', ['order_id' => $donHang->ma_don_hang]) }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-box-arrow-right me-1"></i>Tạo Phiếu xuất kho
                        </a>
                    @endif
                    @if(!in_array($donHang->trang_thai_dh, ['dang_van_chuyen', 'da_hoan_thanh', 'da_huy']))
                        <form method="POST" action="{{ route('admin.orders.cancel', $donHang->ma_don_hang) }}" onsubmit="return confirm('Xác nhận hủy đơn hàng?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-1"></i>Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                    @else
                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Bạn chỉ có quyền xem đơn hàng, không có quyền duyệt hoặc hủy.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
