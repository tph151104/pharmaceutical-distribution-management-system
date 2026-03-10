@extends('layouts.app')

@section('title', 'Tồn kho chi tiết theo lô hàng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Tồn kho theo lô hàng</h1>
            </div>
            <div class="text-muted small">
                Xem chi tiết tồn kho đến từng lô: số lô, hạn dùng, tồn hiện tại, giá vốn và nhà cung cấp.
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
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Sản phẩm</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Mã hoặc tên sản phẩm">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Số lô</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Nhập số lô">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Khoảng hạn dùng</label>
                    <div class="d-flex gap-1">
                        <input type="date" class="form-control form-control-sm">
                        <input type="date" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Tình trạng lô</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="normal">Bình thường</option>
                        <option value="warning">Sắp hết hạn</option>
                        <option value="expired">Đã hết hạn</option>
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

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Tổng số lô đang còn tồn</div>
                    <div class="h4 mb-0">0 lô</div>
                    <div class="small text-muted mt-1">
                        Bao gồm tất cả các lô còn tồn số lượng dương.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Lô sắp hết hạn</div>
                    <div class="h4 mb-0 text-warning">0 lô</div>
                    <div class="small text-muted mt-1">
                        Hạn dùng trong vòng 60 ngày tới.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Giá trị tồn kho theo lô</div>
                    <div class="h4 mb-0 text-primary">₫ 0</div>
                    <div class="small text-muted mt-1">
                        Tính theo giá vốn của từng lô hàng.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Tồn kho chi tiết theo lô
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> lô
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Sản phẩm</th>
                        <th class="text-nowrap">Số lô</th>
                        <th class="text-nowrap">Hạn dùng</th>
                        <th class="text-nowrap text-end">Số ngày còn lại</th>
                        <th class="text-nowrap text-end">Tồn lô</th>
                        <th class="text-nowrap text-end">Giá vốn lô</th>
                        <th class="text-nowrap text-end">Giá trị tồn (VNĐ)</th>
                        <th class="text-nowrap">Nhà cung cấp</th>
                        <th class="text-nowrap text-center">Tình trạng</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="9" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi phát sinh nhập hàng theo lô, tồn kho chi tiết sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

