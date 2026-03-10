@extends('layouts.auth')

@section('title', 'Đăng ký tài khoản')

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

    <h5 class="fw-semibold mb-1">Tạo tài khoản mới</h5>
    <p class="text-muted small mb-4">
        Điền thông tin để tạo tài khoản đăng nhập hệ thống.
    </p>

    <form class="row g-3">
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Họ và tên</label>
            <input type="text" class="form-control form-control-sm" placeholder="VD: Nguyễn Văn A">
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Email đăng nhập</label>
            <input type="email" class="form-control form-control-sm" placeholder="you@example.com">
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Số điện thoại</label>
            <input type="text" class="form-control form-control-sm" placeholder="VD: 0901234567">
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Mật khẩu</label>
            <input type="password" class="form-control form-control-sm" placeholder="••••••••">
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Xác nhận mật khẩu</label>
            <input type="password" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu">
        </div>
        <div class="col-12">
            <div class="form-check form-check-sm">
                <input class="form-check-input" type="checkbox" value="" id="termsCheck">
                <label class="form-check-label small" for="termsCheck">
                    Tôi đồng ý với <a href="#" class="text-decoration-none">Điều khoản sử dụng</a>.
                </label>
            </div>
        </div>
        <div class="col-12 d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus me-1"></i>
                Đăng ký
            </button>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Đã có tài khoản? Đăng nhập
            </a>
        </div>
    </form>
@endsection

