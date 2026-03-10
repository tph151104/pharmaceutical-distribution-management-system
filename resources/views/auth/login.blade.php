@extends('layouts.auth')

@section('title', 'Đăng nhập hệ thống')

@section('content')
    <div class="mb-4 d-md-none">
        <div class="auth-brand mb-3">
            <div class="auth-logo">
                <i class="bi bi-capsule-pill fs-4"></i>
            </div>
            <div>
                <div class="fw-semibold">PharmaDistrib</div>
                <div class="small text-muted">Hệ thống phân phối thuốc tây</div>
            </div>
        </div>
    </div>

    <h5 class="fw-semibold mb-1">Chào mừng quay lại</h5>
    <p class="text-muted small mb-4">
        Đăng nhập để tiếp tục quản lý kho, đơn hàng và công nợ.
    </p>

    <form class="row g-3">
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Email đăng nhập</label>
            <input type="email" class="form-control form-control-sm" placeholder="you@example.com">
        </div>
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label small text-muted mb-0">Mật khẩu</label>
                <a href="#" class="small text-decoration-none">Quên mật khẩu?</a>
            </div>
            <input type="password" class="form-control form-control-sm" placeholder="••••••••">
        </div>
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="form-check form-check-sm">
                <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                <label class="form-check-label small" for="rememberMe">
                    Ghi nhớ đăng nhập
                </label>
            </div>
        </div>
        <div class="col-12 d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Đăng nhập
            </button>
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-person-plus me-1"></i>
                Đăng ký tài khoản mới
            </a>
        </div>
    </form>
@endsection

