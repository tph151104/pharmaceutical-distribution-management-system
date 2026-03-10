@extends('layouts.app')

@section('title', 'Báo cáo công nợ nhà cung cấp & khách hàng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Báo cáo công nợ NCC & KH</h1>
            </div>
            <div class="text-muted small">
                Theo dõi công nợ phải trả nhà cung cấp và phải thu khách hàng, kèm lịch sử thanh toán.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i>
                Xuất Excel
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Công nợ nhà cung cấp</div>
                    <div class="h4 mb-0 text-danger">₫ 580.000.000</div>
                    <div class="small text-muted mt-1">
                        Tổng số tiền phải trả các nhà cung cấp đến hiện tại.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Công nợ khách hàng</div>
                    <div class="h4 mb-0 text-success">₫ 900.000.000</div>
                    <div class="small text-muted mt-1">
                        Tổng số tiền phải thu từ khách hàng bán sỉ đến hiện tại.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs small mb-3" id="debtTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers-pane"
                    type="button" role="tab">
                Công nợ nhà cung cấp
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers-pane"
                    type="button" role="tab">
                Công nợ khách hàng
            </button>
        </li>
    </ul>

    <div class="tab-content" id="debtTabsContent">
        <div class="tab-pane fade show active" id="suppliers-pane" role="tabpanel">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form class="row g-2 align-items-end">
                        <div class="col-12 col-md-3">
                            <label class="form-label small text-muted mb-1">Nhà cung cấp</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Mã hoặc tên NCC">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label small text-muted mb-1">Khu vực</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option>Miền Bắc</option>
                                <option>Miền Trung</option>
                                <option>Miền Nam</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label small text-muted mb-1">Trạng thái</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option>Đang nợ</option>
                                <option>Đã tất toán</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label small text-muted mb-1">Số ngày quá hạn</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option>>= 0 ngày</option>
                                <option>>= 30 ngày</option>
                                <option>>= 60 ngày</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i>
                                Lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Công nợ theo nhà cung cấp
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
                                <th class="text-nowrap text-end">Tổng phải trả</th>
                                <th class="text-nowrap text-end">Đã thanh toán</th>
                                <th class="text-nowrap text-end">Còn phải trả</th>
                                <th class="text-nowrap text-center">Số ngày quá hạn</th>
                                <th class="text-nowrap text-center">Chi tiết</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted small py-4">
                                    Hiện chưa có dữ liệu. Khi phát sinh nhập hàng và thanh toán, công nợ nhà cung cấp sẽ hiển thị tại đây.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="customers-pane" role="tabpanel">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form class="row g-2 align-items-end">
                        <div class="col-12 col-md-3">
                            <label class="form-label small text-muted mb-1">Khách hàng</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Mã hoặc tên KH">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label small text-muted mb-1">Loại khách hàng</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option>Nhà thuốc</option>
                                <option>Đại lý</option>
                                <option>Phòng khám</option>
                                <option>Bệnh viện</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label small text-muted mb-1">Khu vực</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option>Miền Bắc</option>
                                <option>Miền Trung</option>
                                <option>Miền Nam</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label small text-muted mb-1">Trạng thái</label>
                            <select class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option>Đang nợ</option>
                                <option>Đã tất toán</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i>
                                Lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Công nợ theo khách hàng
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
                                <th class="text-nowrap text-end">Tổng phải thu</th>
                                <th class="text-nowrap text-end">Đã thu</th>
                                <th class="text-nowrap text-end">Còn phải thu</th>
                                <th class="text-nowrap text-center">Vượt hạn mức</th>
                                <th class="text-nowrap text-center">Chi tiết</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted small py-4">
                                    Hiện chưa có dữ liệu. Khi phát sinh bán hàng và thu tiền, công nợ khách hàng sẽ hiển thị tại đây.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

