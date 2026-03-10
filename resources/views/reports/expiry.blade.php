@extends('layouts.app')

@section('title', 'Báo cáo hàng sắp hết hạn')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Hàng sắp hết hạn</h1>
            </div>
            <div class="text-muted small">
                Thống kê các lô hàng sắp hết hạn để chủ động xử lý, ưu tiên xuất trước hoặc trả lại nhà cung cấp.
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
                    <label class="form-label small text-muted mb-1">Khoảng ngày hết hạn</label>
                    <select class="form-select form-select-sm">
                        <option value="30">Trong 30 ngày tới</option>
                        <option value="60">Trong 60 ngày tới</option>
                        <option value="90">Trong 90 ngày tới</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Sản phẩm</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Mã hoặc tên sản phẩm">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Nhà cung cấp</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Tên hoặc mã NCC">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Trạng thái</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="warning">Cảnh báo</option>
                        <option value="critical">Nguy cấp</option>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Danh sách lô hàng sắp hết hạn
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
                        <th class="text-nowrap">Nhà cung cấp</th>
                        <th class="text-nowrap text-center">Mức cảnh báo</th>
                        <th class="text-nowrap text-center">Gợi ý xử lý</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi có lô hàng sắp hết hạn, danh sách sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

