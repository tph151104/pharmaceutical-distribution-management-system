@extends('layouts.app')

@section('title', 'Báo cáo tồn kho tổng hợp')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Báo cáo tồn kho tổng hợp</h1>
            </div>
            <div class="text-muted small">
                Thống kê tồn kho hiện tại theo sản phẩm, giá trị tồn và mức cảnh báo tồn tối thiểu.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i>
                Xuất Excel
            </button>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i>
                In báo cáo
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Tại ngày</label>
                    <input type="date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Sản phẩm</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Mã hoặc tên sản phẩm">
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
                    <label class="form-label small text-muted mb-1">Mức tồn</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="below-min">Dưới tồn tối thiểu</option>
                        <option value="above-min">Trên tồn tối thiểu</option>
                        <option value="zero">Hết hàng</option>
                    </select>
                </div>
                <div class="col-12 col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Tổng giá trị tồn kho</div>
                    <div class="h4 mb-0">₫ 2.150.000.000</div>
                    <div class="small text-muted mt-1">
                        Tính theo giá vốn gần nhất.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Sản phẩm dưới tồn tối thiểu</div>
                    <div class="h4 mb-0">18 sản phẩm</div>
                    <div class="small text-danger mt-1">
                        Cần nhập bổ sung sớm.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Sản phẩm hết hàng</div>
                    <div class="h4 mb-0">6 sản phẩm</div>
                    <div class="small text-muted mt-1">
                        Nên ưu tiên đặt hàng lại.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Tồn kho theo sản phẩm
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> dòng
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Mã SP</th>
                        <th class="text-nowrap">Tên sản phẩm</th>
                        <th class="text-nowrap">Đơn vị</th>
                        <th class="text-nowrap text-end">Tồn kho</th>
                        <th class="text-nowrap text-end">Tồn tối thiểu</th>
                        <th class="text-nowrap text-end">Giá vốn TB</th>
                        <th class="text-nowrap text-end">Giá trị tồn (VNĐ)</th>
                        <th class="text-nowrap text-center">Cảnh báo</th>
                        <th class="text-nowrap text-center">Chi tiết lô</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="9" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi có phát sinh nhập xuất, báo cáo tồn kho sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

