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

            @if($donHang->trang_thai_dh === 'da_hoan_thanh')
            {{-- Kiểm tra công nợ --}}
            @php
                $phieuXuat = \App\Models\PhieuXuat::where('ma_don_hang', $donHang->ma_don_hang)->first();
                $thanhToan = $phieuXuat ? \App\Models\ThanhToan::where('ma_phieu_xuat', $phieuXuat->ma_phieu_xuat)->where('loai_thanh_toan','xuat')->first() : null;
            @endphp
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng</h6></div>
                <div class="card-body">
                    @if($thanhToan)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Tổng tiền đơn hàng:</span>
                            <span class="fw-bold">{{ number_format($thanhToan->tong_tien) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Đã thanh toán:</span>
                            <span class="text-success">{{ number_format($thanhToan->so_tien_tt) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small">
                            <span class="text-muted">Còn nợ:</span>
                            <span class="text-danger fw-bold">{{ number_format($thanhToan->so_tien_con_no) }}đ</span>
                        </div>
                        @if($thanhToan->so_tien_con_no > 0)
                            <div class="text-center text-danger fw-semibold py-2">
                                <i class="bi bi-info-circle me-2"></i>Chưa thanh toán dứt điểm
                            </div>
                        @else
                            <div class="text-center text-success fw-semibold py-2">
                                <i class="bi bi-check-circle-fill me-2"></i>Đã thanh toán đầy đủ
                            </div>
                        @endif
                    @elseif($phieuXuat)
                        {{-- Có phiếu xuất nhưng chưa có bản ghi thanh toán --}}
                        <div class="d-flex justify-content-between mb-3 small">
                            <span class="text-muted">Tổng tiền:</span>
                            <span class="fw-bold text-danger">{{ number_format($donHang->tong_tien) }}đ</span>
                        </div>
                        <div class="text-center text-danger fw-semibold py-2">
                            <i class="bi bi-info-circle me-2"></i>Chưa thanh toán
                        </div>
                    @else
                        <div class="text-center text-muted py-2">
                            <i class="bi bi-info-circle me-2"></i>Chưa có phiếu xuất kho liên kết. Vui lòng liên hệ nhân viên.
                        </div>
                    @endif
                </div>
            </div>
            @endif
            
            {{-- CHỨC NĂNG TRẢ HÀNG --}}
            @if($donHang->trang_thai_dh === 'da_hoan_thanh')
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold text-danger"><i class="bi bi-arrow-return-left me-2"></i>Trả hàng / Hoàn tiền</h6></div>
                <div class="card-body">
                    @if(isset($traHang) && $traHang)
                        <div class="alert alert-info py-2 small mb-0">
                            <strong>Trạng thái:</strong> 
                            @if($traHang->trang_thai == 'cho_duyet') <span class="badge bg-warning">Chờ duyệt</span>
                            @elseif($traHang->trang_thai == 'da_duyet_nhap_kho') <span class="badge bg-success">Đã nhận hàng (Hoàn tất)</span>
                            @elseif($traHang->trang_thai == 'tu_choi') <span class="badge bg-danger">Bị từ chối</span>
                            @endif
                            <br>
                            <em>Ghi chú: {{ $traHang->ghi_chu_admin ?? 'Đang chờ bộ phận kho xử lý' }}</em>
                        </div>
                    @else
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalReturnOrder">
                                Yêu cầu Trả hàng
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Modal Trả Hàng -->
            <div class="modal fade" id="modalReturnOrder" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('wholesale.orders.return', $donHang->ma_don_hang) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Yêu cầu Trả hàng</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning small">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Vui lòng nhập số lượng cho từng mặt hàng bạn muốn trả và lý do cụ thể. Những hàng không trả vui lòng để số lượng = 0.
                                </div>
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>SL đã mua</th>
                                                <th style="width: 120px;">SL Trả</th>
                                                <th>Lý do</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($donHang->chiTiet as $i => $ct)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $ct->thuoc->ten_thuoc ?? $ct->ma_thuoc }}</div>
                                                    </td>
                                                    <td class="text-center">{{ $ct->so_luong }}</td>
                                                    <td>
                                                        <input type="hidden" name="items[{{$i}}][ma_thuoc]" value="{{ $ct->ma_thuoc }}">
                                                        <input type="number" name="items[{{$i}}][so_luong]" class="form-control form-control-sm" max="{{ $ct->so_luong }}" min="0" value="0">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="items[{{$i}}][ly_do]" class="form-control form-control-sm" placeholder="Móp méo, cận date...">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Lý do trả hàng tổng quát <span class="text-danger">*</span></label>
                                    <textarea name="ly_do_chung" class="form-control" rows="2" placeholder="Giải thích thêm lý do trả hàng..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn gửi Yêu cầu trả hàng này?')">Xác nhận Gửi yêu cầu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
@endsection
