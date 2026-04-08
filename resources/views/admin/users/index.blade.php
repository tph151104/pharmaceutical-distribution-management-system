@extends('layouts.app')

@section('title', 'Quản lý Người dùng')

@section('content-header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="content-header-title mb-1">Quản lý Người dùng</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Người dùng</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-plus-lg me-1"></i> Thêm người dùng
    </button>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Bộ lọc --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" name="search"
                       placeholder="Tìm tên, username, email..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" name="role">
                    <option value="">Tất cả chức vụ</option>
                    @foreach(\App\Models\NguoiDung::ROLE_NAMES as $id => $name)
                        <option value="{{ $id }}" {{ request('role') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" name="trang_thai">
                    <option value="">Tất cả trạng thái</option>
                    <option value="cho_phep_hd" {{ request('trang_thai') == 'cho_phep_hd' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="vo_hieu_hoa" {{ request('trang_thai') == 'vo_hieu_hoa' ? 'selected' : '' }}>Vô hiệu hóa</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-search me-1"></i> Lọc
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Bảng danh sách --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mã</th>
                        <th>Họ tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Chức vụ</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-3 fw-semibold text-primary">{{ $user->ma_nguoi_dung }}</td>
                        <td>{{ $user->ho_ten_nd }}</td>
                        <td><code>{{ $user->ten_dang_nhap }}</code></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->sdt }}</td>
                        <td>
                            @php
                                $roleBadges = [
                                    1 => 'bg-danger',
                                    2 => 'bg-warning text-dark',
                                    3 => 'bg-success',
                                    4 => 'bg-info text-dark',
                                    5 => 'bg-primary',
                                ];
                            @endphp
                            <span class="badge {{ $roleBadges[$user->role] ?? 'bg-secondary' }}">
                                {{ $user->role_name }}
                            </span>
                        </td>
                        <td>
                            @if($user->trang_thai === 'cho_phep_hd')
                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Vô hiệu hóa</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Sửa"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                        data-id="{{ $user->ma_nguoi_dung }}"
                                        data-ho_ten="{{ $user->ho_ten_nd }}"
                                        data-ten_dang_nhap="{{ $user->ten_dang_nhap }}"
                                        data-email="{{ $user->email }}"
                                        data-sdt="{{ $user->sdt }}"
                                        data-role="{{ $user->role }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.users.toggleStatus', $user->ma_nguoi_dung) }}"
                                      class="d-inline" onsubmit="return confirm('Xác nhận thay đổi trạng thái?')">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-outline-warning" title="{{ $user->trang_thai === 'cho_phep_hd' ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                        <i class="bi bi-{{ $user->trang_thai === 'cho_phep_hd' ? 'lock' : 'unlock' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user->ma_nguoi_dung) }}"
                                      class="d-inline" onsubmit="return confirm('Xác nhận xóa người dùng này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            Chưa có người dùng nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tạo người dùng --}}
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Thêm người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ho_ten_nd" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_dang_nhap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="mat_khau" required minlength="6">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sdt" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chức vụ <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" required>
                            <option value="">-- Chọn chức vụ --</option>
                            @foreach(\App\Models\NguoiDung::ROLE_NAMES as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Tạo người dùng
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sửa người dùng --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editUserForm">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Sửa người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ho_ten_nd" id="edit_ho_ten" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="edit_ten_dang_nhap" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="mat_khau" placeholder="Để trống nếu không đổi" minlength="6">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sdt" id="edit_sdt" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chức vụ <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" id="edit_role" required>
                            @foreach(\App\Models\NguoiDung::ROLE_NAMES as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Cập nhật
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editUserModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const id = btn.dataset.id;
        const form = document.getElementById('editUserForm');
        form.action = '{{ route("admin.users.index") }}/' + id;

        document.getElementById('edit_ho_ten').value = btn.dataset.ho_ten;
        document.getElementById('edit_ten_dang_nhap').value = btn.dataset.ten_dang_nhap;
        document.getElementById('edit_email').value = btn.dataset.email;
        document.getElementById('edit_sdt').value = btn.dataset.sdt;
        document.getElementById('edit_role').value = btn.dataset.role;
    });
});
</script>
@endpush
