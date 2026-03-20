@extends('layouts.wholesale')

@section('title', 'Chi tiết đơn hàng #' . $donHang->ma_don_hang)

@section('content')
    <div class="mb-4">
        <a href="{{ route('wholesale.orders.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách đơn hàng
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold">Đơn hàng: {{ $donHang->ma_don_hang }}</h6>
                        <small class="text-muted">Ngày đặt: {{ $donHang->ngay_dat->format('d/m/Y') }}</small>
                    </div>
                    <span class="badge bg-{{ $donHang->mauTrangThai }} fs-6">{{ $donHang->tenTrangThai }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-3">Sản phẩm</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-3">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donHang->chiTiet as $ct)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-medium">{{ $ct->thuoc->ten_thuoc ?? $ct->ma_thuoc }}</div>
                                            <div class="text-muted small">Mã: {{ $ct->ma_thuoc }}</div>
                                        </td>
                                        <td class="text-end small">{{ number_format($ct->don_gia) }}đ</td>
                                        <td class="text-center">{{ $ct->so_luong }}</td>
                                        <td class="text-end pe-3 fw-semibold text-primary">{{ number_format($ct->thanhTien) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-3">Tổng cộng:</td>
                                    <td class="text-end pe-3 fw-bold text-primary fs-5">{{ number_format($donHang->tong_tien) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Trạng thái đơn hàng</h6></div>
                <div class="card-body">
                    @php
                        $steps = [
                            ['key' => 'cho_xu_ly', 'label' => 'Đã đặt hàng', 'icon' => 'bi-send-check'],
                            ['key' => 'da_duyet', 'label' => 'Đã duyệt', 'icon' => 'bi-check-circle'],
                            ['key' => 'dang_xuat_kho', 'label' => 'Đang xuất kho', 'icon' => 'bi-box-seam'],
                            ['key' => 'da_hoan_thanh', 'label' => 'Hoàn thành', 'icon' => 'bi-trophy'],
                        ];
                        $statusOrder = ['cho_xu_ly' => 0, 'da_duyet' => 1, 'dang_xuat_kho' => 2, 'da_hoan_thanh' => 3, 'da_huy' => -1];
                        $currentStep = $statusOrder[$donHang->trang_thai_dh] ?? 0;
                    @endphp

                    @if($donHang->trang_thai_dh == 'da_huy')
                        <div class="text-center py-3">
                            <i class="bi bi-x-octagon text-danger fs-1 d-block mb-2"></i>
                            <h6 class="text-danger">Đơn hàng đã bị hủy</h6>
                        </div>
                    @else
                        @foreach($steps as $i => $step)
                            <div class="d-flex align-items-start mb-3 {{ $i <= $currentStep ? '' : 'opacity-50' }}">
                                <div class="me-3 text-center" style="width: 30px;">
                                    <i class="bi {{ $step['icon'] }} fs-5 {{ $i <= $currentStep ? 'text-primary' : 'text-muted' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold small {{ $i <= $currentStep ? 'text-dark' : 'text-muted' }}">{{ $step['label'] }}</div>
                                    @if($i == $currentStep)
                                        <div class="text-primary small"><i class="bi bi-arrow-right me-1"></i>Đang ở bước này</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                </div>
            </div>

            <!-- Nút thao tác khách hàng -->
            @if(in_array($donHang->trang_thai_dh, ['cho_xu_ly', 'da_duyet']))
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thao tác</h6></div>
                <div class="card-body d-grid gap-2">
                    <form method="POST" action="{{ route('wholesale.orders.edit', $donHang->ma_don_hang) }}" class="w-100" onsubmit="return confirm('Sửa đơn hàng sẽ hủy đơn hiện tại và đưa toàn bộ sản phẩm về Giỏ hàng để bạn chỉnh sửa. Bạn có muốn tiếp tục?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-pencil-square me-1"></i>Sửa đơn hàng
                        </button>
                    </form>
                    <form method="POST" action="{{ route('wholesale.orders.cancel', $donHang->ma_don_hang) }}" class="w-100" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i>Hủy đơn hàng
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
