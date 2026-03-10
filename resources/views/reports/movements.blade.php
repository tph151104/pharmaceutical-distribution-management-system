@extends('layouts.app')

@section('title', 'Lịch sử nhập xuất kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Lịch sử nhập xuất kho</h1>
            </div>
            <div class="text-muted small">
                Xem chi tiết các giao dịch nhập - xuất kho theo thời gian, sản phẩm, chứng từ.
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
                    <label class="form-label small text-muted mb-1">Từ ngày</label>
                    <input type="date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Đến ngày</label>
                    <input type="date" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Sản phẩm</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Mã hoặc tên sản phẩm">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Loại chứng từ</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="import">Phiếu nhập</option>
                        <option value="export">Phiếu xuất</option>
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
                Lịch sử nhập xuất kho chi tiết
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
                        <th class="text-nowrap">Ngày chứng từ</th>
                        <th class="text-nowrap">Số chứng từ</th>
                        <th class="text-nowrap">Loại</th>
                        <th class="text-nowrap">Sản phẩm</th>
                        <th class="text-nowrap">Số lô</th>
                        <th class="text-nowrap text-end">SL nhập</th>
                        <th class="text-nowrap text-end">SL xuất</th>
                        <th class="text-nowrap text-end">Tồn sau giao dịch</th>
                        <th class="text-nowrap">Đối tượng</th>
                        <th class="text-nowrap text-center">Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="10" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi có phát sinh nhập xuất, lịch sử sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

