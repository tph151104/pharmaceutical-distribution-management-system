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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
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

    <!-- ========== TABS ========== -->
    <ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-products" data-bs-toggle="tab" data-bs-target="#pane-products" type="button" role="tab">
                <i class="bi bi-capsule me-1"></i> Sản phẩm <span class="badge bg-primary ms-1">{{ $thuocs->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-categories" data-bs-toggle="tab" data-bs-target="#pane-categories" type="button" role="tab">
                <i class="bi bi-tags me-1"></i> Nhóm sản phẩm <span class="badge bg-secondary ms-1">{{ $nhom_thuocs->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-units" data-bs-toggle="tab" data-bs-target="#pane-units" type="button" role="tab">
                <i class="bi bi-box me-1"></i> Đơn vị tính <span class="badge bg-secondary ms-1">{{ $don_vi_tinhs->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="productTabsContent">

        <!-- ==================== TAB 1: SẢN PHẨM ==================== -->
        <div class="tab-pane fade show active" id="pane-products" role="tabpanel">
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
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Tìm
                            </button>
                            @if(request()->has('search') || request()->has('nhom_thuoc'))
                                <a href="{{ route('products.index') }}" class="btn btn-light btn-sm mt-1">Xóa lọc</a>
                            @endif
                        </div>
                        <div class="col-12 col-md-3 d-grid">
                            @if(Auth::guard('admin')->user()->hasRole(1, 5))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal">
                                <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm
                            </button>
                            @endif
                        </div> 
                                         
                    </form>
                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                    <div class="col-12 col-md-4 d-grid">
                            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-label small text-muted mb-1">Import Excel</label>
                                <div class="input-group">
                                    <input type="file" name="file_excel" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Import
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-semibold small text-uppercase text-muted">Danh sách thuốc / sản phẩm</div>
                    <div class="small text-muted">Tổng: <span class="fw-semibold">{{ $thuocs->total() }}</span> sản phẩm</div>
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
                                @if(Auth::guard('admin')->user()->hasRole(1, 5))
                                <th class="text-nowrap text-center">Thao tác</th>
                                @endif
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
                                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
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
                                    @endif
                                </tr>

                                @if(Auth::guard('admin')->user()->hasRole(1, 5))
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
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted small py-4">
                                        Hiện chưa có dữ liệu. Khi thêm sản phẩm, danh sách sẽ hiển thị tại đây.
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
        </div>

        <!-- ==================== TAB 2: NHÓM SẢN PHẨM ==================== -->
        <div class="tab-pane fade" id="pane-categories" role="tabpanel">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-semibold small text-uppercase text-muted">Danh sách nhóm sản phẩm</div>
                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddCategory">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Nhóm
                    </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th class="ps-4">Mã Nhóm</th>
                                    <th>Tên Nhóm</th>
                                    <th>Ghi chú</th>
                                    <th class="text-center">Số Sản Phẩm</th>
                                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                                    <th class="text-end pe-4">Thao Tác</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nhom_thuocs as $category)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">{{ $category->ma_nhom }}</td>
                                    <td>{{ $category->ten_nhom }}</td>
                                    <td>{{ $category->ghi_chu ?: '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">{{ $category->cac_thuoc_count }}</span>
                                    </td>
                                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1" 
                                                onclick="editCategory('{{ $category->ma_nhom }}', '{{ addslashes($category->ten_nhom) }}', '{{ addslashes($category->ghi_chu) }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('products.categories.destroy', $category->ma_nhom) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá nhóm «{{ $category->ten_nhom }}» không?\n\nLưu ý: Nếu nhóm này đang có sản phẩm sử dụng thì không thể xoá.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">Chưa có nhóm thuốc nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB 3: ĐƠN VỊ TÍNH ==================== -->
        <div class="tab-pane fade" id="pane-units" role="tabpanel">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-semibold small text-uppercase text-muted">Danh sách đơn vị tính</div>
                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddUnit">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Đơn Vị
                    </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th class="ps-4">Mã ĐVT</th>
                                    <th>Tên Đơn Vị</th>
                                    <th>Ghi chú</th>
                                    <th class="text-center">Số Sản Phẩm</th>
                                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                                    <th class="text-end pe-4">Thao Tác</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($don_vi_tinhs as $unit)
                                <tr>
                                    <td class="ps-4 fw-bold text-success">{{ $unit->ma_dvt }}</td>
                                    <td>{{ $unit->ten_dvt }}</td>
                                    <td>{{ $unit->ghi_chu ?: '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white">{{ $unit->cac_thuoc_count }}</span>
                                    </td>
                                    @if(Auth::guard('admin')->user()->hasRole(1, 5))
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1" 
                                                onclick="editUnit('{{ $unit->ma_dvt }}', '{{ addslashes($unit->ten_dvt) }}', '{{ addslashes($unit->ghi_chu) }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('products.units.destroy', $unit->ma_dvt) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá đơn vị «{{ $unit->ten_dvt }}» không?\n\nLưu ý: Nếu đơn vị này đang có sản phẩm sử dụng thì không thể xoá.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">Chưa có đơn vị tính nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end tab-content -->

    @if(Auth::guard('admin')->user()->hasRole(1, 5))
    <!-- ==================== MODALS ==================== -->

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
                            <label class="form-label small fw-semibold mb-1">Mã sản phẩm</label>
                            <input type="text" class="form-control bg-light" value="Hệ thống tự sinh (TH...)" readonly disabled>
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

    <!-- Modal Thêm Nhóm -->
    <div class="modal fade" id="modalAddCategory" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('products.categories.store') }}" method="POST" onsubmit="return confirm('Xác nhận thêm nhóm mới?')">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Nhóm Thuốc Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã Nhóm</label>
                            <input type="text" class="form-control bg-light" value="Hệ thống tự sinh (NT...)" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Nhóm <span class="text-danger">*</span></label>
                            <input type="text" name="ten_nhom" class="form-control" required placeholder="Ví dụ: Thuốc kháng sinh">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu Nhóm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa Nhóm -->
    <div class="modal fade" id="modalEditCategory" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditCategory" method="POST" onsubmit="return confirm('Xác nhận cập nhật thông tin nhóm?')">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Nhóm Thuốc</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã Nhóm</label>
                            <input type="text" id="edit_ma_nhom" class="form-control bg-light" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Nhóm <span class="text-danger">*</span></label>
                            <input type="text" name="ten_nhom" id="edit_ten_nhom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" id="edit_ghi_chu" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Thêm Đơn Vị -->
    <div class="modal fade" id="modalAddUnit" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('products.units.store') }}" method="POST" onsubmit="return confirm('Xác nhận thêm đơn vị mới?')">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Đơn Vị Tính Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <!-- <label class="form-label fw-bold">Mã ĐVT <span class="text-danger">*</span></label> -->
                            <input type="text" class="form-control bg-light" value="Hệ thống tự sinh (DVT...)" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Đơn Vị <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dvt" class="form-control" required placeholder="Ví dụ: Viên">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu Đơn Vị</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa Đơn Vị -->
    <div class="modal fade" id="modalEditUnit" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditUnit" method="POST" onsubmit="return confirm('Xác nhận cập nhật thông tin đơn vị?')">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Đơn Vị Tính</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã ĐVT</label>
                            <input type="text" id="edit_ma_dvt" class="form-control bg-light" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Đơn Vị <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dvt" id="edit_ten_dvt" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" id="edit_ghi_chu_unit" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

@push('scripts')
<script>
    function editCategory(id, name, desc) {
        document.getElementById('edit_ma_nhom').value = id;
        document.getElementById('edit_ten_nhom').value = name;
        document.getElementById('edit_ghi_chu').value = desc;
        document.getElementById('formEditCategory').action = "{{ url('products/categories') }}/" + id;
        new bootstrap.Modal(document.getElementById('modalEditCategory')).show();
    }

    function editUnit(id, name, desc) {
        document.getElementById('edit_ma_dvt').value = id;
        document.getElementById('edit_ten_dvt').value = name;
        document.getElementById('edit_ghi_chu_unit').value = desc;
        document.getElementById('formEditUnit').action = "{{ url('products/units') }}/" + id;
        new bootstrap.Modal(document.getElementById('modalEditUnit')).show();
    }
</script>
@endpush
@endsection
