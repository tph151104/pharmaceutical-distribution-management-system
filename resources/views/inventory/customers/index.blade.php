@extends('layouts.app')

@section('title', 'Danh mục khách hàng bán sỉ')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Danh mục khách hàng bán sỉ</h1>
            </div>
            <div class="text-muted small">
                Quản lý thông tin khách hàng bán sỉ: nhà thuốc, đại lý, phòng khám, bệnh viện cùng công nợ phải thu.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#customerModal">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm khách hàng
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Từ khóa</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Mã, tên, SĐT, MST...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Loại khách hàng</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả loại</option>
                        <option>Nhà thuốc</option>
                        <option>Đại lý</option>
                        <option>Phòng khám</option>
                        <option>Bệnh viện</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Khu vực</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả khu vực</option>
                        <option>Miền Bắc</option>
                        <option>Miền Trung</option>
                        <option>Miền Nam</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Công nợ</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="has-debt">Có công nợ</option>
                        <option value="no-debt">Không công nợ</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i>
                        Tìm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Danh sách khách hàng bán sỉ
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> khách hàng
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Mã KH</th>
                        <th class="text-nowrap">Tên khách hàng</th>
                        <th class="text-nowrap">Loại KH</th>
                        <th class="text-nowrap">Điện thoại</th>
                        <th class="text-nowrap">Khu vực</th>
                        <th class="text-nowrap text-end">Công nợ hiện tại</th>
                        <th class="text-nowrap text-center">Trạng thái</th>
                        <th class="text-nowrap text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi thêm khách hàng, danh sách sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm / sửa khách hàng -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Thêm khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Mã khách hàng</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: KH0001">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small text-muted mb-1">Tên khách hàng</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: Nhà thuốc Hoa Mai">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Loại khách hàng</label>
                                <select class="form-select form-select-sm">
                                    <option>Nhà thuốc</option>
                                    <option>Đại lý</option>
                                    <option>Phòng khám</option>
                                    <option>Bệnh viện</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label small text-muted mb-1">Điện thoại</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: 0909876543">
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label small text-muted mb-1">Email</label>
                                <input type="email" class="form-control form-control-sm" placeholder="VD: nhathuoc@example.com">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small text-muted mb-1">Địa chỉ</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Số nhà, đường, phường/xã, quận/huyện">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small text-muted mb-1">Tỉnh/TP</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: Hà Nội">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small text-muted mb-1">Khu vực</label>
                                <select class="form-select form-select-sm">
                                    <option>Miền Bắc</option>
                                    <option>Miền Trung</option>
                                    <option>Miền Nam</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Mã số thuế</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nhập MST (nếu có)">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Hạn mức công nợ</label>
                                <input type="number" class="form-control form-control-sm text-end" value="0">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Trạng thái</label>
                                <select class="form-select form-select-sm">
                                    <option value="active">Đang giao dịch</option>
                                    <option value="inactive">Ngừng giao dịch</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Ghi chú</label>
                                <textarea class="form-control form-control-sm" rows="2"
                                          placeholder="Ghi chú về điều khoản thanh toán, chiết khấu, tuyến bán hàng..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>
                        Lưu khách hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

