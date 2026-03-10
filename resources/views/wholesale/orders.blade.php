@extends('layouts.wholesale')

@section('title', 'Đơn hàng mua sỉ của tôi')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <div>
            <h5 class="mb-1">Đơn hàng mua sỉ của tôi</h5>
            <div class="text-muted small">
                Theo dõi tình trạng các đơn đặt hàng mua sỉ mà bạn đã gửi.
            </div>
        </div>
        <a href="{{ route('wholesale.catalog') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-cart-plus me-1"></i>Tạo đơn mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Danh sách đơn hàng
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> đơn
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Mã đơn hàng</th>
                        <th class="text-nowrap">Ngày gửi</th>
                        <th class="text-nowrap">Tổng tiền (VNĐ)</th>
                        <th class="text-nowrap text-center">Trạng thái xử lý</th>
                        <th class="text-nowrap text-center">Trạng thái giao hàng</th>
                        <th class="text-nowrap text-center">Chi tiết</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="6" class="text-center text-muted small py-4">
                            Hiện chưa có đơn hàng. Hãy vào mục <strong>Danh sách sản phẩm</strong> để đặt đơn mới.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

