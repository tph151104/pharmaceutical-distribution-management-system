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
                            <span class="fw-semibold">{{ strtoupper(substr($user->ho_ten_nd ?? 'A', 0, 2)) }}</span>
                        </div>
                    </div>
                    <h5 class="mb-0">{{ $user->ho_ten_nd }}</h5>
                    <div class="text-muted small mb-2">{{ $user->ten_dang_nhap }}</div>
                    <div class="badge bg-light text-secondary border small">
                        <i class="bi bi-shield-lock me-1"></i>Vai trò: {{ $user->roleName }}
                    </div>
                </div>
                <div class="card-footer bg-white small text-muted text-center">
                    @if($user->trang_thai == 'hoat_dong')
                        <span class="text-success"><i class="bi bi-circle-fill small me-1"></i>Đang hoạt động</span>
                    @else
                        <span class="text-danger"><i class="bi bi-circle-fill small me-1"></i>Bị khóa</span>
                    @endif
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
                <form action="{{ route('account.updateProfile') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show small" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Họ và tên</label>
                                <input type="text" name="ho_ten_nd" class="form-control form-control-sm" value="{{ old('ho_ten_nd', $user->ho_ten_nd) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Tên đăng nhập</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->ten_dang_nhap }}" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Email đăng nhập</label>
                                <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Số điện thoại</label>
                                <input type="text" name="sdt" class="form-control form-control-sm" value="{{ old('sdt', $user->sdt) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Chức vụ (Vai trò)</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $user->roleName }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i>Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

