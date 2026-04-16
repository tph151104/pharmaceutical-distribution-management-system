@extends('layouts.app')

@section('title', 'Khách hàng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-people text-primary me-2"></i>Khách hàng</h1>
            <p class="text-muted small mb-0 mt-1">Quản lý tài khoản đại lý, nhà thuốc, bệnh viện và phòng khám</p>
        </div>
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

    <!-- Toolbar: Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('customers.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-12 col-md-4 position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" class="form-control ps-5" placeholder="Tên, mã KH, SĐT..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <select name="loai_kh" class="form-select">
                        <option value="">-- Tất cả phân loại --</option>
                        <option value="nha_thuoc" {{ request('loai_kh') == 'nha_thuoc' ? 'selected' : '' }}>Nhà thuốc</option>
                        <option value="dai_ly" {{ request('loai_kh') == 'dai_ly' ? 'selected' : '' }}>Đại lý</option>
                        <option value="phong_kham" {{ request('loai_kh') == 'phong_kham' ? 'selected' : '' }}>Phòng khám</option>
                        <option value="benh_vien" {{ request('loai_kh') == 'benh_vien' ? 'selected' : '' }}>Bệnh viện</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="trang_thai_tk" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="hoat_dong" {{ request('trang_thai_tk') == 'hoat_dong' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="cho_duyet" {{ request('trang_thai_tk') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt GPKD</option>
                        <option value="vo_hieu_hoa" {{ request('trang_thai_tk') == 'vo_hieu_hoa' ? 'selected' : '' }}>Bị khóa</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    @if(request()->has('search') || request()->has('loai_kh') || request()->has('trang_thai_tk'))
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
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
                            <th class="ps-4">Khách hàng</th>
                            <th>Phân loại</th>
                            <th>Liên hệ & MST</th>
                            <th>Tài khoản</th>
                            <th>Hồ sơ / Giấy phép</th>
                            @if(Auth::guard('admin')->user()->hasRole(1, 3))
                            <th class="text-end pe-4">Thao tác</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($khachHangs as $kh)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($kh->hinh_dai_dien)
                                            <img src="{{ asset($kh->hinh_dai_dien) }}" class="rounded-circle me-3 object-fit-cover shadow-sm" width="40" height="40">
                                        @else
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                                {{ substr($kh->ten_kh, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $kh->ten_kh }}</div>
                                            <div class="small text-muted">{{ $kh->ma_kh }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($kh->loai_kh == 'nha_thuoc')
                                        <span class="badge bg-info text-dark">Nhà Thuốc</span>
                                    @elseif($kh->loai_kh == 'dai_ly')
                                        <span class="badge bg-primary">Đại Lý</span>
                                    @elseif($kh->loai_kh == 'phong_kham')
                                        <span class="badge bg-success">Phòng Khám</span>
                                    @else
                                        <span class="badge bg-danger">Bệnh Viện</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small"><i class="bi bi-telephone text-muted me-1"></i> {{ $kh->dien_thoai ?? '--' }}</div>
                                    <div class="small"><i class="bi bi-receipt text-muted me-1"></i> MST: {{ $kh->ma_so_thue ?? '--' }}</div>
                                </td>
                                <td>
                                    <div class="small mb-1">UN: <span class="fw-bold">{{ $kh->ten_dang_nhap }}</span></div>
                                    @if($kh->trang_thai_tk == 'hoat_dong')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle me-1"></i>Hoạt động</span>
                                    @elseif($kh->trang_thai_tk == 'cho_duyet')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning"><i class="bi bi-hourglass-split me-1"></i>Chờ duyệt</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="bi bi-ban me-1"></i>Bị khóa</span>
                                    @endif
                                </td>
                                <td>
                                    @if($kh->giay_phep_hd_image)
                                        <a href="{{ asset($kh->giay_phep_hd_image) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-image"></i> Xem GPKD
                                        </a>
                                    @else
                                        <span class="text-muted small">Chưa cung cấp</span>
                                    @endif
                                </td>
                                @if(Auth::guard('admin')->user()->hasRole(1, 3))
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Tùy chọn
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li><h6 class="dropdown-header">Thay đổi trạng thái</h6></li>
                                            @if($kh->trang_thai_tk == 'cho_duyet' || $kh->trang_thai_tk == 'vo_hieu_hoa')
                                            <li>
                                                <form action="{{ route('customers.updateStatus', $kh->ma_kh) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="trang_thai_tk" value="hoat_dong">
                                                    <button type="submit" class="dropdown-item text-success"><i class="bi bi-check-circle me-2"></i>Duyệt tài khoản</button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($kh->trang_thai_tk == 'hoat_dong' || $kh->trang_thai_tk == 'cho_duyet')
                                            <li>
                                                <form action="{{ route('customers.updateStatus', $kh->ma_kh) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="trang_thai_tk" value="vo_hieu_hoa">
                                                    <button type="submit" class="dropdown-item text-warning"><i class="bi bi-lock me-2"></i>Khóa tài khoản</button>
                                                </form>
                                            </li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#editCustomerCanvas{{ $kh->ma_kh }}">
                                                    <i class="bi bi-eye me-2 text-primary"></i>Xem chi tiết
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('customers.destroy', $kh->ma_kh) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">
                                                        <i class="bi bi-trash me-2"></i>Xóa hồ sơ
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                @endif
                            </tr>

                            @if(Auth::guard('admin')->user()->hasRole(1, 3))
                            <!-- Edit Offcanvas for this loop -->
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="editCustomerCanvas{{ $kh->ma_kh }}" style="width: 500px;">
                                <div class="offcanvas-header border-bottom">
                                    <h5 class="offcanvas-title fw-bold text-primary">Chi tiết hồ sơ Khách hàng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <div class="customer-details">
                                        <h6 class="text-uppercase text-muted fw-bold small mb-3">Thông tin tài khoản</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-muted">Tên đăng nhập (Username)</label>
                                            <div class="form-control-plaintext border-bottom pb-2">{{ $kh->ten_dang_nhap }}</div>
                                        </div>

                                        <h6 class="text-uppercase text-muted fw-bold small mb-3 mt-4">Hồ sơ đối tác</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-muted">Tên khách hàng/Tổ chức</label>
                                            <div class="form-control-plaintext border-bottom pb-2 fw-semibold">{{ $kh->ten_kh }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-muted">Phân loại</label>
                                            <div class="form-control-plaintext border-bottom pb-2">
                                                @if($kh->loai_kh == 'nha_thuoc') Nhà Thuốc
                                                @elseif($kh->loai_kh == 'dai_ly') Đại Lý Phân Phối
                                                @elseif($kh->loai_kh == 'phong_kham') Phòng Khám
                                                @else Bệnh Viện @endif
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold text-muted">Điện thoại</label>
                                                <div class="form-control-plaintext border-bottom pb-2">{{ $kh->dien_thoai ?? '--' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold text-muted">Mã số thuế</label>
                                                <div class="form-control-plaintext border-bottom pb-2">{{ $kh->ma_so_thue ?? '--' }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-muted">Email</label>
                                            <div class="form-control-plaintext border-bottom pb-2">{{ $kh->email ?? '--' }}</div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold text-muted">Địa chỉ kinh doanh</label>
                                            <div class="form-control-plaintext border-bottom pb-2">{{ $kh->dia_chi }}</div>
                                        </div>

                                        <h6 class="text-uppercase text-muted fw-bold small mb-3 mt-4">Tài liệu đính kèm</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-muted">Ảnh đại diện/Logo</label>
                                            <div class="mt-2">
                                                @if($kh->hinh_dai_dien)
                                                    <img src="{{ asset($kh->hinh_dai_dien) }}" class="rounded shadow-sm border" width="100">
                                                @else
                                                    <span class="text-muted small">Chưa cung cấp</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold text-muted">Giấy phép hoạt động/GPKD</label>
                                            <div class="mt-2">
                                                @if($kh->giay_phep_hd_image)
                                                    <a href="{{ asset($kh->giay_phep_hd_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-file-earmark-image me-1"></i> Xem trực tiếp
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Chưa cung cấp</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="button" class="btn btn-secondary py-2 fw-bold" data-bs-dismiss="offcanvas">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people display-4 d-block mb-3 text-secondary opacity-50"></i>
                                    Chưa có dữ liệu khách hàng.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($khachHangs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $khachHangs->links() }}
            </div>
        @endif
    </div>

@endsection
