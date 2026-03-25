@extends('layouts.wholesale')

@section('title', $thuoc->ten_thuoc . ' - PharmaDistrib')

@push('styles')
<style>
    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }
    .breadcrumb-item a:hover {
        color: #0d6efd;
    }
    .product-gallery {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background-color: #fff;
        position: relative;
    }
    .main-image-placeholder {
        width: 100%;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        position: relative;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .main-image-placeholder img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }
    .main-image-placeholder i {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 8rem;
        color: #dee2e6;
    }
    .thumbnail-gallery {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .thumbnail-item {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #adb5bd;
        font-size: 2rem;
        transition: all 0.2s;
        overflow: hidden;
    }
    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .thumbnail-item:hover, .thumbnail-item.active {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    .product-title-large {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .product-sku {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .product-brand {
        font-size: 1rem;
        color: #495057;
    }
    .price-block {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1.25rem;
        margin: 1.5rem 0;
        border: 1px solid #e5e7eb;
    }
    .price-main {
        font-size: 2rem;
        font-weight: 700;
        color: #0d6efd;
    }
    .qty-control {
        display: flex;
        align-items: center;
        width: 140px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        overflow: hidden;
    }
    .qty-btn {
        background: #f8f9fa;
        border: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        color: #495057;
    }
    .qty-btn:hover {
        background: #e9ecef;
    }
    .qty-input-large {
        width: 60px;
        height: 40px;
        border: none;
        border-left: 1px solid #ced4da;
        border-right: 1px solid #ced4da;
        text-align: center;
        font-weight: 600;
    }
    .qty-input-large:focus {
        outline: none;
    }
    .btn-buy-now {
        height: 48px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 8px;
    }
    .btn-add-cart-large {
        height: 48px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 8px;
    }
    .product-info-tabs .nav-link {
        color: #495057;
        font-weight: 500;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 1rem 1.5rem;
        margin-bottom: -1px;
    }
    .product-info-tabs .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 2px solid #0d6efd;
    }
    .product-info-tabs .nav-link:hover:not(.active) {
        border-bottom: 2px solid #dee2e6;
    }
    .tab-content-container {
        background: #fff;
        border-radius: 0 0 12px 12px;
        border: 1px solid #e5e7eb;
        border-top: none;
        padding: 2rem;
        min-height: 300px;
        line-height: 1.8;
    }
    .info-list {
        list-style-type: none;
        padding-left: 0;
    }
    .info-list li {
        position: relative;
        padding-left: 20px;
        margin-bottom: 10px;
    }
    .info-list li::before {
        content: "\F287"; /* Bootstrap icon check */
        font-family: "bootstrap-icons";
        position: absolute;
        left: 0;
        color: #198754;
    }
    .feature-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .feature-icon {
        font-size: 1.5rem;
        color: #0d6efd;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4 mt-2">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('wholesale.catalog') }}"><i class="bi bi-house-door"></i> Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('wholesale.catalog') }}">
            @if(isset($thuoc->nhomThuoc->ten_nhom))
                {{ $thuoc->nhomThuoc->ten_nhom }}
            @else
                Tất cả sản phẩm
            @endif
        </a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $thuoc->ten_thuoc }}</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Product Gallery -->
    <div class="col-lg-5">
        <div class="sticky-top" style="top: 80px; z-index: 1;">
            <div class="product-gallery">
                @if($thuoc->ton_kho_hien_tai <= 0)
                    <span class="badge bg-secondary position-absolute top-0 start-0 m-3 z-3 fs-6 rounded-pill px-3 py-2">Hết hàng</span>
                @elseif($thuoc->ton_kho_hien_tai <= 10)
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 z-3 fs-6 rounded-pill px-3 py-2">Sắp hết</span>
                @endif
                <div class="main-image-placeholder" id="mainImageContainer">
                    @if($thuoc->image1)
                        <img src="{{ asset('storage/' . $thuoc->image1) }}" alt="{{ $thuoc->ten_thuoc }}" id="mainImage">
                    @elseif($thuoc->image2)
                        <img src="{{ asset('storage/' . $thuoc->image2) }}" alt="{{ $thuoc->ten_thuoc }}" id="mainImage">
                    @elseif($thuoc->image3)
                        <img src="{{ asset('storage/' . $thuoc->image3) }}" alt="{{ $thuoc->ten_thuoc }}" id="mainImage">
                    @else
                        <i class="bi bi-capsule"></i>
                    @endif
                </div>
            </div>
            
            <div class="thumbnail-gallery">
                @if($thuoc->image1)
                <div class="thumbnail-item active" onclick="changeImage('{{ asset('storage/' . $thuoc->image1) }}', this)">
                    <img src="{{ asset('storage/' . $thuoc->image1) }}" alt="Thumnail">
                </div>
                @endif
                @if($thuoc->image2)
                <div class="thumbnail-item" onclick="changeImage('{{ asset('storage/' . $thuoc->image2) }}', this)">
                    <img src="{{ asset('storage/' . $thuoc->image2) }}" alt="Thumnail">
                </div>
                @endif
                @if($thuoc->image3)
                <div class="thumbnail-item" onclick="changeImage('{{ asset('storage/' . $thuoc->image3) }}', this)">
                    <img src="{{ asset('storage/' . $thuoc->image3) }}" alt="Thumnail">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Summary Info -->
    <div class="col-lg-7">
        <div class="ps-lg-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                @if($thuoc->nhomThuoc)
                <span class="badge bg-primary-subtle text-primary-emphasis px-2 py-1 rounded-1 fw-medium">{{ $thuoc->nhomThuoc->ten_nhom }}</span>
                @endif
                <span class="product-sku ms-2">Mã SP: <strong>{{ $thuoc->ma_thuoc }}</strong></span>
            </div>
            
            <h1 class="product-title-large">{{ $thuoc->ten_thuoc }}</h1>
            
            <div class="d-flex align-items-center gap-4 mb-2 mt-3 pb-3 border-bottom">
                <div class="product-brand"><i class="bi bi-building me-1 text-muted"></i> Nguồn gốc: <span class="fw-medium text-dark">{{ $thuoc->nguon_goc ?? 'Đang cập nhật' }}</span></div>
            </div>

            <div class="price-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted mb-1 small">Giá bán sỉ (đã bao gồm VAT)</div>
                        <div class="d-flex align-items-baseline">
                            <span class="price-main">{{ number_format($thuoc->gia_ban_de_xuat ?? 0) }}₫</span>
                            <span class="text-muted ms-2">/ {{ $thuoc->donViTinh->ten_dvt ?? 'Sản phẩm' }}</span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3 opacity-25">
                
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            @if($thuoc->ton_kho_hien_tai > 0)
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <div>
                                    <div class="fw-medium">Tình trạng: Còn hàng</div>
                                    <div class="small text-muted">Sẵn sàng giao ({{ $thuoc->ton_kho_hien_tai }} sản phẩm)</div>
                                </div>
                            @else
                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                <div>
                                    <div class="fw-medium text-danger">Tình trạng: Hết hàng</div>
                                    <div class="small text-muted">Vui lòng quay lại sau</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam-fill text-primary fs-5"></i>
                            <div>
                                <div class="fw-medium">Đơn vị tính</div>
                                <div class="small text-muted">{{ $thuoc->donViTinh->ten_dvt ?? 'Đang cập nhật' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($thuoc->ton_kho_hien_tai > 0)
            <form method="POST" action="{{ route('wholesale.cart.add') }}">
                @csrf
                <input type="hidden" name="ma_thuoc" value="{{ $thuoc->ma_thuoc }}">
                <div class="d-flex flex-wrap gap-3 align-items-center mb-4 mt-4">
                    <div class="qty-control">
                        <button class="qty-btn" type="button" onclick="const input = document.getElementById('qty'); input.value = Math.max(1, parseInt(input.value) - 1);"><i class="bi bi-dash"></i></button>
                        <input type="number" id="qty" name="so_luong" class="qty-input-large" value="1" min="1" max="{{ $thuoc->ton_kho_hien_tai }}">
                        <button class="qty-btn" type="button" onclick="const input = document.getElementById('qty'); let maxVal = parseInt(input.max); let curVal = parseInt(input.value); if(curVal < maxVal) input.value = curVal + 1;"><i class="bi bi-plus"></i></button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-add-cart-large px-4 d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
                        <i class="bi bi-cart-plus fs-5"></i> Thêm vào giỏ
                    </button>
                </div>
            </form>
            @endif

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="feature-box border">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.95rem;">Hàng chính hãng 100%</div>
                            <div class="small text-muted">Đầy đủ hóa đơn đỏ, giấy tờ CL</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-box border">
                        <i class="bi bi-truck feature-icon text-success"></i>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.95rem;">Giao hàng siêu tốc</div>
                            <div class="small text-muted">Miễn phí giao hàng đơn từ 500K</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Tabs -->
<div class="row mb-5">
    <div class="col-12">
        <div class="border rounded-top-3 overflow-hidden border-bottom-0">
            <ul class="nav nav-tabs product-info-tabs bg-light px-3 pt-3" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">Thông tin sản phẩm</button>
                </li>
            </ul>
        </div>
        <div class="tab-content border border-top-0 rounded-bottom-4 bg-white" id="productTabContent">         
            <!-- Thông tin -->
            <div class="tab-pane fade show active p-4 p-md-5" id="desc-pane" role="tabpanel" aria-labelledby="desc-tab" tabindex="0">
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Thành phần & Hàm lượng</h5>
                    @if($thuoc->thanh_phan || $thuoc->ham_luong)
                        <p>{!! nl2br(e($thuoc->thanh_phan)) !!}</p>
                        <p><strong>Hàm lượng:</strong> {!! nl2br(e($thuoc->ham_luong)) !!}</p>
                    @else
                        <p class="text-muted">Đang cập nhật</p>
                    @endif
                </div>

                <div class="mb-4">
                    <h5 class="text-primary mb-3">Công dụng</h5>
                    @if($thuoc->cong_dung)
                        <div>{!! nl2br(e($thuoc->cong_dung)) !!}</div>
                    @else
                        <p class="text-muted">Đang cập nhật</p>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Cách dùng</h5>
                    @if($thuoc->cach_dung)
                        <div>{!! nl2br(e($thuoc->cach_dung)) !!}</div>
                    @else
                        <p class="text-muted">Đang cập nhật</p>
                    @endif
                </div>
                
                @if($thuoc->chong_chi_dinh)
                <div class="mb-4">
                    <h5 class="text-danger mb-3">Chống chỉ định</h5>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis">
                        {!! nl2br(e($thuoc->chong_chi_dinh)) !!}
                    </div>
                </div>
                @endif
                
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Bảo quản & Dạng bào chế</h5>
                    <p><strong>Bảo quản:</strong> {{ $thuoc->bao_quan ?? 'Đang cập nhật' }}</p>
                    <p><strong>Dạng bào chế:</strong> {{ $thuoc->dang_bao_che ?? 'Đang cập nhật' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cùng danh mục -->
@if($similarProducts->count() > 0)
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold fs-4 mb-0 border-start border-4 border-primary ps-3">Sản phẩm tương tự</h3>
        <a href="{{ route('wholesale.catalog', ['nhom' => $thuoc->ma_nhom]) }}" class="text-decoration-none text-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-3 g-xl-4">
        @foreach($similarProducts as $sp)
        <div class="col-6 col-md-3">
            <div class="product-card h-100 d-flex flex-column">
                <div class="product-img-wrapper" onclick="window.location.href='{{ route('wholesale.product', $sp->ma_thuoc) }}'" style="cursor: pointer; {{ $sp->ton_kho_hien_tai <= 0 ? 'opacity: 0.7;' : '' }}">
                    @if($sp->ton_kho_hien_tai <= 0)
                        <div class="product-badge"><span class="badge bg-secondary rounded-pill px-2">Hết hàng</span></div>
                    @endif
                    <div class="placeholder-icon">
                        @if($sp->image1)
                            <img src="{{ asset('storage/' . $sp->image1) }}" alt="{{ $sp->ten_thuoc }}" class="img-fluid" style="max-height:120px; object-fit:contain;">
                        @else
                            <i class="bi bi-capsule"></i>
                        @endif
                    </div>
                </div>
                <div class="p-3 d-flex flex-column flex-grow-1">
                    <h3 class="product-title">
                        <a href="{{ route('wholesale.product', $sp->ma_thuoc) }}" class="text-decoration-none {{ $sp->ton_kho_hien_tai <= 0 ? 'text-muted' : 'text-dark' }}">{{ $sp->ten_thuoc }}</a>
                    </h3>
                    <div class="product-meta mb-2">{{ $sp->donViTinh->ten_dvt ?? '' }}</div>
                    
                    <div class="mt-auto pt-3 border-top position-relative">
                        <div class="product-price mb-2">{{ number_format($sp->gia_ban_de_xuat ?? 0) }}đ</div>
                        
                        @if($sp->ton_kho_hien_tai > 0)
                        <form method="POST" action="{{ route('wholesale.cart.add') }}" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="ma_thuoc" value="{{ $sp->ma_thuoc }}">
                            <input type="hidden" name="so_luong" value="1">
                            <button class="btn btn-outline-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Vào giỏ</span>
                            </button>
                        </form>
                        @else
                            <button class="btn btn-secondary w-100 disabled">Hết hàng</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script>
    function changeImage(src, element) {
        document.getElementById('mainImageContainer').innerHTML = '<img src="' + src + '" alt="Product Image" id="mainImage" style="width:100%; height:100%; object-fit:contain; padding:10px; position:absolute; top:0; left:0;">';
        
        let thumbnails = document.querySelectorAll('.thumbnail-item');
        thumbnails.forEach(function(el) {
            el.classList.remove('active');
        });
        
        element.classList.add('active');
    }
</script>
@endpush
@endsection
