@extends('layouts.wholesale')

@section('title', 'Giỏ hàng mua sỉ')

@section('content')
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="d-flex justify-content-between align-items-end mb-2">
                <div>
                    <h5 class="mb-1">Giỏ hàng mua sỉ</h5>
                    <div class="text-muted small">
                        Kiểm tra lại số lượng và thông tin trước khi gửi đơn đặt hàng.
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
                                <th>Sản phẩm</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="fw-semibold small">Paracetamol 500mg</div>
                                    <div class="text-muted small">Mã: PARA500 • Hộp 10 vỉ x 10 viên</div>
                                </td>
                                <td class="text-end small">₫ 120.000</td>
                                <td class="text-center" style="width: 120px;">
                                    <input type="number" min="1" value="10" class="form-control form-control-sm text-end">
                                </td>
                                <td class="text-end small fw-semibold text-primary">₫ 1.200.000</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-link text-danger p-0">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-center text-muted small py-4">
                                    (Dữ liệu mẫu minh họa. Sau này sẽ hiển thị các sản phẩm khách đã chọn.)
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Thông tin xuất hóa đơn / giao hàng
                    </div>
                </div>
                <div class="card-body">
                    <form class="row g-2">
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Tên nhà thuốc / đơn vị</label>
                            <input type="text" class="form-control form-control-sm" placeholder="VD: Nhà thuốc Hoa Mai">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Địa chỉ giao hàng</label>
                            <textarea class="form-control form-control-sm" rows="2"
                                      placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Ghi chú cho đơn hàng</label>
                            <textarea class="form-control form-control-sm" rows="2"
                                      placeholder="Ví dụ: Giao giờ hành chính, liên hệ trước khi giao..."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Tóm tắt đơn hàng
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Tạm tính</span>
                        <span>₫ 1.200.000</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Chiết khấu</span>
                        <span>₫ 0</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="fw-semibold">Tổng tiền</span>
                        <span class="fw-semibold text-primary">₫ 1.200.000</span>
                    </div>
                    <button class="btn btn-primary btn-sm w-100 mt-2">
                        <i class="bi bi-send-check me-1"></i>
                        Gửi yêu cầu đặt hàng
                    </button>
                    <div class="small text-muted mt-2">
                        Sau khi gửi, bộ phận kinh doanh sẽ liên hệ xác nhận đơn hàng và thời gian giao hàng với bạn.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

