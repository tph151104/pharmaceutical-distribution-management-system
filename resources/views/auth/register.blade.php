@extends('layouts.auth')

@section('title', 'Đăng ký tài khoản Đối tác')

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

    <h5 class="fw-semibold mb-1">Mở tài khoản Đại lý / Cơ sở Y tế</h5>
    <p class="text-muted small mb-4">
        Vui lòng điền đầy đủ và trung thực các thông tin dưới đây. Tài khoản cần được phê duyệt trước khi mua hàng.
    </p>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        
        <div class="col-12 mt-1">
            <h6 class="small fw-bold text-primary border-bottom pb-1 mb-2">Thông tin Đăng nhập</h6>
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Tên đăng nhập (Username) <span class="text-danger">*</span></label>
            <input type="text" name="ten_dang_nhap" class="form-control form-control-sm" value="{{ old('ten_dang_nhap') }}" required placeholder="VD: nhathuoc_abc">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-muted mb-1">Mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="mat_khau" class="form-control form-control-sm" required placeholder="Tối thiểu 6 ký tự">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-muted mb-1">Xác nhận mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="mat_khau_confirmation" class="form-control form-control-sm" required placeholder="Nhập lại mật khẩu">
        </div>

        <div class="col-12 mt-3">
            <h6 class="small fw-bold text-primary border-bottom pb-1 mb-2">Thông tin Cơ sở / Khách hàng</h6>
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Tên Cơ sở / Tổ chức <span class="text-danger">*</span></label>
            <input type="text" name="ten_kh" class="form-control form-control-sm" value="{{ old('ten_kh') }}" required placeholder="VD: Nhà thuốc Pharmacy XYZ">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-muted mb-1">Loại hình kinh doanh <span class="text-danger">*</span></label>
            <select name="loai_kh" class="form-select form-select-sm" required>
                <option value="nha_thuoc" {{ old('loai_kh') == 'nha_thuoc' ? 'selected' : '' }}>Nhà Thuốc</option>
                <option value="dai_ly" {{ old('loai_kh') == 'dai_ly' ? 'selected' : '' }}>Đại Lý Phân Phối</option>
                <option value="phong_kham" {{ old('loai_kh') == 'phong_kham' ? 'selected' : '' }}>Phòng Khám</option>
                <option value="benh_vien" {{ old('loai_kh') == 'benh_vien' ? 'selected' : '' }}>Bệnh Viện</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label small text-muted mb-1">Mã số thuế <span class="text-danger">*</span></label>
            <input type="text" name="ma_so_thue" class="form-control form-control-sm" value="{{ old('ma_so_thue') }}" required placeholder="VD: 0312345678">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-muted mb-1">Số điện thoại liên hệ <span class="text-danger">*</span></label>
            <input type="text" name="dien_thoai" class="form-control form-control-sm" value="{{ old('dien_thoai') }}" required placeholder="VD: 0901234567">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-muted mb-1">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}" required placeholder="contact@example.com">
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Địa chỉ Đăng ký Kinh doanh/Hoạt động <span class="text-danger">*</span></label>
            <input type="text" name="dia_chi" class="form-control form-control-sm" value="{{ old('dia_chi') }}" required placeholder="Số nhà, Tên đường, Phường/Xã, Quận/Huyện, Tỉnh/TP">
        </div>

        <div class="col-12 mt-3">
            <h6 class="small fw-bold text-primary border-bottom pb-1 mb-2">Tài liệu Kiểm duyệt</h6>
        </div>        
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Ảnh chụp Giấy Phép HĐ / GPKD <span class="text-danger">*</span></label>
            <input type="file" name="giay_phep_hd_image" class="form-control form-control-sm" accept="image/*,.pdf" required>
            <div class="form-text small" style="font-size: 0.75rem;">File ảnh hoặc PDF. Bắt buộc để xác minh tư cách pháp nhân bán buôn dược phẩm.</div>
        </div>
        <div class="col-12">
            <label class="form-label small text-muted mb-1">Logo / Ảnh đại diện cơ sở (Tùy chọn)</label>
            <input type="file" name="hinh_dai_dien" class="form-control form-control-sm" accept="image/*">
        </div>

        <div class="col-12 mt-4">
            <div class="form-check form-check-sm">
                <input class="form-check-input" type="checkbox" name="terms" id="termsCheck" required>
                <label class="form-check-label small" for="termsCheck">
                    Tôi cam kết cung cấp thông tin trung thực và đồng ý với <a href="#" class="text-decoration-none">Điều khoản mua sỉ</a>.
                </label>
            </div>
        </div>
        <div class="col-12 d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary btn-sm py-2 fw-semibold">
                <i class="bi bi-person-plus me-1"></i>
                Hết tất Đăng ký Mở tài khoản
            </button>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm py-2">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Đã có tài khoản được duyệt? Đăng nhập ngay
            </a>
        </div>
    </form>
@endsection
