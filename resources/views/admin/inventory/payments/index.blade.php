@extends('layouts.app')

@section('title', 'Xử lý Thanh toán')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-cash-coin text-primary me-2"></i>Xử lý Thanh toán</h1>
            <p class="text-muted small mb-0 mt-1">Quản lý và thanh toán công nợ Phải thu / Phải trả / Hoàn trả</p>
        </div>
        <div>
            <a href="{{ route('payments.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history me-2"></i>Lịch sử thanh toán
            </a>
        </div>
    </div>
@endsection

@section('content')
    {{-- Alert Messages --}}
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
                    <button class="nav-link {{ $activeTab == 'supplier' || $activeTab == '' ? 'active' : '' }} fw-semibold"
                            id="supplier-tab" data-bs-toggle="tab" data-bs-target="#supplier-pane"
                            type="button" role="tab">
                        <i class="bi bi-arrow-up-circle text-danger me-1"></i>Công nợ Phải trả (Nhà cung cấp)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'customer' ? 'active' : '' }} fw-semibold"
                            id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-pane"
                            type="button" role="tab">
                        <i class="bi bi-arrow-down-circle text-success me-1"></i>Công nợ Phải thu (Khách hàng)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'tra_hang' ? 'active' : '' }} fw-semibold"
                            id="return-tab" data-bs-toggle="tab" data-bs-target="#return-pane"
                            type="button" role="tab">
                        <i class="bi bi-arrow-return-left text-warning me-1"></i>Hoàn trả hàng (KH & NCC)
                        @php $totalPending = $donTraHangs->count() + $traNccs->count(); @endphp
                        @if($totalPending > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $totalPending }}</span>
                        @endif
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="paymentTabContent">

                {{-- THU NỢ NHÀ CUNG CẤP (PHẢI TRẢ) --}}
                <div class="tab-pane fade {{ $activeTab == 'supplier' || $activeTab == '' ? 'show active' : '' }}"
                     id="supplier-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-end p-2 border-bottom text-bg-light">
                        <a href="{{ route('payments.export.suppliers') }}" class="btn btn-sm btn-success">
                            <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel
                        </a>
                    </div>
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
                                                <span class="badge bg-danger shadow-sm"><i class="bi bi-x-circle me-1"></i>Chưa thanh toán</span>
                                            @else
                                                <span class="badge bg-warning text-dark shadow-sm"><i class="bi bi-hourglass-split me-1"></i>Thanh toán một phần</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openPaymentModal({
                                                loai: 'nhap',
                                                ma_phieu: '{{ $pn->ma_phieu_nhap }}',
                                                doituong: '{{ $pn->nhaCungCap->ten_ncc ?? "N/A" }}',
                                                tong_tien: {{ $pn->tong_tien ?? 0 }},
                                                da_tra: {{ $pn->so_tien_da_tra ?? 0 }},
                                                con_no: {{ $pn->so_tien_con_no ?? 0 }}
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

                {{-- THU NỢ KHÁCH HÀNG (PHẢI THU) --}}
                <div class="tab-pane fade {{ $activeTab == 'customer' ? 'show active' : '' }}"
                     id="customer-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-end p-2 border-bottom text-bg-light">
                        <a href="{{ route('payments.export.customers') }}" class="btn btn-sm btn-success">
                            <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel
                        </a>
                    </div>
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
                                                <span class="badge bg-danger shadow-sm"><i class="bi bi-x-circle me-1"></i>Chưa thu tiền</span>
                                            @else
                                                <span class="badge bg-warning text-dark shadow-sm"><i class="bi bi-hourglass-split me-1"></i>Thu nợ một phần</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openPaymentModal({
                                                loai: 'xuat',
                                                ma_phieu: '{{ $px->ma_phieu_xuat }}',
                                                doituong: '{{ $px->khachHang->ten_kh ?? "N/A" }}',
                                                tong_tien: {{ $px->tong_tien ?? 0 }},
                                                da_tra: {{ $px->so_tien_da_tra ?? 0 }},
                                                con_no: {{ $px->so_tien_con_no ?? 0 }}
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

                {{-- HOÀN TRẢ ĐƠN HÀNG (KH) --}}
                <div class="tab-pane fade {{ $activeTab == 'tra_hang' ? 'show active' : '' }}"
                     id="return-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-end p-2 border-bottom text-bg-light">
                        <a href="{{ route('payments.export.returns') }}" class="btn btn-sm btn-success">
                            <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel
                        </a>
                    </div>
                    <div class="table-responsive">
                        <h6 class="p-3 mb-0 bg-light border-bottom text-primary fw-bold"><i class="bi bi-person-badge me-2"></i>Khách hàng trả hàng (Cần hoàn tiền)</h6>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Mã Đơn Trả</th>
                                    <th>Khách Hàng</th>
                                    <th>Đơn Hàng Gốc</th>
                                    <th>Tổng Hoàn</th>
                                    <th>Đã Hoàn</th>
                                    <th>Còn Lại</th>
                                    <th class="text-end pe-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donTraHangs as $dth)
                                    <tr>
                                        <td class="ps-3 fw-medium">
                                            <a href="{{ route('admin.returns.show', $dth->ma_tra_hang) }}" class="text-decoration-none">{{ $dth->ma_tra_hang }}</a>
                                        </td>
                                        <td>{{ $dth->khachHang->ten_kh ?? 'N/A' }}</td>
                                        <td class="small text-muted">{{ $dth->ma_don_hang }}</td>
                                        <td class="fw-semibold">{{ number_format($dth->tong_tien_hoan_tra) }}</td>
                                        <td class="text-success">{{ number_format($dth->so_tien_da_hoan ?? 0) }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($dth->so_tien_con_hoan) }}</td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-warning fw-semibold"
                                                    onclick="openRefundModal({
                                                        type: 'customer',
                                                        ma_phieu: '{{ $dth->ma_tra_hang }}',
                                                        doituong: '{{ $dth->khachHang->ten_kh ?? "N/A" }}',
                                                        phu_de: 'Đơn hàng: {{ $dth->ma_don_hang }}',
                                                        tong: {{ $dth->tong_tien_hoan_tra ?? 0 }},
                                                        da_tra: {{ $dth->so_tien_da_hoan ?? 0 }},
                                                        con_lai: {{ $dth->so_tien_con_hoan ?? 0 }}
                                                    })">
                                                <i class="bi bi-cash me-1"></i>Hoàn tiền
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-3 text-muted small">Không có đơn khách trả hàng nào cần hoàn tiền.</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        <h6 class="p-3 mb-0 bg-light border-bottom border-top text-danger fw-bold"><i class="bi bi-building me-2"></i>Trả hàng Nhà Cung Cấp (Cần nhận lại tiền)</h6>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Mã Phiếu Trả</th>
                                    <th>Nhà Cung Cấp</th>
                                    <th>Ngày Tạo</th>
                                    <th>Tổng Tiền</th>
                                    <th>Đã Nhận</th>
                                    <th>Còn Lại</th>
                                    <th class="text-end pe-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($traNccs as $tn)
                                    <tr>
                                        <td class="ps-3 fw-medium">
                                            <a href="{{ route('supplier-returns.show', $tn->ma_phieu_tra_ncc) }}" class="text-decoration-none">{{ $tn->ma_phieu_tra_ncc }}</a>
                                        </td>
                                        <td>{{ $tn->nhaCungCap->ten_ncc ?? 'N/A' }}</td>
                                        <td class="small">{{ $tn->ngay_tao ? $tn->ngay_tao->format('d/m/Y') : '-' }}</td>
                                        <td class="fw-semibold">{{ number_format($tn->tong_tien) }}</td>
                                        <td class="text-success">{{ number_format($tn->so_tien_da_nhan ?? 0) }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($tn->so_tien_con_nhan) }}</td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-danger fw-semibold"
                                                    onclick="openRefundModal({
                                                        type: 'supplier',
                                                        ma_phieu: '{{ $tn->ma_phieu_tra_ncc }}',
                                                        doituong: '{{ $tn->nhaCungCap->ten_ncc ?? "N/A" }}',
                                                        phu_de: 'Mã phiếu trả: {{ $tn->ma_phieu_tra_ncc }}',
                                                        tong: {{ $tn->tong_tien ?? 0 }},
                                                        da_tra: {{ $tn->so_tien_da_nhan ?? 0 }},
                                                        con_lai: {{ $tn->so_tien_con_nhan ?? 0 }}
                                                    })">
                                                <i class="bi bi-wallet2 me-1"></i>Nhận tiền
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-3 text-muted small">Không có phiếu trả NCC nào cần thu hồi tiền.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Thanh Toán (Phải thu / Phải trả) --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
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
                                <input type="number" name="so_tien_tt" id="modal_so_tien" class="form-control form-control-lg fw-bold text-primary" required min="1000" step="1000">
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

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Minh chứng thanh toán (Tùy chọn)</label>
                            <input type="file" name="minh_chung_tt_image" class="form-control" accept="image/*">
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

    {{-- Modal Hoàn Tiền Đơn Trả Hàng --}}
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="refundForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-bottom-0 bg-warning bg-opacity-10">
                        <h5 class="modal-title fw-bold text-warning" id="refundModalLabel">
                            <i class="bi bi-arrow-return-left me-2"></i>Hoàn Tiền Đơn Trả Hàng
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0 pt-3">
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Mã chứng từ:</div>
                                <div class="col-7 fw-bold" id="ref_ma_phieu">-</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Đối tượng:</div>
                                <div class="col-7 fw-medium" id="ref_doi_tuong">-</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small" id="ref_lbl_phu_de">Thông tin:</div>
                                <div class="col-7 small text-muted" id="ref_val_phu_de">-</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Tổng tiền:</div>
                                <div class="col-7 fw-semibold" id="ref_tong">0</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 text-muted small">Đã xử lý:</div>
                                <div class="col-7 text-success" id="ref_da_tra">0</div>
                            </div>
                            <div class="row border-top pt-1 mt-1">
                                <div class="col-5 text-muted small fw-bold">Còn lại:</div>
                                <div class="col-7 text-danger fw-bold fs-5" id="ref_con_lai">0</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số tiền hoàn trả <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="so_tien_tt" id="ref_so_tien" class="form-control form-control-lg fw-bold text-warning" required min="1" step="1">
                                <span class="input-group-text fw-bold">VNĐ</span>
                            </div>
                            <div class="form-text text-danger d-none" id="ref_error_so_tien">Số tiền không được lớn hơn số tiền còn phải hoàn.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phương thức hoàn tiền <span class="text-danger">*</span></label>
                            <select name="phuong_thuc_tt" class="form-select" required>
                                <option value="Chuyển khoản">Chuyển khoản (NH)</option>
                                <option value="Tiền mặt">Tiền mặt</option>
                                <option value="Cấn trừ công nợ">Cấn trừ công nợ</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Minh chứng (Tùy chọn)</label>
                            <input type="file" name="minh_chung_tt_image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-semibold">Ghi chú (Tùy chọn)</label>
                            <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Nội dung chuyển khoản, ghi chú..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 mt-3 pb-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning px-4 fw-bold" id="btn_submit_refund">
                            <i class="bi bi-arrow-return-left me-1"></i>Xác nhận Hoàn tiền
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Activate the correct tab on page load
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'tra_hang') {
            const returnTab = document.getElementById('return-tab');
            if (returnTab) bootstrap.Tab.getOrCreateInstance(returnTab).show();
        } else if (tab === 'customer') {
            const customerTab = document.getElementById('customer-tab');
            if (customerTab) bootstrap.Tab.getOrCreateInstance(customerTab).show();
        }
    });

    function openPaymentModal(data) {
        document.getElementById('modal_loai').value = data.loai;
        document.getElementById('modal_ma_phieu').value = data.ma_phieu;
        document.getElementById('lbl_phieu').textContent = data.ma_phieu;
        document.getElementById('paymentModalLabel').innerHTML = data.loai === 'nhap'
            ? '<i class="bi bi-arrow-up-circle"></i> Trả Nợ Nhà Cung Cấp'
            : '<i class="bi bi-arrow-down-circle"></i> Thu Nợ Khách Hàng';
        document.getElementById('lbl_doi_tuong').textContent = data.doituong;
        document.getElementById('lbl_tong_tien').textContent = new Intl.NumberFormat('vi-VN').format(data.tong_tien) + ' đ';
        document.getElementById('lbl_da_tra').textContent = new Intl.NumberFormat('vi-VN').format(data.da_tra) + ' đ';
        document.getElementById('lbl_con_no').textContent = new Intl.NumberFormat('vi-VN').format(data.con_no) + ' đ';

        let inputTien = document.getElementById('modal_so_tien');
        inputTien.max = data.con_no;
        inputTien.value = data.con_no;

        // Validation: Số tiền không vượt quá dư nợ, tối thiểu 1000 VNĐ
        inputTien.oninput = function () {
            let val = parseFloat(this.value); //lấy tiền vừa gõ
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

        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    }

    function openRefundModal(data) {
        // Set form action dynamically
        if (data.type === 'customer') {
            document.getElementById('refundForm').action = `/admin/returns/${data.ma_phieu}/refund`;
            document.getElementById('refundModalLabel').innerHTML = '<i class="bi bi-arrow-return-left me-2"></i>Hoàn Tiền Cho Khách Hàng';
            document.getElementById('refundModalLabel').className = 'modal-title fw-bold text-warning';
        } else {
            document.getElementById('refundForm').action = `/admin/supplier-returns/${data.ma_phieu}/process-refund`;
            document.getElementById('refundModalLabel').innerHTML = '<i class="bi bi-wallet2 me-2"></i>Nhận Tiền Từ Nhà Cung Cấp';
            document.getElementById('refundModalLabel').className = 'modal-title fw-bold text-danger';
        }

        document.getElementById('ref_ma_phieu').textContent = data.ma_phieu;
        document.getElementById('ref_doi_tuong').textContent = data.doituong;
        document.getElementById('ref_val_phu_de').textContent = data.phu_de;
        document.getElementById('ref_tong').textContent = new Intl.NumberFormat('vi-VN').format(data.tong) + ' đ';
        document.getElementById('ref_da_tra').textContent = new Intl.NumberFormat('vi-VN').format(data.da_tra) + ' đ';
        document.getElementById('ref_con_lai').textContent = new Intl.NumberFormat('vi-VN').format(data.con_lai) + ' đ';

        let inputRef = document.getElementById('ref_so_tien');
        inputRef.max = data.con_lai;
        inputRef.value = data.con_lai;

        inputRef.oninput = function () {
            let val = parseFloat(this.value);
            let btn = document.getElementById('btn_submit_refund');
            let err = document.getElementById('ref_error_so_tien');
            if (val > data.con_lai) {
                btn.disabled = true;
                err.classList.remove('d-none');
            } else {
                btn.disabled = false;
                err.classList.add('d-none');
            }
        };

        new bootstrap.Modal(document.getElementById('refundModal')).show();
    }
</script>
@endpush
