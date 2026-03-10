@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Thông tin cá nhân</h1>
            </div>
            <div class="text-muted small">
                Xem và cập nhật thông tin tài khoản của bạn trong hệ thống phân phối thuốc.
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center"
                             style="width:80px;height:80px;font-size:2rem;">
                            <span class="fw-semibold">AD</span>
                        </div>
                    </div>
                    <h5 class="mb-0">Admin</h5>
                    <div class="text-muted small mb-2">Quản trị hệ thống</div>
                    <div class="badge bg-light text-secondary border small">
                        <i class="bi bi-shield-lock me-1"></i>Vai trò: Quản trị
                    </div>
                </div>
                <div class="card-footer bg-white small text-muted">
                    Lần đăng nhập gần nhất: 01/03/2026 09:15
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Thông tin tài khoản
                    </div>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted mb-1">Họ và tên</label>
                            <input type="text" class="form-control form-control-sm" value="Admin">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted mb-1">Email đăng nhập</label>
                            <input type="email" class="form-control form-control-sm" value="admin@example.com">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted mb-1">Số điện thoại</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted mb-1">Chức vụ</label>
                            <input type="text" class="form-control form-control-sm" placeholder="VD: Quản lý kho, Kế toán...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Ghi chú</label>
                            <textarea class="form-control form-control-sm" rows="2"
                                      placeholder="Ghi chú nội bộ (ví dụ: phụ trách kho khu vực nào, ca làm việc...)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <button class="btn btn-light btn-sm">Hủy</button>
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>Cập nhật
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

