@extends('layouts.wholesale')

@section('title', 'Danh sách sản phẩm mua sỉ - PharmaDistrib')

@section('content')
<div class="row g-4">
    <!-- Sidebar Filters -->
    <div class="col-lg-3 d-none d-lg-block">
        <div class="filter-sidebar sticky-top" style="top: 80px;">
            <div class="mb-4">
                <h6 class="filter-title">Danh mục sản phẩm</h6>
                <div class="d-flex flex-column gap-1">
                    <a href="{{ route('wholesale.catalog') }}" class="text-decoration-none small {{ !request('nhom') ? 'fw-bold text-primary' : 'text-muted' }}">
                        <i class="bi bi-grid me-1"></i>Tất cả sản phẩm
                    </a>
                    @foreach($nhomThuocs as $nhom)
                        <a href="{{ route('wholesale.catalog', ['nhom' => $nhom->ma_nhom]) }}" class="text-decoration-none small {{ request('nhom') == $nhom->ma_nhom ? 'fw-bold text-primary' : 'text-muted' }}">
                            <i class="bi bi-chevron-right me-1"></i>{{ $nhom->ten_nhom }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-12 col-lg-9">
        <!-- Search & Sort Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 bg-white p-3 rounded-3 shadow-sm border" style="border-color: #e5e7eb !important;">
            <form method="GET" action="{{ route('wholesale.catalog') }}" class="flex-grow-1 position-relative" style="max-width: 500px;">
                <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" style="left: 15px;"></i>
                <input type="search" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-5" placeholder="Tìm theo tên thuốc, mã, hoạt chất..." style="border-radius: 8px;">
                @if(request('nhom'))<input type="hidden" name="nhom" value="{{ request('nhom') }}">@endif
            </form>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small text-nowrap">Sắp xếp:</span>
                <select class="form-select border-0 bg-light form-select-sm" style="border-radius: 6px; min-width: 150px;" onchange="window.location.href='{{ route('wholesale.catalog') }}?sort='+this.value+'&search={{ request('search') }}&nhom={{ request('nhom') }}'">
                    <option value="ten_thuoc" {{ request('sort', 'ten_thuoc') == 'ten_thuoc' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="gia_thap" {{ request('sort') == 'gia_thap' ? 'selected' : '' }}>Giá từ thấp đến cao</option>
                    <option value="gia_cao" {{ request('sort') == 'gia_cao' ? 'selected' : '' }}>Giá từ cao đến thấp</option>
                    <option value="moi_nhat" {{ request('sort') == 'moi_nhat' ? 'selected' : '' }}>Mới cập nhật</option>
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Product Grid -->
        <div class="row g-3 g-xl-4">
            @forelse($thuocs as $thuoc)
                <div class="col-6 col-md-4">
                    <div class="product-card h-100 d-flex flex-column">
                        <div class="product-img-wrapper" style="{{ $thuoc->ton_kho_hien_tai <= 0 ? 'opacity: 0.7;' : '' }}">
                            @if($thuoc->ton_kho_hien_tai <= 0)
                                <div class="product-badge"><span class="badge bg-secondary rounded-pill px-2">Hết hàng</span></div>
                            @elseif($thuoc->ton_kho_hien_tai <= 10)
                                <div class="product-badge"><span class="badge bg-warning text-dark rounded-pill px-2">Sắp hết</span></div>
                            @endif
                            <div class="placeholder-icon">
                                @if($thuoc->image1)
                                    <img src="{{ asset($thuoc->image1) }}" alt="{{ $thuoc->ten_thuoc }}" class="img-fluid" style="object-fit:contain;">
                                @else
                                    <i class="bi bi-capsule"></i>
                                @endif
                            </div>
                        </div>
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                                <span>Mã: {{ $thuoc->ma_thuoc }}</span>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-1">{{ $thuoc->donViTinh->ten_dvt ?? '' }}</span>
                            </div>
                            <h3 class="product-title">
                                <a href="{{ route('wholesale.product', $thuoc->ma_thuoc) }}" class="text-decoration-none {{ $thuoc->ton_kho_hien_tai <= 0 ? 'text-muted' : 'text-dark' }}">{{ $thuoc->ten_thuoc }}</a>
                            </h3>
                            @if($thuoc->nhomThuoc)
                                <div class="product-meta mb-2">
                                    <span><i class="bi bi-tag me-1"></i>{{ $thuoc->nhomThuoc->ten_nhom }}</span>
                                </div>
                            @endif

                            <div class="mt-auto pt-3 border-top position-relative">
                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <div>
                                        <div class="product-price">{{ number_format($thuoc->gia_ban_de_xuat ?? 0) }}đ</div>
                                    </div>
                                    <div class="text-end">
                                        @if($thuoc->ton_kho_hien_tai > 10)
                                            <div class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Còn hàng</div>
                                        @elseif($thuoc->ton_kho_hien_tai > 0)
                                            <div class="text-warning small"><i class="bi bi-exclamation-circle-fill me-1"></i>Còn {{ $thuoc->ton_kho_hien_tai }}</div>
                                        @else
                                            <div class="text-danger small"><i class="bi bi-x-circle-fill me-1"></i>Hết hàng</div>
                                        @endif
                                    </div>
                                </div>

                                @if($thuoc->ton_kho_hien_tai > 0)
                                    <form method="POST" action="{{ route('wholesale.cart.add') }}" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="ma_thuoc" value="{{ $thuoc->ma_thuoc }}">
                                        <input type="number" name="so_luong" class="qty-input" value="1" min="1" max="{{ $thuoc->ton_kho_hien_tai }}">
                                        <button type="submit" class="btn btn-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                            <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Chọn</span>
                                        </button>
                                    </form>
                                @else
                                    <div class="d-flex gap-2">
                                        <input type="number" class="qty-input bg-light text-muted" value="0" disabled>
                                        <button class="btn btn-secondary btn-add-cart w-100" disabled>
                                            <i class="bi bi-bell flex-shrink-0"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
                    <p class="text-muted small">Thử thay đổi từ khóa hoặc bộ lọc.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($thuocs->hasPages())
            <div class="d-flex justify-content-center mt-5 mb-4">
                {{ $thuocs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection