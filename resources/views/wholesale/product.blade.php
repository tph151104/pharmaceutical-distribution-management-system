@extends('layouts.wholesale')

@section('title', 'Chi tiết sản phẩm - PharmaDistrib')

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
    .price-old {
        font-size: 1.1rem;
        color: #6c757d;
        text-decoration: line-through;
        margin-left: 10px;
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
        <li class="breadcrumb-item"><a href="{{ route('wholesale.catalog') }}">Thuốc không kê đơn</a></li>
        <li class="breadcrumb-item"><a href="{{ route('wholesale.catalog') }}">Giảm đau, hạ sốt</a></li>
        <li class="breadcrumb-item active" aria-current="page">Paracetamol 500mg - Dược Hậu Giang</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Product Gallery -->
    <div class="col-lg-5">
        <div class="sticky-top" style="top: 80px; z-index: 1;">
            <div class="product-gallery">
                <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-3 fs-6 rounded-pill px-3 py-2">HOT</span>
                <div class="main-image-placeholder">
                    <i class="bi bi-capsule"></i>
                </div>
            </div>
            
            <div class="thumbnail-gallery">
                <div class="thumbnail-item active"><i class="bi bi-capsule"></i></div>
                <div class="thumbnail-item"><i class="bi bi-box"></i></div>
                <div class="thumbnail-item"><i class="bi bi-file-medical"></i></div>
            </div>
        </div>
    </div>

    <!-- Product Summary Info -->
    <div class="col-lg-7">
        <div class="ps-lg-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary-emphasis px-2 py-1 rounded-1 fw-medium">Thuốc không kê đơn</span>
                <span class="product-sku ms-2">Mã SP: <strong>PARA500</strong></span>
            </div>
            
            <h1 class="product-title-large">Paracetamol 500mg - Dược Hậu Giang (Hộp 10 vỉ x 10 viên)</h1>
            
            <div class="d-flex align-items-center gap-4 mb-2 mt-3 pb-3 border-bottom">
                <div class="product-brand"><i class="bi bi-building me-1 text-muted"></i> Thương hiệu: <a href="#" class="text-decoration-none fw-medium">DHG Pharma</a></div>
                <div class="text-muted"><i class="bi bi-geo-alt me-1"></i> Xuất xứ: <span class="fw-medium text-dark">Việt Nam</span></div>
            </div>

            <div class="price-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted mb-1 small">Giá bán sỉ (đã bao gồm VAT)</div>
                        <div class="d-flex align-items-baseline">
                            <span class="price-main">120.000₫</span>
                            <span class="price-old">130.000₫</span>
                            <span class="badge bg-danger ms-2 rounded-1">-8%</span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3 opacity-25">
                
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>
                                <div class="fw-medium">Tình trạng: Còn hàng</div>
                                <div class="small text-muted">Sẵn sàng giao (320 hộp)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam-fill text-primary fs-5"></i>
                            <div>
                                <div class="fw-medium">Quy cách</div>
                                <div class="small text-muted">Hộp 10 vỉ x 10 viên nén</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 align-items-center mb-4 mt-4">
                <div class="qty-control">
                    <button class="qty-btn" type="button" onclick="const input = document.getElementById('qty'); input.value = Math.max(1, parseInt(input.value) - 1);"><i class="bi bi-dash"></i></button>
                    <input type="number" id="qty" class="qty-input-large" value="1" min="1">
                    <button class="qty-btn" type="button" onclick="const input = document.getElementById('qty'); input.value = parseInt(input.value) + 1;"><i class="bi bi-plus"></i></button>
                </div>
                <button class="btn btn-outline-primary btn-add-cart-large px-4 d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
                    <i class="bi bi-cart-plus fs-5"></i> Thêm vào giỏ
                </button>
                <a href="{{ route('wholesale.cart') }}" class="btn btn-primary btn-buy-now px-4 flex-grow-1 flex-md-grow-0">
                    Mua ngay
                </a>
            </div>

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
                <div class="col-12">
                     <div class="feature-box border bg-warning-subtle py-2">
                         <i class="bi bi-gift text-warning fs-4"></i>
                         <div class="small w-100">
                             <div class="fw-bold text-dark">Khuyến mãi đang áp dụng:</div>
                             <ul class="mb-0 ps-3">
                                 <li>Mua 50 hộp tặng 2 hộp cùng loại</li>
                                 <li>Giảm 2% thanh toán qua chuyển khoản trước</li>
                             </ul>
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
                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">Mô tả sản phẩm</button>
                </li> -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients-pane" type="button" role="tab">Thành phần</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="usage-tab" data-bs-toggle="tab" data-bs-target="#usage-pane" type="button" role="tab">Công dụng & Liều dùng</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="warning-tab" data-bs-toggle="tab" data-bs-target="#warning-pane" type="button" role="tab">Chống chỉ định</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs-pane" type="button" role="tab">Tài liệu đính kèm</button>
                </li>
            </ul>
        </div>
        <div class="tab-content border border-top-0 rounded-bottom-4 bg-white" id="productTabContent">         
            <!-- Thành phần -->
            <div class="tab-pane fade show active p-4 p-md-5" id="ingredients-pane" role="tabpanel" aria-labelledby="ingredients-tab" tabindex="0">
                <table class="table table-bordered table-striped" style="max-width: 600px;">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 70%">Hoạt chất / Tá dược</th>
                            <th scope="col" style="width: 30%">Hàm lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-medium">Paracetamol (Acetaminophen)</td>
                            <td>500 mg</td>
                        </tr>
                        <tr>
                            <td>Tinh bột ngô</td>
                            <td>Vừa đủ</td>
                        </tr>
                        <tr>
                            <td>Lactose monohydrat</td>
                            <td>Vừa đủ</td>
                        </tr>
                        <tr>
                            <td>Povidon K30</td>
                            <td>Vừa đủ</td>
                        </tr>
                        <tr>
                            <td>Natri starch glycolat</td>
                            <td>Vừa đủ</td>
                        </tr>
                        <tr>
                            <td>Talc, Magnesi stearat</td>
                            <td>Vừa đủ 1 viên</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-muted small mt-3">* Dạng bào chế: Viên nén dài.</p>
            </div>
            
            <!-- Công dụng & Liều dùng -->
            <div class="tab-pane fade p-4 p-md-5" id="usage-pane" role="tabpanel" aria-labelledby="usage-tab" tabindex="0">
                <h5 class="text-primary mb-3">Chỉ định (Công dụng)</h5>
                <p>Điều trị triệu chứng đau từ nhẹ đến vừa và/hoặc sốt.</p>
                <ul class="info-list mb-4">
                    <li>Hạ sốt nhanh chóng trong các trường hợp sốt do cảm cúm, nhiễm khuẩn...</li>
                    <li>Giảm đau hiệu quả đối với các chứng đau: Đau đầu, đau nửa đầu, đau răng, đau nhức cơ xương do vận động, đau bung kinh.</li>
                </ul>

                <h5 class="text-primary mb-3">Liều lượng & Cách dùng</h5>
                <p><strong>Cách dùng:</strong> Dùng đường uống. Có thể uống trong hoặc ngoài bữa ăn.</p>
                <div class="bg-light p-3 rounded border">
                    <p class="mb-2"><strong>Dành cho người lớn và trẻ em trên 12 tuổi:</strong></p>
                    <ul class="mb-3">
                        <li>Uống 1 - 2 viên (500mg - 1000mg) mỗi lần.</li>
                        <li>Khoảng cách giữa các liều là từ 4 - 6 giờ (khi cần thiết).</li>
                        <li><strong>Không quá:</strong> 8 viên (4g)/ngày.</li>
                    </ul>
                    <p class="mb-2"><strong>Dành cho trẻ em từ 6 đến 12 tuổi:</strong></p>
                    <ul class="mb-0">
                        <li>Uống ½ - 1 viên/lần. Lặp lại sau 4 - 6 giờ nếu cần. Không dùng quá 4 lần/ngày.</li>
                    </ul>
                </div>
            </div>

            <!-- Chống chỉ định -->
             <div class="tab-pane fade p-4 p-md-5" id="warning-pane" role="tabpanel" aria-labelledby="warning-tab" tabindex="0">
                <div class="alert alert-danger d-flex align-items-start gap-3 border-0 bg-danger-subtle text-danger-emphasis">
                    <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0 mt-1"></i>
                    <div>
                        <h5 class="alert-heading text-danger fw-bold">Chống chỉ định tuyệt đối:</h5>
                        <ul class="mb-0 ps-3">
                            <li>Người bệnh quá mẫn với paracetamol hay với bất kỳ thành phần nào của thuốc.</li>
                            <li>Người bệnh suy gan nặng, viêm gan tiến triển.</li>
                            <li>Người thiếu máu nhiều lần, có bệnh lý tiền sử về tim, phổi, thận, hoặc gan.</li>
                            <li>Người thiếu hụt men Glucose-6-phosphat dehydrogenase (G6PD).</li>
                        </ul>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Tác dụng phụ có thể gặp:</h5>
                <p>Paracetamol hiếm khi gây tác dụng phụ ở liều điều trị. Tuy nhiên, một số phản ứng có thể xảy ra:</p>
                <ul class="mb-4">
                    <li>Ban da, hồng ban, mề đay.</li>
                    <li>Rối loạn tiêu hóa nhẹ (buồn nôn, nôn).</li>
                    <li>Suy giảm chức năng gan nếu tự ý sử dụng quá liều cao trong thời gian dài (gây hoại tử tế bào gan rất nghiêm trọng).</li>
                </ul>
            </div>
            
            <!-- Tài liệu -->
            <div class="tab-pane fade p-4 p-md-5" id="docs-pane" role="tabpanel" aria-labelledby="docs-tab" tabindex="0">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center gap-3 p-3 border rounded">
                            <i class="bi bi-file-earmark-pdf text-danger fs-1"></i>
                            <div>
                                <h6 class="mb-1">Tờ rơi HDSD (PDF)</h6>
                                <a href="#" class="text-decoration-none small">Tải xuống (1.2MB)</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center gap-3 p-3 border rounded">
                            <i class="bi bi-file-earmark-pdf text-danger fs-1"></i>
                            <div>
                                <h6 class="mb-1">Giấy chứng nhận VSATTP</h6>
                                <a href="#" class="text-decoration-none small">Tải xuống (850KB)</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cùng danh mục -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold fs-4 mb-0 border-start border-4 border-primary ps-3">Sản phẩm tương tự</h3>
        <a href="{{ route('wholesale.catalog') }}" class="text-decoration-none text-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-3 g-xl-4">
        <!-- Render 4 similar product cards -->
        @for($i = 1; $i <= 4; $i++)
        <div class="col-6 col-md-3">
            <div class="product-card h-100 d-flex flex-column">
                <div class="product-img-wrapper" onclick="window.location.href='{{ route('wholesale.product') }}'" style="cursor: pointer;">
                    <div class="placeholder-icon">
                        <i class="bi bi-capsule"></i>
                    </div>
                </div>
                <div class="p-3 d-flex flex-column flex-grow-1">
                    <h3 class="product-title">
                        <a href="{{ route('wholesale.product') }}" class="text-decoration-none text-dark">Tiffy Dey Giảm cảm cúm, sổ mũi</a>
                    </h3>
                    <div class="product-meta mb-2">Thái Nakorn Patana</div>
                    
                    <div class="mt-auto pt-3 border-top position-relative">
                        <div class="product-price mb-2">105.000đ</div>
                        <button class="btn btn-outline-primary btn-add-cart w-100 d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-cart-plus flex-shrink-0"></i><span class="d-none d-sm-inline">Vào giỏ</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

@endsection

