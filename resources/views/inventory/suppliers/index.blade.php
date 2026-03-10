@extends('layouts.app')

@section('title', 'Danh mục nhà cung cấp')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Danh mục nhà cung cấp</h1>
            </div>
            <div class="text-muted small">
                Quản lý thông tin nhà cung cấp: mã, tên, liên hệ, khu vực và công nợ hiện tại.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#supplierModal">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm nhà cung cấp
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
                    <input type="text" class="form-control form-control-sm" placeholder="Mã, tên, SĐT, email...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Khu vực</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả khu vực</option>
                        <option>Miền Bắc</option>
                        <option>Miền Trung</option>
                        <option>Miền Nam</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Trạng thái</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="active">Đang giao dịch</option>
                        <option value="inactive">Ngừng giao dịch</option>
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
                Danh sách nhà cung cấp
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> nhà cung cấp
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Mã NCC</th>
                        <th class="text-nowrap">Tên nhà cung cấp</th>
                        <th class="text-nowrap">Liên hệ</th>
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
                            Hiện chưa có dữ liệu. Khi thêm nhà cung cấp, danh sách sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm / sửa nhà cung cấp -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">Thêm nhà cung cấp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Mã nhà cung cấp</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: NCC001">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small text-muted mb-1">Tên nhà cung cấp</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: Công ty Dược ABC">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Người liên hệ</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: Nguyễn Văn A">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label small text-muted mb-1">Điện thoại</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: 0901234567">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label small text-muted mb-1">Email</label>
                                <input type="email" class="form-control form-control-sm" placeholder="VD: contact@abc.com">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small text-muted mb-1">Địa chỉ</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Số nhà, đường, phường/xã, quận/huyện">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small text-muted mb-1">Tỉnh/TP</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: TP.HCM">
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
                                          placeholder="Ghi chú riêng về điều khoản thanh toán, chiết khấu..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>
                        Lưu nhà cung cấp
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

