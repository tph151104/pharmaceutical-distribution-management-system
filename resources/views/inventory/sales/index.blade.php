@extends('layouts.app')

@section('title', 'Danh sách phiếu xuất kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Phiếu xuất kho bán sỉ</h1>
            </div>
            <div class="text-muted small">
                Quản lý các phiếu xuất kho bán sỉ cho khách hàng, theo dõi doanh số và trạng thái thanh toán.
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Lập phiếu xuất mới
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Từ ngày</label>
                    <input type="date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Đến ngày</label>
                    <input type="date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Khách hàng</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Nhập tên hoặc mã KH">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Thanh toán</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="unpaid">Chưa thanh toán</option>
                        <option value="partial">Thanh toán một phần</option>
                        <option value="paid">Đã thanh toán</option>
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
                Danh sách phiếu xuất kho
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">0</span> phiếu
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr class="small text-muted">
                        <th class="text-nowrap">Số phiếu</th>
                        <th class="text-nowrap">Ngày xuất</th>
                        <th class="text-nowrap">Khách hàng</th>
                        <th class="text-nowrap text-end">Tổng tiền (VNĐ)</th>
                        <th class="text-nowrap text-center">Trạng thái thanh toán</th>
                        <th class="text-nowrap text-center">Công nợ</th>
                        <th class="text-nowrap text-center">Nhân viên</th>
                        <th class="text-nowrap text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi có phiếu xuất, danh sách sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

