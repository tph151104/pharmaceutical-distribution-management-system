@extends('layouts.app')

@section('title', 'Không có quyền truy cập')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="text-center">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 mb-4"
             style="width: 100px; height: 100px;">
            <i class="bi bi-shield-x text-danger" style="font-size: 3rem;"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">403 — Không có quyền truy cập</h2>
        <p class="text-muted mb-4" style="max-width: 450px;">
            Xin lỗi, tài khoản của bạn không được phép truy cập chức năng này.
            Vui lòng liên hệ quản trị viên nếu bạn cho rằng đây là lỗi.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-house me-1"></i> Về Dashboard
            </a>
            <button onclick="history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </button>
        </div>
    </div>
</div>
@endsection
