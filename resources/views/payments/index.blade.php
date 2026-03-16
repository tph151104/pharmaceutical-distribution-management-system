@extends('layouts.app')

@section('title', 'Xử lý Thanh toán')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-cash-coin text-primary me-2"></i>Xử lý Thanh toán</h1>
            <p class="text-muted small mb-0 mt-1">Quản lý và thanh toán công nợ Phải thu / Phải trả</p>
        </div>
        <div>
            <a href="{{ route('payments.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history me-2"></i>Lịch sử thanh toán
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <ul class="nav nav-tabs card-header-tabs" id="paymentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="supplier-tab" data-bs-toggle="tab" data-bs-target="#supplier-pane" type="button" role="tab" aria-controls="supplier-pane" aria-selected="true">
                        <i class="bi bi-arrow-up-circle text-danger me-1"></i>Công nợ Phải trả (Nhà cung cấp)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-pane" type="button" role="tab" aria-controls="customer-pane" aria-selected="false">
                        <i class="bi bi-arrow-down-circle text-success me-1"></i>Công nợ Phải thu (Khách hàng)
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="paymentTabContent">
                
                <!-- THU NỢ NHÀ CUNG CẤP (PHẢI TRẢ) -->
                <div class="tab-pane fade show active" id="supplier-pane" role="tabpanel" aria-labelledby="supplier-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Mã Phiếu Nhập</th>
                                    <th>Nhà Cung Cấp</th>
                                    <th>Tổng Tiền</th>
                                    <th>Đã Thanh Toán</th>
                                    <th>Còn Nợ</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end pe-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phieuNhaps as $pn)
                                    <tr>
                                        <td class="ps-3 fw-medium">
                                            <a href="{{ route('imports.inspect', $pn->ma_phieu_nhap) }}" class="text-decoration-none">{{ $pn->ma_phieu_nhap }}</a>
                                            <div class="small text-muted">{{ $pn->ngay_nhap->format('d/m/Y') }}</div>
                                        </td>
                                        <td>{{ $pn->nhaCungCap->ten_ncc ?? 'N/A' }}</td>
                                        <td class="fw-semibold">{{ number_format($pn->tong_tien) }}</td>
                                        <td class="text-success">{{ number_format($pn->so_tien_da_tra) }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($pn->so_tien_con_no) }}</td>
                                        <td>
                                            @if($pn->trang_thai_tt == 'chua_tt')
                                                <span class="badge bg-danger-subtle text-danger-emphasis">Chưa thanh toán</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis">Một phần</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openPaymentModal({
                                                loai: 'nhap',
                                                ma_phieu: '{{ $pn->ma_phieu_nhap }}',
                                                doituong: '{{ $pn->nhaCungCap->ten_ncc ?? "N/A" }}',
                                                tong_tien: {{ $pn->tong_tien }},
                                                da_tra: {{ $pn->so_tien_da_tra }},
                                                con_no: {{ $pn->so_tien_con_no }}
                                            })">
                                                <i class="bi bi-wallet2 me-1"></i>Thanh toán
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-check-circle fs-4 d-block mb-2 text-success"></i>
                                            Không có công nợ Phải trả nào cần thanh toán.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- THU NỢ KHÁCH HÀNG (PHẢI THU) -->
                <div class="tab-pane fade" id="customer-pane" role="tabpanel" aria-labelledby="customer-tab" tabindex="0">
                     <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Mã Phiếu Xuất</th>
                                    <th>Khách Hàng</th>
                                    <th>Tổng Tiền</th>
                                    <th>Đã Thanh Toán</th>
                                    <th>Còn Nợ</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end pe-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phieuXuats as $px)
                                    <tr>
                                        <td class="ps-3 fw-medium">
                                            {{ $px->ma_phieu_xuat }}
                                            <div class="small text-muted">{{ \Carbon\Carbon::parse($px->ngay_xuat)->format('d/m/Y') }}</div>
                                        </td>
                                        <td>{{ $px->khachHang->ten_kh ?? 'N/A' }}</td>
                                        <td class="fw-semibold">{{ number_format($px->tong_tien) }}</td>
                                        <td class="text-success">{{ number_format($px->so_tien_da_tra) }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($px->so_tien_con_no) }}</td>
                                        <td>
                                            @if($px->trang_thai_tt == 'chua_tt')
                                                <span class="badge bg-danger-subtle text-danger-emphasis">Chưa thanh toán</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis">Một phần</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openPaymentModal({
                                                loai: 'xuat',
                                                ma_phieu: '{{ $px->ma_phieu_xuat }}',
                                                doituong: '{{ $px->khachHang->ten_kh ?? "N/A" }}',
                                                tong_tien: {{ $px->tong_tien }},
                                                da_tra: {{ $px->so_tien_da_tra }},
                                                con_no: {{ $px->so_tien_con_no }}
                                            })">
                                                <i class="bi bi-wallet2 me-1"></i>Thu tiền
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-check-circle fs-4 d-block mb-2 text-success"></i>
                                            Không có công nợ Phải thu nào cần thu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Thanh Toán -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold text-primary" id="paymentModalLabel">Thanh Toán / Thu Tiền</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0">
                        <input type="hidden" name="loai_thanh_toan" id="modal_loai">
                        <input type="hidden" name="ma_phieu" id="modal_ma_phieu">

                        <div class="bg-light p-3 rounded mb-3">
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Phiếu:</div>
                                <div class="col-7 fw-bold" id="lbl_phieu">-</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Đối tác:</div>
                                <div class="col-7 fw-medium" id="lbl_doi_tuong">-</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Tổng chứng từ:</div>
                                <div class="col-7" id="lbl_tong_tien">0</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Đã thanh toán:</div>
                                <div class="col-7 text-success" id="lbl_da_tra">0</div>
                            </div>
                            <div class="row border-top pt-1 mt-1">
                                <div class="col-5 text-muted small fw-bold">Còn nợ:</div>
                                <div class="col-7 text-danger fw-bold fs-5" id="lbl_con_no">0</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số tiền thanh toán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="so_tien_tt" id="modal_so_tien" class="form-control form-control-lg fw-bold text-primary" required min="1" step="1" max="">
                                <span class="input-group-text fw-bold">VNĐ</span>
                            </div>
                            <div class="form-text text-danger d-none" id="error_so_tien">Số tiền không được lớn hơn dư nợ.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phương thức <span class="text-danger">*</span></label>
                            <select name="phuong_thuc_tt" class="form-select" required>
                                <option value="Chuyển khoản">Chuyển khoản (NH)</option>
                                <option value="Tiền mặt">Tiền mặt</option>
                                <option value="Cấn trừ công nợ">Cấn trừ công nợ</option>
                            </select>
                        </div>
                        
                        <div class="mb-1">
                            <label class="form-label fw-semibold">Ghi chú (Tùy chọn)</label>
                            <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Nội dung/mã GD..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 mt-3 pb-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold" id="btn_submit_payment">Xác nhận Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openPaymentModal(data) {
        // Set values
        document.getElementById('modal_loai').value = data.loai;
        document.getElementById('modal_ma_phieu').value = data.ma_phieu;
        
        // Labels
        document.getElementById('lbl_phieu').textContent = data.ma_phieu;
        document.getElementById('paymentModalLabel').innerHTML = data.loai === 'nhap' ? '<i class="bi bi-arrow-up-circle"></i> Trả Nợ Nhà Cung Cấp' : '<i class="bi bi-arrow-down-circle"></i> Thu Nợ Khách Hàng';
        document.getElementById('lbl_doi_tuong').textContent = data.doituong;
        document.getElementById('lbl_tong_tien').textContent = new Intl.NumberFormat('vi-VN').format(data.tong_tien) + ' đ';
        document.getElementById('lbl_da_tra').textContent = new Intl.NumberFormat('vi-VN').format(data.da_tra) + ' đ';
        document.getElementById('lbl_con_no').textContent = new Intl.NumberFormat('vi-VN').format(data.con_no) + ' đ';

        // Set Input max and direct value
        let inputTien = document.getElementById('modal_so_tien');
        inputTien.max = data.con_no;
        inputTien.value = data.con_no; // Default full payment

        // Live Validate Function
        inputTien.oninput = function() {
            let val = parseFloat(this.value);
            let btn = document.getElementById('btn_submit_payment');
            let err = document.getElementById('error_so_tien');
            if (val > data.con_no) {
                btn.disabled = true;
                err.classList.remove('d-none');
            } else {
                btn.disabled = false;
                err.classList.add('d-none');
            }
        };

        // Open Modal
        var myModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        myModal.show();
    }
</script>
@endpush
