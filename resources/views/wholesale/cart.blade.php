@extends('layouts.wholesale')

@section('title', 'Giỏ hàng mua sỉ')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="d-flex justify-content-between align-items-end mb-2">
                <div>
                    <h5 class="mb-1">Giỏ hàng mua sỉ</h5>
                    <div class="text-muted small">
                        {{ count($cart) }} sản phẩm trong giỏ hàng
                    </div>
                </div>
                <a href="{{ route('wholesale.catalog') }}" class="small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Tiếp tục chọn sản phẩm
                </a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small text-muted">
                            <tr>
                                <th class="ps-3">Sản phẩm</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($cart as $key => $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold small">{{ $item['ten_thuoc'] }}</div>
                                        <div class="text-muted small">Mã: {{ $item['ma_thuoc'] }}</div>
                                    </td>
                                    <td class="text-end small">{{ number_format($item['don_gia']) }}đ</td>
                                    <td class="text-center" style="width: 120px;">
                                        <form method="POST" action="{{ route('wholesale.cart.update') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="ma_thuoc" value="{{ $item['ma_thuoc'] }}">
                                            <input type="number" name="so_luong" min="1" value="{{ $item['so_luong'] }}" class="form-control form-control-sm text-center" style="width: 70px; display:inline-block;" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="text-end small fw-semibold text-primary">{{ number_format($item['don_gia'] * $item['so_luong']) }}đ</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('wholesale.cart.remove') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="ma_thuoc" value="{{ $item['ma_thuoc'] }}">
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                        Giỏ hàng trống. <a href="{{ route('wholesale.catalog') }}">Bắt đầu mua sắm</a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            @if(count($cart) > 0)
            <form method="POST" action="{{ route('wholesale.order.place') }}">
                @csrf
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0">
                        <div class="fw-semibold small text-uppercase text-muted">Thông tin giao hàng</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small text-muted mb-1">Tên nhà thuốc / đơn vị</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $customer->ten_kh ?? '' }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted mb-1">Địa chỉ giao hàng</label>
                            <textarea name="dia_chi_giao" class="form-control form-control-sm" rows="2" placeholder="Nhập địa chỉ nhận hàng">{{ $customer->dia_chi ?? '' }}</textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small text-muted mb-1">Ghi chú đơn hàng</label>
                            <textarea name="ghi_chu" class="form-control form-control-sm" rows="2" placeholder="Ví dụ: Giao buổi sáng..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <div class="fw-semibold small text-uppercase text-muted">Tóm tắt đơn hàng</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Tạm tính ({{ count($cart) }} SP)</span>
                            <span>{{ number_format($tongTien) }}đ</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-semibold">Tổng tiền</span>
                            <span class="fw-semibold text-primary fs-6">{{ number_format($tongTien) }}đ</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">
                            <i class="bi bi-send-check me-1"></i>
                            Gửi yêu cầu đặt hàng
                        </button>
                        <div class="small text-muted mt-2">
                            Sau khi gửi, bộ phận kinh doanh sẽ xác nhận đơn hàng và thời gian giao hàng.
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
@endsection
