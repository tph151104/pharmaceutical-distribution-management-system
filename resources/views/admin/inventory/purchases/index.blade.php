@extends('layouts.app')

@section('title', 'Danh sách phiếu nhập kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Phiếu nhập kho (mua hàng)</h1>
            </div>
            <div class="text-muted small">
                Quản lý các phiếu nhập kho từ nhà cung cấp, theo dõi tổng tiền và trạng thái xử lý.
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Lập phiếu nhập mới
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
                    <label class="form-label small text-muted mb-1">Nhà cung cấp</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Nhập tên hoặc mã NCC">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Trạng thái</label>
                    <select class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="draft">Tạm</option>
                        <option value="completed">Hoàn tất</option>
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
                Danh sách phiếu nhập kho
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
                        <th class="text-nowrap">Ngày nhập</th>
                        <th class="text-nowrap">Nhà cung cấp</th>
                        <th class="text-nowrap text-end">Tổng tiền (VNĐ)</th>
                        <th class="text-nowrap text-center">Trạng thái</th>
                        <th class="text-nowrap text-center">Công nợ</th>
                        <th class="text-nowrap text-center">Nhân viên</th>
                        <th class="text-nowrap text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8" class="text-center text-muted small py-4">
                            Hiện chưa có dữ liệu. Khi có phiếu nhập, danh sách sẽ hiển thị tại đây.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

