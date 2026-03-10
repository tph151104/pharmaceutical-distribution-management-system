<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Đăng nhập hệ thống phân phối thuốc')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top left, #eff6ff 0, #e0f2fe 30%, #f9fafb 70%);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .auth-card {
            max-width: 960px;
            width: 100%;
            border-radius: 24px;
            border: none;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
            overflow: hidden;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .auth-logo {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0d6efd, #22c55e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="container px-3 px-md-4">
    <div class="card auth-card mx-auto">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-between text-white"
                 style="background: linear-gradient(160deg, #0d6efd, #1d4ed8); padding: 2rem 2.25rem;">
                <div>
                    <div class="auth-brand mb-4">
                        <div class="auth-logo">
                            <i class="bi bi-capsule-pill fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">PharmaDistrib</div>
                            <div class="small text-white-50">Hệ thống phân phối thuốc tây</div>
                        </div>
                    </div>
                    <h4 class="fw-semibold mb-2">Quản lý kho & công nợ<br>tập trung, chính xác</h4>
                    <p class="small text-white-75 mb-4">
                        Theo dõi nhập - xuất kho theo lô, hạn dùng, công nợ nhà cung cấp và khách hàng
                        trên một nền tảng duy nhất, trực quan.
                    </p>
                </div>
                <div class="small text-white-50">
                    © {{ date('Y') }} PharmaDistrib. All rights reserved.
                </div>
            </div>
            <div class="col-md-6 bg-white">
                <div class="p-4 p-md-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

@stack('scripts')
</body>
</html>

