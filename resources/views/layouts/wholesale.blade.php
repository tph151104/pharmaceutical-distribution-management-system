<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cổng khách hàng mua sỉ')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f9fafb;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .store-navbar {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.06);
        }
        .product-card {
        transition: all 0.2s ease-in-out;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            border-color: #0d6efd;
        }
        .product-img-wrapper {
            position: relative;
            padding-top: 100%; 
            background-color: #f8f9fa;
            overflow: hidden;
        }
        .product-img-wrapper img, .product-img-wrapper .placeholder-icon {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }
        .product-img-wrapper .placeholder-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #dee2e6;
        }
        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }
        .product-title {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
        }
        .product-meta {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .product-price {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0d6efd;
        }
        .filter-sidebar {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 1.25rem;
        }
        .filter-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            color: #495057;
        }
        .custom-checkbox .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-add-cart {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
        }
        .qty-input {
            width: 60px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .qty-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
            outline: none;
        }
        /* CSS cho Footer hiện đại */
        .footer-modern {
            background-color: #f8f9fa;
            border-top: 1px solid #e5e7eb;
            color: #495057;
        }
        .footer-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.25rem;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        .footer-links a {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
            display: inline-block;
        }
        .footer-links a:hover {
            color: #0d6efd;
            transform: translateX(5px); 
        }
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        .social-icon:hover {
            background-color: #0d6efd;
            color: #fff;
            transform: translateY(-3px); /* Hiệu ứng nảy lên nhẹ */
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }
        .footer-contact li {
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
    </style>

    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg store-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('wholesale.catalog') }}">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white"
                  style="width:32px;height:32px;">
                <i class="bi bi-capsule-pill"></i>
            </span>
            <span class="fw-semibold">PharmaDistrib</span>
            <span class="badge bg-primary-subtle text-primary-emphasis small">Khách hàng mua sỉ</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="storeNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('wholesale.catalog') ? 'active' : '' }}"
                       href="{{ route('wholesale.catalog') }}">Danh sách sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('wholesale.orders.*') ? 'active' : '' }}"
                       href="{{ route('wholesale.orders.index') }}">Đơn hàng của tôi</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                @php $cartCount = count(session('cart', [])); @endphp
                <a href="{{ route('wholesale.cart') }}" class="btn btn-outline-secondary btn-sm position-relative">
                    <i class="bi bi-cart3 me-1"></i>Giỏ hàng
                    @if($cartCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $cartCount }}
                    </span>
                    @endif
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-light btn-sm border-0 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth('customer')->user()->ten_kh ?? 'Khách hàng' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end small">
                        <li><a class="dropdown-item" href="{{ route('wholesale.profile') }}"><i class="bi bi-person me-2"></i>Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item" href="{{ route('wholesale.orders.index') }}"><i class="bi bi-bag me-2"></i>Đơn hàng của tôi</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
    @yield('footer')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

@stack('scripts')
</body>
</html>

