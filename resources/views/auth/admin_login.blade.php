@extends('layouts.auth')

@section('title', 'Đăng nhập Quản trị')

@section('content')
<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3"
         style="width: 64px; height: 64px;">
        <i class="bi bi-shield-lock-fill text-primary fs-2"></i>
    </div>
    <h4 class="fw-bold text-dark mb-1">Đăng nhập Quản trị</h4>
    <p class="text-muted small">Dành cho nhân viên & quản lý nội bộ</p>
</div>

@if($errors->has('error'))
    <div class="alert alert-danger py-2 small">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        {{ $errors->first('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success py-2 small">
        <i class="bi bi-check-circle-fill me-1"></i>
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.auth.login') }}">
    @csrf

    <div class="mb-3">
        <label for="ten_dang_nhap" class="form-label fw-semibold small">Tên đăng nhập</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-person text-muted"></i>
            </span>
            <input type="text"
                   class="form-control border-start-0 ps-0"
                   id="ten_dang_nhap"
                   name="ten_dang_nhap"
                   value="{{ old('ten_dang_nhap') }}"
                   placeholder="Nhập tên đăng nhập"
                   required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label for="mat_khau" class="form-label fw-semibold small">Mật khẩu</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-lock text-muted"></i>
            </span>
            <input type="password"
                   class="form-control border-start-0 ps-0"
                   id="mat_khau"
                   name="mat_khau"
                   placeholder="Nhập mật khẩu"
                   required>
        </div>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
        <i class="bi bi-box-arrow-in-right me-1"></i>
        Đăng nhập
    </button>
</form>

<div class="text-center mt-4">
    <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i>
        Đăng nhập với tư cách Khách hàng
    </a>
</div>
@endsection
