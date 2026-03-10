@extends('layouts.wholesale')

@section('title', 'Danh sách sản phẩm mua sỉ - PharmaDistrib')

@push('styles')
<style>

</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Sidebar Filters -->
    <div class="col-lg-3 d-none d-lg-block">
        <div class="filter-sidebar sticky-top" style="top: 80px;">
            <div class="mb-4">
                <h6 class="filter-title">Danh mục sản phẩm</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="cat1" checked>
                        <label class="form-check-label" for="cat1">Tất cả sản phẩm</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="cat2">
                        <label class="form-check-label" for="cat2">Thuốc kê đơn (Rx)</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="cat3">
                        <label class="form-check-label" for="cat3">Thuốc không kê đơn</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="cat4">
                        <label class="form-check-label" for="cat4">Thực phẩm chức năng</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="cat5">
                        <label class="form-check-label" for="cat5">Dụng cụ y tế</label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="filter-title">Hoạt chất phổ biến</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="ing1">
                        <label class="form-check-label" for="ing1">Paracetamol</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="ing2">
                        <label class="form-check-label" for="ing2">Amoxicillin</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="ing3">
                        <label class="form-check-label" for="ing3">Vitamin C</label>
                    </div>
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="ing4">
                        <label class="form-check-label" for="ing4">Ibuprofen</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-12 col-lg-9">
        <!-- Search & Sort Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 bg-white p-3 rounded-3 shadow-sm border" style="border-color: #e5e7eb !important;">
            <div class="flex-grow-1 position-relative" style="max-width: 500px;">
                <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" style="left: 15px;"></i>
                <input type="search" class="form-control bg-light border-0 ps-5" placeholder="Tìm theo tên thuốc, mã, hoạt chất..." style="border-radius: 8px;">
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small text-nowrap">Sắp xếp:</span>
                <select class="form-select border-0 bg-light form-select-sm" style="border-radius: 6px; min-width: 150px;">
                    <option>Bán chạy nhất</option>
                    <option>Giá từ thấp đến cao</option>
                    <option>Giá từ cao đến thấp</option>
                    <option>Mới cập nhật</option>
                </select>
            </div>
        </div>

        <!-- Active Filters (Mobile) -->
        <div class="d-lg-none mb-3 d-flex gap-2 overflow-auto pb-2" style="-webkit-overflow-scrolling: touch; scrollbar-width: none;">
            <span class="badge bg-primary rounded-pill py-2 px-3 fw-normal">Tất cả sản phẩm <i class="bi bi-x ms-1"></i></span>
            <button class="btn btn-sm btn-outline-secondary rounded-pill text-nowrap d-flex align-items-center"><i class="bi bi-funnel me-1"></i> Lọc</button>
        </div>

        <!-- Product Grid -->
        <div class="row g-3 g-xl-4">
            <!-- Product Item 1 -->
            <div class="col-6 col-md-4">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="product-img-wrapper">
                        <div class="product-badge">
                            <span class="badge bg-danger rounded-pill px-2">HOT</span>
                        </div>
                        <div class="placeholder-icon">
                            <i class="bi bi-capsule"></i>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                            <span>Mã: PARA500</span>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-1">Hộp 100v</span>
                        </div>
                        <h3 class="product-title">
                            <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-dark">Paracetamol 500mg - Dược Hậu Giang</a>
                        </h3>
                        <div class="product-meta mb-2">
                            <span><i class="bi bi-building me-1"></i>DHG Pharma</span>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top position-relative">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="text-muted small text-decoration-line-through">130.000đ</div>
                                    <div class="product-price">120.000đ</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Còn hàng</div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="btn btn-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Chọn</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Item 2 -->
            <div class="col-6 col-md-4">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="product-img-wrapper">
                        <div class="product-badge">
                            <span class="badge bg-warning text-dark rounded-pill px-2">Rx</span>
                        </div>
                        <div class="placeholder-icon">
                            <i class="bi bi-prescription2"></i>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                            <span>Mã: AMOX500</span>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-1">Hộp 10 vỉ</span>
                        </div>
                        <h3 class="product-title">
                            <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-dark">Amoxicillin 500mg - Domesco</a>
                        </h3>
                        <div class="product-meta mb-2">
                            <span><i class="bi bi-building me-1"></i>Domesco</span>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top position-relative">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="text-muted small">&nbsp;</div>
                                    <div class="product-price">180.000đ</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Còn hàng</div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="btn btn-outline-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Chọn</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Item 3 -->
            <div class="col-6 col-md-4">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="product-img-wrapper">
                        <div class="product-badge">
                            <span class="badge bg-info text-white rounded-pill px-2">Mới</span>
                        </div>
                        <div class="placeholder-icon">
                            <i class="bi bi-bandaid"></i>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                            <span>Mã: VITC500</span>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-1">Lọ 100v</span>
                        </div>
                        <h3 class="product-title">
                            <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-dark">Vitamin C 500mg Tăng cường đề kháng Traphaco</a>
                        </h3>
                        <div class="product-meta mb-2">
                            <span><i class="bi bi-building me-1"></i>Traphaco</span>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top position-relative">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="text-muted small">&nbsp;</div>
                                    <div class="product-price">65.000đ</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning small"><i class="bi bi-exclamation-circle-fill me-1"></i>Sắp hết</div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="btn btn-outline-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Chọn</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Item 4 -->
            <div class="col-6 col-md-4">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="product-img-wrapper">
                        <div class="placeholder-icon">
                            <i class="bi bi-capsule-pill"></i>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                            <span>Mã: IBU400</span>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-1">Hộp 5 vỉ</span>
                        </div>
                        <h3 class="product-title">
                            <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-dark">Ibuprofen 400mg giảm đau kháng viêm</a>
                        </h3>
                        <div class="product-meta mb-2">
                            <span><i class="bi bi-building me-1"></i>Hà Tây Pharma</span>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top position-relative">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="text-muted small">&nbsp;</div>
                                    <div class="product-price">45.000đ</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Còn hàng</div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="btn btn-outline-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Chọn</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Item 5 -->
            <div class="col-6 col-md-4">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="product-img-wrapper" style="opacity: 0.7;">
                        <div class="product-badge">
                            <span class="badge bg-secondary rounded-pill px-2">Hết hàng</span>
                        </div>
                        <div class="placeholder-icon">
                            <i class="bi bi-droplet"></i>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                            <span>Mã: ORS200</span>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-1">Gói</span>
                        </div>
                        <h3 class="product-title">
                            <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-muted">Oresol Cam bù nước bù điện giải</a>
                        </h3>
                        <div class="product-meta mb-2">
                            <span><i class="bi bi-building me-1"></i>Dược Trung Ương</span>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top position-relative">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="text-muted small">&nbsp;</div>
                                    <div class="product-price text-muted">2.500đ</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-danger small"><i class="bi bi-x-circle-fill me-1"></i>Hết hàng</div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <input type="number" class="qty-input bg-light text-muted" value="0" disabled>
                                <button class="btn btn-secondary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2" disabled>
                                    <i class="bi bi-bell flex-shrink-0"></i><span class="d-none d-sm-inline">Thông báo</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Item 6 -->
            <div class="col-6 col-md-4">
                <div class="product-card h-100 d-flex flex-column">
                    <div class="product-img-wrapper">
                        <div class="placeholder-icon">
                            <i class="bi bi-thermometer"></i>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="product-meta mb-1 d-flex justify-content-between align-items-center">
                            <span>Mã: THER01</span>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-1">Cái</span>
                        </div>
                        <h3 class="product-title">
                            <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-dark">Nhiệt kế điện tử Omron đo trán</a>
                        </h3>
                        <div class="product-meta mb-2">
                            <span><i class="bi bi-building me-1"></i>Omron</span>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top position-relative">
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="text-muted small text-decoration-line-through">950.000đ</div>
                                    <div class="product-price">890.000đ</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Còn hàng</div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="btn btn-outline-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Chọn</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5 mb-4">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                        </a>
                    </li>
                    <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">12</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="footer-modern pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row gy-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-capsule-pill fs-5"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-primary">PharmaDistrib</h4>
                </div>
                <p class="text-muted small pe-lg-4 line-height-base">
                    Hệ thống phân phối dược phẩm sỉ uy tín hàng đầu. Chúng tôi cam kết cung cấp các sản phẩm chất lượng cao, chính hãng cho nhà thuốc và phòng khám trên toàn quốc.
                </p>
                <div class="d-flex gap-2 mt-4">
                    <a href="#" class="social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-icon" title="Tiktok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title">Về chúng tôi</h5>
                <ul class="footer-links">
                    <li><a href="#">Giới thiệu công ty</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Chính sách giao hàng</a></li>
                    <li><a href="#">Bảo mật thông tin</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Liên hệ</h5>
                <ul class="list-unstyled footer-contact text-muted">
                    <li>
                        <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                        <span>123 Tòa nhà Etown, Phường 13, Quận Tân Bình, TP.HCM</span>
                    </li>
                    <li>
                        <i class="bi bi-telephone-fill text-primary mt-1"></i>
                        <span>Hotline sỉ: <strong>1900 xxxx</strong></span>
                    </li>
                    <li>
                        <i class="bi bi-envelope-fill text-primary mt-1"></i>
                        <span>sales@pharmadistrib.com</span>
                    </li>
                    <li>
                        <i class="bi bi-clock-fill text-primary mt-1"></i>
                        <span>Thứ 2 - Thứ 7: 8:00 - 17:30</span>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Đăng ký nhận báo giá</h5>
                <p class="text-muted small">Nhận thông báo sớm nhất về các đợt cập nhật giá thuốc và chương trình khuyến mãi.</p>
                <form class="mt-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email của bạn" aria-label="Email" style="border-radius: 8px 0 0 8px;">
                        <button class="btn btn-primary" type="button" style="border-radius: 0 8px 8px 0;">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row border-top pt-3 align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <p class="text-muted small mb-0">&copy; 2026 PharmaDistrib. Đã đăng ký bản quyền.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="text-muted small me-2">Chấp nhận thanh toán:</span>
                <i class="bi bi-credit-card text-muted fs-5 me-1"></i>
                <i class="bi bi-cash-coin text-muted fs-5"></i>
            </div>
        </div>
    </div>
</footer>
@endsection