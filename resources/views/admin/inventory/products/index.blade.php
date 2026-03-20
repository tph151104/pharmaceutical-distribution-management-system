@extends('layouts.app')

@section('title', 'Danh mục thuốc / sản phẩm')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Danh mục thuốc / sản phẩm</h1>
            </div>
            <div class="text-muted small">
                Quản lý danh sách thuốc và sản phẩm kinh doanh: mã, tên, hàm lượng, đơn vị, nhóm, trạng thái.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm sản phẩm
            </button>
        </div>
    </div>
@endsection

@section('content')
    <!-- Hiển thị thông báo thành công hoặc lỗi -->
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

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted mb-1">Từ khóa</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Mã, tên thuốc..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Nhóm sản phẩm</label>
                    <select name="nhom_thuoc" class="form-select form-select-sm">
                        <option value="">Tất cả nhóm</option>
                        @foreach($nhom_thuocs as $nhom)
                            <option value="{{ $nhom->ma_nhom }}" {{ request('nhom_thuoc') == $nhom->ma_nhom ? 'selected' : '' }}>
                                {{ $nhom->ten_nhom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Có thể thêm lọc theo đơn vị tính ở đây -->
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i>
                        Tìm
                    </button>
                    @if(request()->has('search') || request()->has('nhom_thuoc'))
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-sm mt-1">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Danh sách thuốc / sản phẩm
            </div>
            <div class="small text-muted">
                Tổng: <span class="fw-semibold">{{ $thuocs->total() }}</span> sản phẩm
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-center" width="60">Ảnh</th>
                        <th class="text-nowrap">Mã SP</th>
                        <th class="text-nowrap">Tên sản phẩm</th>
                        <th class="text-nowrap">Hoạt chất / Hàm lượng</th>
                        <th class="text-nowrap">Đơn vị</th>
                        <th class="text-nowrap">Nhóm</th>
                        <th class="text-nowrap text-end">Giá bán tham khảo</th>
                        <th class="text-nowrap text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($thuocs as $thuoc)
                        <tr>
                            <td class="text-center">
                                @if($thuoc->image1)
                                    <img src="{{ asset($thuoc->image1) }}" alt="{{ $thuoc->ten_thuoc }}" class="img-thumbnail p-0" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center border rounded" style="width: 40px; height: 40px; font-size: 10px;">No img</div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $thuoc->ma_thuoc }}</td>
                            <td>{{ $thuoc->ten_thuoc }}</td>
                            <td>{{ $thuoc->thanh_phan }}<br><small class="text-muted">{{ $thuoc->ham_luong }}</small></td>
                            <td>{{ $thuoc->donViTinh->ten_dvt ?? $thuoc->ma_dvt }}</td>
                            <td>{{ $thuoc->nhomThuoc->ten_nhom ?? $thuoc->ma_nhom }}</td>
                            <td class="text-end fw-semibold text-primary">{{ number_format($thuoc->gia_ban_de_xuat, 0, ',', '.') }}đ</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-light" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal{{ $thuoc->ma_thuoc }}" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('products.destroy', $thuoc->ma_thuoc) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal sửa sản phẩm -->
                        <div class="modal fade text-start" id="editModal{{ $thuoc->ma_thuoc }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa thông tin sản phẩm: {{ $thuoc->ma_thuoc }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('products.update', $thuoc->ma_thuoc) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-md-12">
                                                    <label class="form-label small fw-semibold mb-1">Tên sản phẩm *</label>
                                                    <input type="text" name="ten_thuoc" class="form-control" value="{{ $thuoc->ten_thuoc }}" required>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small fw-semibold mb-1">Nhóm sản phẩm *</label>
                                                    <select name="ma_nhom" class="form-select" required>
                                                        @foreach($nhom_thuocs as $nhom)
                                                            <option value="{{ $nhom->ma_nhom }}" {{ $thuoc->ma_nhom == $nhom->ma_nhom ? 'selected' : '' }}>{{ $nhom->ten_nhom }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small fw-semibold mb-1">Đơn vị tính *</label>
                                                    <select name="ma_dvt" class="form-select" required>
                                                        @foreach($don_vi_tinhs as $dvt)
                                                            <option value="{{ $dvt->ma_dvt }}" {{ $thuoc->ma_dvt == $dvt->ma_dvt ? 'selected' : '' }}>{{ $dvt->ten_dvt }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label small mb-1">Nguồn gốc</label>
                                                    <input type="text" name="nguon_goc" class="form-control" value="{{ $thuoc->nguon_goc }}">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label small mb-1">Giá bán đề xuất</label>
                                                    <input type="number" name="gia_ban_de_xuat" class="form-control" value="{{ $thuoc->gia_ban_de_xuat }}">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label small mb-1">Dạng bào chế</label>
                                                    <input type="text" name="dang_bao_che" class="form-control" value="{{ $thuoc->dang_bao_che }}">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small mb-1">Thành phần</label>
                                                    <textarea name="thanh_phan" class="form-control" rows="2">{{ $thuoc->thanh_phan }}</textarea>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small mb-1">Hàm lượng</label>
                                                    <input type="text" name="ham_luong" class="form-control" value="{{ $thuoc->ham_luong }}">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small mb-1">Công dụng</label>
                                                    <textarea name="cong_dung" class="form-control" rows="2">{{ $thuoc->cong_dung }}</textarea>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small mb-1">Cách dùng</label>
                                                    <textarea name="cach_dung" class="form-control" rows="2">{{ $thuoc->cach_dung }}</textarea>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small mb-1">Bảo quản</label>
                                                    <textarea name="bao_quan" class="form-control" rows="2">{{ $thuoc->bao_quan }}</textarea>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small mb-1">Chống chỉ định</label>
                                                    <textarea name="chong_chi_dinh" class="form-control" rows="2">{{ $thuoc->chong_chi_dinh }}</textarea>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <h6 class="border-bottom pb-2 mt-3 mb-3">Hình ảnh sản phẩm</h6>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label small mb-1">Ảnh 1</label>
                                                    @if($thuoc->image1)
                                                        <div class="mb-2"><img src="{{ asset($thuoc->image1) }}" height="60" class="rounded border"></div>
                                                    @endif
                                                    <input type="file" name="image1" class="form-control form-control-sm" accept="image/*">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label small mb-1">Ảnh 2</label>
                                                    @if($thuoc->image2)
                                                        <div class="mb-2"><img src="{{ asset($thuoc->image2) }}" height="60" class="rounded border"></div>
                                                    @endif
                                                    <input type="file" name="image2" class="form-control form-control-sm" accept="image/*">
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label small mb-1">Ảnh 3</label>
                                                    @if($thuoc->image3)
                                                        <div class="mb-2"><img src="{{ asset($thuoc->image3) }}" height="60" class="rounded border"></div>
                                                    @endif
                                                    <input type="file" name="image3" class="form-control form-control-sm" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted small py-4">
                                Hhiện chưa có dữ liệu. Khi thêm sản phẩm, danh sách sẽ hiển thị tại đây.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($thuocs->hasPages())
                <div class="px-3 pt-3">
                    {{ $thuocs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal thêm sản phẩm -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold mb-1">Mã sản phẩm *</label>
                            <input type="text" name="ma_thuoc" class="form-control" placeholder="VD: PARA500" required>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-semibold mb-1">Tên sản phẩm *</label>
                            <input type="text" name="ten_thuoc" class="form-control" placeholder="VD: Paracetamol 500mg" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold mb-1">Nhóm sản phẩm *</label>
                            <select name="ma_nhom" class="form-select" required>
                                <option value="">-- Chọn nhóm --</option>
                                @foreach($nhom_thuocs as $nhom)
                                    <option value="{{ $nhom->ma_nhom }}">{{ $nhom->ten_nhom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold mb-1">Đơn vị tính *</label>
                            <select name="ma_dvt" class="form-select" required>
                                <option value="">-- Chọn ĐVT --</option>
                                @foreach($don_vi_tinhs as $dvt)
                                    <option value="{{ $dvt->ma_dvt }}">{{ $dvt->ten_dvt }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label class="form-label small mb-1">Nguồn gốc</label>
                            <input type="text" name="nguon_goc" class="form-control" placeholder="VD: Việt Nam">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small mb-1">Giá bán đề xuất</label>
                            <input type="number" name="gia_ban_de_xuat" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small mb-1">Dạng bào chế</label>
                            <input type="text" name="dang_bao_che" class="form-control" placeholder="VD: Viên nén">
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label class="form-label small mb-1">Thành phần</label>
                            <textarea name="thanh_phan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small mb-1">Hàm lượng</label>
                            <input type="text" name="ham_luong" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small mb-1">Công dụng</label>
                            <textarea name="cong_dung" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small mb-1">Cách dùng</label>
                            <textarea name="cach_dung" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small mb-1">Bảo quản</label>
                            <textarea name="bao_quan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small mb-1">Chống chỉ định</label>
                            <textarea name="chong_chi_dinh" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mt-3 mb-3">Hình ảnh sản phẩm</h6>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small mb-1">Ảnh 1</label>
                            <input type="file" name="image1" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small mb-1">Ảnh 2</label>
                            <input type="file" name="image2" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small mb-1">Ảnh 3</label>
                            <input type="file" name="image3" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

