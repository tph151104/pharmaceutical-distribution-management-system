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

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h5 class="fw-semibold mb-1">Chào mừng quay lại</h5>
    <p class="text-muted small mb-4">
        Đăng nhập khu vực Khách hàng / Đại lý phân phối.
    </p>

    <form method="POST" action="{{ route('login') }}" class="row g-3">
        @csrf
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Tên đăng nhập (Username)</label>
            <input type="text" name="ten_dang_nhap" class="form-control form-control-sm" value="{{ old('ten_dang_nhap') }}" required autofocus placeholder="congty_abc">
        </div>
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label small text-muted mb-0">Mật khẩu</label>
                <a href="#" class="small text-decoration-none">Quên mật khẩu?</a>
            </div>
            <input type="password" name="mat_khau" class="form-control form-control-sm" required placeholder="••••••••">
        </div>
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="form-check form-check-sm">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
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
