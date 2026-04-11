@extends('layouts.app')

@section('title', 'Nhà cung cấp')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-truck text-primary me-2"></i>Nhà cung cấp</h1>
            <p class="text-muted small mb-0 mt-1">Quản lý đối tác cung cấp dược phẩm và vật tư y tế</p>
        </div>
        @if(Auth::guard('admin')->user()->hasRole(1, 5))
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            <i class="bi bi-plus-circle me-2"></i>Thêm nhà cung cấp
        </button>
        @endif
    </div>
@endsection

@section('content')
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Toolbar: Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('suppliers.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-12 col-md-4 position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" class="form-control ps-5" placeholder="Tìm theo tên, mã, SĐT, MST..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    @if(request()->has('search') && request('search') != '')
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nhà cung cấp</th>
                            <th>Liên hệ</th>
                            <th>Mã số thuế</th>
                            <th>Địa chỉ</th>
                            @if(Auth::guard('admin')->user()->hasRole(1, 5))
                            <th class="text-end pe-4">Thao tác</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nhaCungCaps as $ncc)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $ncc->ten_ncc }}</div>
                                    <div class="small text-muted">{{ $ncc->ma_ncc }}</div>
                                </td>
                                <td>
                                    @if($ncc->dien_thoai) <div class="small"><i class="bi bi-telephone text-muted me-1"></i> {{ $ncc->dien_thoai }}</div> @endif
                                    @if($ncc->email) <div class="small"><i class="bi bi-envelope text-muted me-1"></i> {{ $ncc->email }}</div> @endif
                                </td>
                                <td>{{ $ncc->ma_so_thue ?? '--' }}</td>
                                <td><div class="text-truncate" style="max-width: 200px;" title="{{ $ncc->dia_chi }}">{{ $ncc->dia_chi ?? '--' }}</div></td>
                                @if(Auth::guard('admin')->user()->hasRole(1, 5))
                                <td class="text-end pe-4">
                                    <!-- Nút sửa mở modal truyền data -->
                                    <button class="btn btn-sm btn-outline-primary" title="Sửa" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editSupplierModal{{ $ncc->ma_ncc }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    
                                    <!-- Nút xoá -->
                                    <form action="{{ route('suppliers.destroy', $ncc->ma_ncc) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa {{ $ncc->ten_ncc }}?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>

                                <!-- Edit Modal for this loop -->
                                <div class="modal fade" id="editSupplierModal{{ $ncc->ma_ncc }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-bottom-0">
                                                <h5 class="modal-title fw-bold text-primary">Cập nhật Nhà cung cấp</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('suppliers.update', $ncc->ma_ncc) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body pb-0 text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Mã Nhà cung cấp</label>
                                                        <input type="text" class="form-control" value="{{ $ncc->ma_ncc }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Tên Nhà cung cấp <span class="text-danger">*</span></label>
                                                        <input type="text" name="ten_ncc" class="form-control" value="{{ $ncc->ten_ncc }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Điện thoại</label>
                                                            <input type="text" name="dien_thoai" class="form-control" value="{{ $ncc->dien_thoai }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Mã số thuế</label>
                                                            <input type="text" name="ma_so_thue" class="form-control" value="{{ $ncc->ma_so_thue }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $ncc->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Địa chỉ</label>
                                                        <textarea name="dia_chi" class="form-control" rows="2">{{ $ncc->dia_chi }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Ghi chú</label>
                                                        <textarea name="ghi_chu" class="form-control" rows="2">{{ $ncc->ghi_chu }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary px-4">Lưu cập nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-buildings display-4 d-block mb-3 text-secondary opacity-50"></i>
                                    Không tìm thấy nhà cung cấp nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($nhaCungCaps->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $nhaCungCaps->links() }}
            </div>
        @endif
    </div>

    @if(Auth::guard('admin')->user()->hasRole(1, 5))
    <!-- Create Modal -->
    <div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-primary">Thêm Nhà cung cấp mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body pb-0">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên Nhà cung cấp <span class="text-danger">*</span></label>
                            <input type="text" name="ten_ncc" class="form-control" required placeholder="Nhập tên đối tác">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Điện thoại</label>
                                <input type="text" name="dien_thoai" class="form-control" placeholder="0123...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mã số thuế</label>
                                <input type="text" name="ma_so_thue" class="form-control" placeholder="123456...">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="contact@domain.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Địa chỉ</label>
                            <textarea name="dia_chi" class="form-control" rows="2" placeholder="Địa chỉ chi tiết..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Thông tin thêm..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4">Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection
