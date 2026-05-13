<!doctype html>
<html lang="vi">

<?php
    use App\Models\DonHang;
    use App\Models\KhachTraHang;
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hệ thống phân phối thuốc tây')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Icons (Bootstrap Icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f5f7fb;
        }

        .img-clickable {
            cursor: zoom-in;
            transition: transform 0.2s;
        }
        .img-clickable:hover {
            transform: scale(1.05);
            filter: brightness(0.9);
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0d6efd 0%, #0050c8 100%);
            color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 1.25rem 1rem;
            box-shadow: 2px 0 15px rgba(15, 23, 42, 0.25);
            z-index: 1030;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }

        .sidebar .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar .brand-text {
            font-weight: 600;
            line-height: 1.25;
        }

        .sidebar .brand-sub {
            font-size: .75rem;
            opacity: .85;
        }

        .sidebar-nav {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .sidebar-section-title {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            opacity: .7;
            margin: 1rem .75rem .25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .9rem;
            border-radius: .6rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: .9rem;
            transition: background-color .18s ease, color .18s ease, transform .08s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(15, 23, 42, 0.16);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background-color: #fff;
            color: #0d6efd;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
        }

        .sidebar-link-icon {
            width: 1.1rem;
            text-align: center;
            font-size: 1.05rem;
        }

        .sidebar-badge {
            margin-left: auto;
            font-size: .7rem;
        }

        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            backdrop-filter: blur(16px);
            background: rgba(248, 249, 252, 0.9);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        .main-header .search-input {
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            padding-left: 2.25rem;
            font-size: .9rem;
        }

        .main-header .search-input:focus {
            box-shadow: 0 0 0 .15rem rgba(13, 110, 253, 0.15);
            border-color: rgba(37, 99, 235, 0.8);
        }

        .search-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .9rem;
        }

        .content {
            padding: 1.5rem 1.75rem 2rem;
        }

        .content-header-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #0f172a;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .15rem .6rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 500;
            background: rgba(34, 197, 94, 0.08);
            color: #16a34a;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-logo">
            <i class="bi bi-capsule-pill fs-4"></i>
        </div>
        <div class="brand-text">
            <div>PharmaDistrib</div>
            <div class="brand-sub">Quản lý phân phối thuốc</div>
        </div>
    </div>

    @php $user = Auth::guard('admin')->user(); @endphp

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-speedometer2"></i></span>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- ═══ KHO HÀNG: Admin(1), NV Kho(2), Trưởng kho(5) ═══ --}}
        @if($user->hasRole(1, 2, 5))
        <li class="sidebar-section-title">Kho hàng</li>
        <li>
            <a href="{{ route('imports.index') }}" class="sidebar-link {{ request()->routeIs('imports.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-box-arrow-in-down"></i></span>
                <span>Nhập kho (mua hàng)</span>
            </a>
        </li>
        <li>
            <a href="{{ route('sales.index') }}" class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-box-arrow-up"></i></span>
                <span>Xuất kho bán sỉ</span>
            </a>
        </li>
        @endif

        {{-- ═══ ĐƠN HÀNG: Admin(1), NV Kho(2), NV BH(3), Trưởng kho(5) ═══ --}}
        @if($user->hasRole(1, 2, 3, 5))
        <li>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-box-seam"></i></span>
                <span>Quản lý Đơn hàng</span>
            </a>
        </li>
        @endif

        {{-- ═══ TỒN KHO: Admin(1), NV Kho(2), Trưởng kho(5) full; NV BH(3), KT(4) read-only ═══ --}}
        @if($user->hasRole(1, 2, 5))
        <li>
            <a href="{{ route('batches.index') }}" class="sidebar-link {{ request()->routeIs('batches.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-layers"></i></span>
                <span>Tồn kho theo lô</span>
            </a>
        </li>
        @elseif($user->hasRole(3, 4))
        <li>
            <a href="{{ route('batches.index') }}" class="sidebar-link {{ request()->routeIs('batches.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-layers"></i></span>
                <span>Tồn kho theo lô</span>
                <span class="sidebar-badge badge bg-light text-muted">Xem</span>
            </a>
        </li>
        @endif

        {{-- ═══ ĐIỀU CHUYỂN KHO: Admin(1), NV Kho(2), Trưởng kho(5) ═══ --}}
        @if($user->hasRole(1, 2, 5))
        <li>
            <a href="{{ route('transfers.index') }}" class="sidebar-link {{ request()->routeIs('transfers.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-arrow-left-right"></i></span>
                <span>Luân chuyển khu vực</span>
            </a>
        </li>
        <li>
            <a href="{{ route('supplier-returns.index') }}" class="sidebar-link {{ request()->routeIs('supplier-returns.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-box-arrow-right"></i></span>
                <span>Trả Hàng NCC</span>
            </a>
        </li>
        @endif

        {{-- ═══ TRẢ HÀNG: Tất cả roles nhưng NV Kho(2) + Kế toán(4) chỉ xem ═══ --}}
        <li>
            <a href="{{ route('admin.returns.index') }}" class="sidebar-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-arrow-return-left"></i></span>
                <span>Khách Trả Hàng</span>
                @if($user->hasRole(2, 4))
                <span class="sidebar-badge badge bg-light text-muted">Xem</span>
                @endif
            </a>
        </li>

        {{-- ═══════════ DANH MỤC ═══════════ --}}
        @if($user->hasRole(1, 2, 3, 4, 5))
        <li class="sidebar-section-title">Danh mục</li>
        @endif

        {{-- Danh mục thuốc: Admin(1), Trưởng kho(5) CRUD; NV Kho(2) xem --}}
        @if($user->hasRole(1, 2, 5))
        <li>
            <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-capsule"></i></span>
                <span>Danh mục thuốc / sản phẩm</span>
                @if($user->hasRole(2))
                <span class="sidebar-badge badge bg-light text-muted">Xem</span>
                @endif
            </a>
        </li>
        @endif

        {{-- Nhà cung cấp: Admin(1), Trưởng kho(5) CRUD; Kế toán(4) xem --}}
        @if($user->hasRole(1, 4, 5))
        <li>
            <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-building"></i></span>
                <span>Nhà cung cấp</span>
                @if($user->hasRole(4))
                <span class="sidebar-badge badge bg-light text-muted">Xem</span>
                @endif
            </a>
        </li>
        @endif

        {{-- Khách hàng: Admin(1), NV BH(3) full; Trưởng kho(5), KT(4) xem --}}
        @if($user->hasRole(1, 3, 4, 5))
        <li>
            <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-people"></i></span>
                <span>Quản lý khách hàng</span>
                @if($user->hasRole(4, 5))
                <span class="sidebar-badge badge bg-light text-muted">Xem</span>
                @endif
            </a>
        </li>
        @endif

        {{-- Thanh toán: Admin(1), Kế toán(4) --}}
        @if($user->hasRole(1, 4))
        <li>
            <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-cash-coin"></i></span>
                <span>Xử lý thanh toán</span>
            </a>
        </li>
        @endif

        {{-- ═══════════ BÁO CÁO ═══════════ --}}
        @if($user->hasRole(1, 3, 4, 5))
        <li class="sidebar-section-title">Báo cáo & Công nợ</li>
        @endif

        {{-- BC Tồn kho: Admin(1), Trưởng kho(5), Kế toán(4), NV Kho(2) --}}
        @if($user->hasRole(1, 4, 5,2))
        <li>
            <a href="{{ route('reports.stock') }}" class="sidebar-link {{ request()->routeIs('reports.stock*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-clipboard-data"></i></span>
                <span>Báo cáo tồn kho</span>
            </a>
        </li>
        @endif

        {{-- Lịch sử XNK: Admin(1), Trưởng kho(5) ONLY --}}
        @if($user->hasRole(1, 5))
        <li>
            <a href="{{ route('reports.movements') }}" class="sidebar-link {{ request()->routeIs('reports.movements*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-clock-history"></i></span>
                <span>Lịch sử nhập xuất kho</span>
            </a>
        </li>
        @endif

        {{-- Công nợ: Admin(1), NV BH(3), Kế toán(4) --}}
        @if($user->hasRole(1, 3, 4))
        <li>
            <a href="{{ route('reports.debts') }}" class="sidebar-link {{ request()->routeIs('reports.debts*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-cash-coin"></i></span>
                <span>Công nợ NCC & KH</span>
            </a>
        </li>
        @endif

        {{-- ═══════════ HỆ THỐNG ═══════════ --}}
        <li class="sidebar-section-title">Hệ thống</li>

        {{-- Quản lý người dùng: Chỉ Admin(1) --}}
        @if($user->hasRole(1))
        <li>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-person-fill-gear"></i></span>
                <span>Quản lý người dùng</span>
            </a>
        </li>
        @endif
        {{-- Quản lý người dùng: Chỉ Admin(1) --}}
        @if($user->hasRole(1))
        <li>
            <a href="{{ route('admin.features.index') }}" class="sidebar-link {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                <span class="sidebar-link-icon"><i class="bi bi-sliders"></i></span>
                <span>Quản lý bảo trì chức năng</span>
            </a>
        </li>
        @endif
    </ul>
    <div style="height: 50px;"></div>
