@extends('layouts.app')

@section('title', 'Danh mục thuốc / sản phẩm')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Danh mục thuốc / sản phẩm</h1>
            </div>
            <div class="text-muted small">
                Quản lý danh sách thuốc và sản phẩm kinh doanh: mã, tên, hàm lượng, đơn vị, nhóm, trạng thái.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm sản phẩm
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
                    <input type="text" class="form-control form-control-sm" placeholder="Mã, tên, hoạt chất...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Nhóm sản phẩm</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả nhóm</option>
                        <option>Thuốc kê đơn</option>
                        <option>Thuốc không kê đơn</option>
                        <option>Thực phẩm chức năng</option>
                        <option>Dụng cụ y tế</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Trạng thái</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="active">Đang kinh doanh</option>
                        <option value="inactive">Ngừng kinh doanh</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Quy cách</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option>Hộp</option>
                        <option>Vỉ</option>
                        <option>Chai</option>
                        <option>Ống</option>
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
                Danh sách thuốc / sản phẩm
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> sản phẩm
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Mã SP</th>
                        <th class="text-nowrap">Tên sản phẩm</th>
                        <th class="text-nowrap">Hoạt chất / Hàm lượng</th>
                        <th class="text-nowrap">Đơn vị</th>
                        <th class="text-nowrap">Quy cách</th>
                        <th class="text-nowrap">Nhóm</th>
                        <th class="text-nowrap text-center">Trạng thái</th>
                        <th class="text-nowrap text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi thêm sản phẩm, danh sách sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm / sửa sản phẩm -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Mã sản phẩm</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: PARA500">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small text-muted mb-1">Tên sản phẩm</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: Paracetamol 500mg">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Hoạt chất / Hàm lượng</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: Paracetamol 500mg">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label small text-muted mb-1">Đơn vị tính</label>
                                <select class="form-select form-select-sm">
                                    <option>Hộp</option>
                                    <option>Vỉ</option>
                                    <option>Viên</option>
                                    <option>Chai</option>
                                    <option>Ống</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label small text-muted mb-1">Quy cách đóng gói</label>
                                <input type="text" class="form-control form-control-sm" placeholder="VD: 10 vỉ x 10 viên">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Nhóm sản phẩm</label>
                                <select class="form-select form-select-sm">
                                    <option>Thuốc kê đơn</option>
                                    <option>Thuốc không kê đơn</option>
                                    <option>Thực phẩm chức năng</option>
                                    <option>Dụng cụ y tế</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Mức tồn tối thiểu</label>
                                <input type="number" class="form-control form-control-sm text-end" value="0">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small text-muted mb-1">Trạng thái</label>
                                <select class="form-select form-select-sm">
                                    <option value="active">Đang kinh doanh</option>
                                    <option value="inactive">Ngừng kinh doanh</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Ghi chú</label>
                                <textarea class="form-control form-control-sm" rows="2"
                                          placeholder="Ghi chú thêm về hướng dẫn sử dụng, bảo quản..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>
                        Lưu sản phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

