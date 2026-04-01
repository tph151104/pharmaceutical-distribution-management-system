@extends('layouts.wholesale')

@section('title', 'Chi tiết đơn hàng #' . $donHang->ma_don_hang)

@section('content')
    <div class="mb-4">
        <a href="{{ route('wholesale.orders.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách đơn hàng
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                            ['key' => 'dang_van_chuyen', 'label' => 'Đang vận chuyển', 'icon' => 'bi-truck'],
                            ['key' => 'da_hoan_thanh', 'label' => 'Hoàn thành', 'icon' => 'bi-trophy'],
                        ];
                        $statusOrder = ['cho_xu_ly' => 0, 'da_duyet' => 1, 'dang_xuat_kho' => 2, 'dang_van_chuyen' => 3, 'da_hoan_thanh' => 4, 'da_huy' => -1];
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
            @if(in_array($donHang->trang_thai_dh, ['cho_xu_ly', 'da_duyet', 'dang_xuat_kho']))
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thao tác</h6></div>
                <div class="card-body d-grid gap-2">
                    @if($donHang->trang_thai_dh === 'dang_xuat_kho')
                        <div class="alert alert-warning small mb-2">
                            <i class="bi bi-exclamation-triangle me-1"></i> Đơn hàng đang được xuất kho. Sửa hoặc hủy sẽ xóa phiếu xuất nháp liên quan.
                        </div>
                    @endif
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

            @if($donHang->trang_thai_dh === 'dang_van_chuyen')
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Xác nhận đơn hàng</h6></div>
                <div class="card-body d-grid gap-2">
                    <form method="POST" action="{{ route('wholesale.orders.complete', $donHang->ma_don_hang) }}" class="w-100" onsubmit="return confirm('Bạn xác nhận đã nhận đủ hàng và đơn hàng hoàn tất?')">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-2 fw-medium">
                            <i class="bi bi-box-seam me-2"></i>Đã nhận được hàng
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- PHẦN THANH TOÁN - hiện khi có phiếu xuất liên kết --}}
            @php
                $phieuXuat = \App\Models\PhieuXuat::where('ma_don_hang', $donHang->ma_don_hang)->first();
                $allPayments = $phieuXuat ? \App\Models\ThanhToan::where('ma_phieu_xuat', $phieuXuat->ma_phieu_xuat)->orderBy('ngay_thanh_toan', 'desc')->get() : collect();
                $tongDaTra = $allPayments->sum('so_tien_tt');
                $conNo = $donHang->tong_tien - $tongDaTra;
            @endphp

            @if($phieuXuat && in_array($donHang->trang_thai_dh, ['da_xuat_kho', 'dang_van_chuyen', 'da_hoan_thanh']))
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng</h6>
                    @if($allPayments->count() > 0)
                        <a href="{{ route('wholesale.orders.payment_history', $donHang->ma_don_hang) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-clock-history me-1"></i>Lịch sử ({{ $allPayments->count() }})
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    {{-- Tóm tắt công nợ --}}
                    <div class="bg-light rounded p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Tổng tiền đơn hàng:</span>
                            <span class="fw-bold">{{ number_format($donHang->tong_tien) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Đã thanh toán:</span>
                            <span class="text-success fw-semibold">{{ number_format($tongDaTra) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <span class="fw-bold">Còn nợ:</span>
                            <span class="fw-bold fs-5 {{ $conNo > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $conNo > 0 ? number_format($conNo) . 'đ' : 'Đã thanh toán đủ' }}
                            </span>
                        </div>
                    </div>

                    @if($conNo > 0)
                        {{-- Form thanh toán --}}
                        <form action="{{ route('wholesale.orders.pay', $donHang->ma_don_hang) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Số tiền thanh toán <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="so_tien_tt" class="form-control fw-bold text-primary" 
                                        required min="1" step="1" max="{{ $conNo }}" value="{{ (int)$conNo }}"
                                        placeholder="Nhập số tiền...">
                                    <span class="input-group-text fw-bold">VNĐ</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Phương thức thanh toán <span class="text-danger">*</span></label>
                                <select name="phuong_thuc_tt" class="form-select" required>
                                    <option value="Chuyển khoản">Chuyển khoản (NH)</option>
                                    <option value="Tiền mặt">Tiền mặt</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Minh chứng thanh toán</label>
                                <input type="file" name="minh_chung_tt_image" class="form-control form-control-sm" accept="image/*">
                                <div class="form-text">Tải ảnh chụp biên lai / xác nhận chuyển khoản (tùy chọn)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control form-control-sm" rows="2" placeholder="Nội dung / mã GD chuyển khoản..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-semibold" onclick="return confirm('Xác nhận thanh toán số tiền này?')">
                                <i class="bi bi-wallet2 me-1"></i>Xác nhận thanh toán
                            </button>
                        </form>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                            <h6 class="text-success mb-1">Đã thanh toán đầy đủ</h6>
                            <p class="text-muted small mb-0">Cảm ơn bạn đã thanh toán cho đơn hàng này.</p>
                        </div>
                    @endif
                </div>
            </div>
            @elseif(!$phieuXuat && $donHang->trang_thai_dh === 'da_hoan_thanh')
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng</h6></div>
                <div class="card-body text-center text-muted py-3">
                    <i class="bi bi-info-circle me-1"></i>Chưa có phiếu xuất kho liên kết. Vui lòng liên hệ nhân viên.
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