</div>

<div class="main-wrapper">
    <header class="main-header">
        <div class="container-fluid py-2 px-3">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-light d-lg-none" type="button" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @php
                        // Lấy thông báo thực tế (chỉ tính đơn hàng và trả hàng đang chờ duyệt cho các role có quyền)
                        $tongThongBao = 0;
                        if ($user->hasRole(1, 3, 5)) {
                            $tongThongBao += DonHang::where('trang_thai_dh', 'cho_xu_ly')->count();
                            $tongThongBao += KhachTraHang::where('trang_thai', 'cho_duyet')->count();
                        }
                    @endphp
                    
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-circle position-relative" title="Xem công việc chờ xử lý">
                        <i class="bi bi-bell"></i>
                        @if($tongThongBao > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $tongThongBao > 99 ? '99+' : $tongThongBao }}
                            </span>
                        @endif
                    </a>
                    
                    <div class="dropdown">
                        <button class="btn btn-light border-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                            @php
                                $roleBgColors = [
                                    1 => 'bg-danger-subtle text-danger',
                                    2 => 'bg-warning-subtle text-warning',
                                    3 => 'bg-success-subtle text-success',
                                    4 => 'bg-info-subtle text-info',
                                    5 => 'bg-primary-subtle text-primary',
                                ];
                                $hoTenParts = explode(' ', trim($user->ho_ten_nd));
                                $ten = end($hoTenParts);
                                $initials = mb_strtoupper(mb_substr($ten, 0, 1));
                                $avatarClass = $roleBgColors[$user->role] ?? 'bg-secondary-subtle text-secondary';
                            @endphp
                            <div class="rounded-circle {{ $avatarClass }} d-flex align-items-center justify-content-center"
                                 style="width:32px;height:32px;">
                                <span class="fw-bold" style="font-size: 0.85rem;">{{ $initials }}</span>
                            </div>
                            <div class="text-start d-none d-sm-block">
                                <div class="small fw-semibold">{{ $user->ho_ten_nd }}</div>
                                <div class="small text-muted" style="font-size: 0.75rem;">{{ $user->role_name }}</div>
                            </div>
                            <i class="bi bi-chevron-down small ms-1 text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li><h6 class="dropdown-header">Tài khoản</h6></li>
                            <li><a class="dropdown-item py-2" href="{{ route('account.profile') }}"><i class="bi bi-person me-2 text-muted"></i>Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('account.password') }}"><i class="bi bi-shield-lock me-2 text-muted"></i>Đổi mật khẩu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger fw-medium">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="content">
        @yield('content-header')
        @yield('content')
    </main>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        if (!sidebar || !toggle) return;

        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Hệ thống',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>

@stack('scripts')
    <!-- Global Image Preview Modal -->
    <div class="modal fade" id="globalImageModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="" id="globalImagePreview" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sự kiện cho tất cả ảnh có class img-clickable
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('img-clickable')) {
                    const src = e.target.getAttribute('src');
                    document.getElementById('globalImagePreview').setAttribute('src', src);
                    const modal = new bootstrap.Modal(document.getElementById('globalImageModal'));
                    modal.show();
                }
            });
        });
    </script>
</body>
</html>

