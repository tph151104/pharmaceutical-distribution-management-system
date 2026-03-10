<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cổng khách hàng mua sỉ')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite(['resources/css/catalog.css'])
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
                <a href="{{ route('wholesale.cart') }}" class="btn btn-outline-secondary btn-sm position-relative">
                    <i class="bi bi-cart3 me-1"></i>Giỏ hàng
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </a>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm border-0 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        Khách hàng
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end small">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Thông tin tài khoản</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
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

