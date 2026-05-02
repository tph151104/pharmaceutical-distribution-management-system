@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Đổi mật khẩu</h1>
            </div>
            <div class="text-muted small">
                Đổi mật khẩu đăng nhập để đảm bảo an toàn cho tài khoản của bạn.
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Thông tin đổi mật khẩu
                    </div>
                </div>
                <form action="{{ route('account.updatePassword') }}" method="POST">
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
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="form-control form-control-sm" placeholder="Nhập mật khẩu hiện tại" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control form-control-sm" placeholder="Nhập mật khẩu mới" required minlength="6">
                                <div class="small text-muted mt-1">
                                    Mật khẩu nên có ít nhất 6 ký tự.
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu mới" required minlength="6">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Sau khi đổi mật khẩu, lần đăng nhập tiếp theo sẽ sử dụng mật khẩu mới.
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-shield-lock me-1"></i>
                                Lưu mật khẩu mới
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

