@extends('layouts.wholesale')

@section('title', 'Thông Tin Cá Nhân')

@section('content')
<div class="row justify-content-center g-4">
    <div class="col-lg-3">
        <!-- Sidebar -->
        <div class="card border-0 shadow-sm text-center p-4">
            @if($customer->hinh_dai_dien)
                <img src="{{ asset($customer->hinh_dai_dien) }}" class="rounded-circle mx-auto mb-3" width="100" height="100" style="object-fit:cover;" alt="Avatar">
            @else
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;">
                    <i class="bi bi-person-fill fs-1 text-primary"></i>
                </div>
            @endif
            <h6 class="fw-bold mb-0">{{ $customer->ten_kh }}</h6>
            <div class="text-muted small">{{ $customer->ten_dang_nhap }}</div>
            @php
                $loaiMap = ['nha_thuoc' => 'Nhà thuốc', 'dai_ly' => 'Đại lý', 'phong_kham' => 'Phòng khám', 'benh_vien' => 'Bệnh viện'];
            @endphp
            <span class="badge bg-primary-subtle text-primary mt-2">{{ $loaiMap[$customer->loai_kh] ?? $customer->loai_kh }}</span>
        </div>

        <div class="list-group mt-3 border-0 shadow-sm">
            <a href="{{ route('wholesale.profile') }}" class="list-group-item list-group-item-action active">
                <i class="bi bi-person me-2"></i>Thông tin cá nhân
            </a>
            <a href="{{ route('wholesale.orders.index') }}" class="list-group-item list-group-item-action">
                <i class="bi bi-bag me-2"></i>Đơn hàng của tôi
            </a>
        </div>
    </div>

    <div class="col-lg-9">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('wholesale.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Thông tin cơ bản -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white pt-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Mã khách hàng</label>
                            <input type="text" class="form-control bg-light" value="{{ $customer->ma_kh }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tên đăng nhập</label>
                            <input type="text" class="form-control bg-light" value="{{ $customer->ten_dang_nhap }}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-medium">Tên cơ sở / Tên khách hàng <span class="text-danger">*</span></label>
                            <input type="text" name="ten_kh" class="form-control @error('ten_kh') is-invalid @enderror"
                                   value="{{ old('ten_kh', $customer->ten_kh) }}" required>
                            @error('ten_kh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Loại khách hàng <span class="text-danger">*</span></label>
                            <select name="loai_kh" class="form-select @error('loai_kh') is-invalid @enderror" required>
                                <option value="nha_thuoc" {{ old('loai_kh', $customer->loai_kh) == 'nha_thuoc' ? 'selected' : '' }}>Nhà thuốc</option>
                                <option value="dai_ly" {{ old('loai_kh', $customer->loai_kh) == 'dai_ly' ? 'selected' : '' }}>Đại lý</option>
                                <option value="phong_kham" {{ old('loai_kh', $customer->loai_kh) == 'phong_kham' ? 'selected' : '' }}>Phòng khám</option>
                                <option value="benh_vien" {{ old('loai_kh', $customer->loai_kh) == 'benh_vien' ? 'selected' : '' }}>Bệnh viện</option>
                            </select>
                            @error('loai_kh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" name="dia_chi" class="form-control @error('dia_chi') is-invalid @enderror"
                                   value="{{ old('dia_chi', $customer->dia_chi) }}" required>
                            @error('dia_chi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Số điện thoại</label>
                            <input type="text" name="dien_thoai" class="form-control @error('dien_thoai') is-invalid @enderror"
                                   value="{{ old('dien_thoai', $customer->dien_thoai) }}">
                            @error('dien_thoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Mã số thuế</label>
                            <input type="text" name="ma_so_thue" class="form-control"
                                   value="{{ old('ma_so_thue', $customer->ma_so_thue) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ghi chú</label>
                            <input type="text" name="ghi_chu" class="form-control"
                                   value="{{ old('ghi_chu', $customer->ghi_chu) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh & Giấy phép -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white pt-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0"><i class="bi bi-image me-2"></i>Hình ảnh & Giấy tờ</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ảnh đại diện</label>
                            @if($customer->hinh_dai_dien)
                                <div class="mb-2">
                                    <img src="{{ asset($customer->hinh_dai_dien) }}" class="img-thumbnail" height="80" alt="Avatar hiện tại">
                                    <div class="text-muted small mt-1">Ảnh hiện tại</div>
                                </div>
                            @endif
                            <input type="file" name="hinh_dai_dien" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Giấy phép hoạt động</label>
                            @if($customer->giay_phep_hd_image)
                                <div class="mb-2">
                                    <a href="{{ asset($customer->giay_phep_hd_image) }}" target="_blank">
                                        <img src="{{ asset($customer->giay_phep_hd_image) }}" class="img-thumbnail" height="80" alt="Giấy phép hiện tại">
                                    </a>
                                    <div class="text-muted small mt-1">Giấy phép hiện tại (nhấp để xem)</div>
                                </div>
                            @endif
                            <input type="file" name="giay_phep_hd_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đổi mật khẩu -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white pt-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0"><i class="bi bi-lock me-2"></i>Đổi mật khẩu <span class="text-muted fw-normal small">(bỏ trống nếu không muốn đổi)</span></h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Mật khẩu hiện tại</label>
                            <input type="password" name="mat_khau_cu" class="form-control @error('mat_khau_cu') is-invalid @enderror"
                                   placeholder="Nhập mật khẩu cũ">
                            @error('mat_khau_cu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Mật khẩu mới</label>
                            <input type="password" name="mat_khau_moi" class="form-control @error('mat_khau_moi') is-invalid @enderror"
                                   placeholder="Tối thiểu 6 ký tự">
                            @error('mat_khau_moi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Xác nhận mật khẩu mới</label>
                            <input type="password" name="mat_khau_moi_confirmation" class="form-control"
                                   placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-save me-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
