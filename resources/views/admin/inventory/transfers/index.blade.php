@extends('layouts.app')

<?php use App\Models\TonKhoKhuVuc; use App\Models\LichSuDichChuyenKho; ?>

@section('title', 'Quản lý Luân chuyển Kho')

@section('content')
<div class="container-fluid content-padding">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold"><i class="bi bi-arrow-left-right me-2"></i>Quản lý luân chuyển giữa các kho</h5>
            <p class="text-muted mb-0">Quản lý việc vận chuyển lô hàng vào các vị trí kho</p>
        </div>
        <a href="{{ route('transfers.history') }}" class="btn btn-outline-primary d-inline-flex align-items-center">
            <i class="bi bi-clock-history me-1"></i> Xem lịch sử luân chuyển
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-start border-4 border-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-start border-4 border-danger" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <!-- Filter Bar -->
        <div class="card-header bg-light py-3 border-bottom-0">
            <form action="{{ route('transfers.index') }}" method="GET" class="row g-3">
                <!-- Row 1: Search, PN, Area -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1"><i class="bi bi-search me-1"></i>Tìm Số lô / Tên thuốc</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nhập từ khóa hoặc số lô...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1"><i class="bi bi-hash me-1"></i>Mã phiếu nhập</label>
                    <input type="text" name="ma_phieu_nhap" value="{{ request('ma_phieu_nhap') }}" class="form-control" placeholder="Ví dụ: PN_2024...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1"><i class="bi bi-geo-alt me-1"></i>Khu vực kho</label>
                    <select name="khu_vuc" class="form-select">
                        <option value="">-- Tất cả khu vực --</option>
                        @foreach($khuVucs as $kv)
                            <option value="{{ $kv->ma_khu_vuc }}" {{ request('khu_vuc') == $kv->ma_khu_vuc ? 'selected' : '' }}>{{ $kv->ten_khu_vuc }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 2: Date Range and Buttons -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small mb-1">Từ ngày (Ngày nhập)</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small mb-1">Đến ngày</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                    <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary px-4">
                        Xóa lọc
                    </a>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center">
                        <i class="bi bi-funnel me-1"></i> Lọc kết quả
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 text-secondary" style="min-width: 250px;">Thông tin Thuốc</th>
                        <th class="py-3 text-secondary">Mã phiếu nhập / Số Lô / HSD</th>
                        <th class="py-3 text-secondary">Khu vực hiện tại</th>
                        <th class="py-3 text-center text-secondary">Số lượng tồn</th>
                        <th class="py-3 text-end text-secondary">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center p-1 border" style="width: 48px; height: 48px;">
                                        @if($item->thuoc && $item->thuoc->image1)
                                            <img class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" src="{{ asset($item->thuoc->image1) }}" alt="">
                                        @elseif($item->tonKho && $item->tonKho->image1)
                                            <img class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" src="{{ asset($item->tonKho->image1) }}" alt="">
                                        @else
                                            <i class="bi bi-image text-secondary fs-4"></i>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-bold text-primary mb-1">
                                            {{ $item->thuoc->ten_thuoc ?? 'N/A' }}
                                        </div>
                                        <div class="small text-muted">Mã: {{ $item->ma_thuoc }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark mb-1">Mã phiếu nhập: {{ $item->ma_phieu_nhap }}</div>
                                <div class="small text-muted">Số lô: {{ $item->so_lo }}</div>
                                <div class="small text-muted">Ngày nhập: {{ $item->phieuNhap ? ($item->phieuNhap->ngay_nhap ? \Carbon\Carbon::parse($item->phieuNhap->ngay_nhap)->format('d/m/Y') : 'N/A') : 'N/A' }}</div>
                                <div class="small text-muted">
                                    HSD: {{ ($item->tonKho && $item->tonKho->han_su_dung) ? $item->tonKho->han_su_dung->format('d/m/Y') : 'N/A' }}
                                    @if($item->tonKho && $item->tonKho->han_su_dung && $item->tonKho->han_su_dung < now())
                                        <span class="text-danger fw-bold ms-1">(Hết hạn)</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark rounded-pill px-3 py-2 border border-info-subtle">
                                    {{ $item->khuVuc->ten_khu_vuc ?? $item->ma_khu_vuc ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center fw-bold text-dark fs-5">
                                {{ number_format($item->so_luong) }}
                            </td>
                            <td class="text-end">
                                @php
                                    $detailLocations = TonKhoKhuVuc::with('khuVuc')
                                        ->where('ma_thuoc', $item->ma_thuoc)
                                        ->where('ma_phieu_nhap', $item->ma_phieu_nhap)
                                        ->where('so_lo', $item->so_lo)
                                        ->get();
                                    $detailHistory = LichSuDichChuyenKho::with(['tuKhuVucKho', 'denKhuVucKho', 'nguoiThucHien'])
                                        ->where('ma_thuoc', $item->ma_thuoc)
                                        ->where('so_lo', $item->so_lo)
                                        ->orderBy('ngay_chuyen', 'desc')
                                        ->limit(10)
                                        ->get();
                                @endphp
                                <button type="button" class="btn btn-sm btn-outline-info d-inline-flex align-items-center me-1"
                                    data-thuoc="{{ $item->thuoc->ten_thuoc ?? 'N/A' }}"
                                    data-ma-thuoc="{{ $item->ma_thuoc }}"
                                    data-so-lo="{{ $item->so_lo }}"
                                    data-ma-pn="{{ $item->ma_phieu_nhap }}"
                                    data-khu-vuc="{{ $item->khuVuc->ten_khu_vuc ?? $item->ma_khu_vuc }}"
                                    data-so-luong="{{ $item->so_luong }}"
                                    data-hsd="{{ ($item->tonKho && $item->tonKho->han_su_dung) ? $item->tonKho->han_su_dung->format('d/m/Y') : 'N/A' }}" 
                                    data-trang-thai="{{ $item->tonKho->trang_thai_lo ?? 'N/A' }}"
                                    data-locations="{{ json_encode($detailLocations->map(fn($l) => ['kv' => $l->khuVuc->ten_khu_vuc ?? $l->ma_khu_vuc, 'sl' => $l->so_luong])->toArray()) }}"
                                    data-history="{{ json_encode($detailHistory->map(fn($h) => ['ngay' => $h->ngay_chuyen ? $h->ngay_chuyen->format('d/m/Y H:i') : '', 'tu' => $h->tu_khu_vuc ? ($h->tuKhuVucKho->ten_khu_vuc ?? $h->tu_khu_vuc) : 'Nhận hàng', 'den' => $h->den_khu_vuc ? ($h->denKhuVucKho->ten_khu_vuc ?? $h->den_khu_vuc) : 'Xuất kho', 'sl' => $h->so_luong_chuyen, 'ly_do' => $h->ly_do_chuyen ?? '', 'nguoi' => $h->nguoiThucHien->ho_ten_nd ?? $h->nguoi_thuc_hien])->toArray()) }}"
                                    onclick="openDetailModal(this)">
                                    <i class="bi bi-eye me-1"></i> Chi tiết
                                </button>
                                @if($item->ma_khu_vuc !== 'KV05_LOAI_BO')
                                <button onclick="openTransferModal({{ $item->id }}, '{{ $item->thuoc ? $item->thuoc->ten_thuoc : 'N/A' }}', '{{ $item->so_lo }}', '{{ $item->khuVuc->ten_khu_vuc }}', {{ $item->so_luong }}, '{{ $item->ma_khu_vuc }}')" 
                                    class="btn btn-sm btn-primary d-inline-flex align-items-center shadow-sm">
                                    <i class="bi bi-arrow-left-right me-1"></i> Luân Chuyển
                                </button>
                                @if($item->ma_khu_vuc === 'KV04_CHO_XU_LY')
                                <a href="{{ route('supplier-returns.create', ['ma_thuoc' => $item->ma_thuoc, 'so_lo' => $item->so_lo, 'ma_phieu_nhap' => $item->ma_phieu_nhap]) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center shadow-sm ms-1">
                                    <i class="bi bi-box-arrow-right me-1"></i> Trả NCC
                                </a>
                                @endif
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <i class="bi bi-lock me-1"></i>Đã loại bỏ
                                </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                                <p class="mb-0 fw-medium">Không tìm thấy dữ liệu tồn kho nào thoả điều kiện.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($transfers->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $transfers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Chi Tiết -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Chi tiết lô hàng tại kho</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Thông tin chung -->
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Sản phẩm</div>
                        <div class="fw-bold text-primary" id="detail_thuoc"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Số lô</div>
                        <div class="fw-semibold" id="detail_so_lo"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">HSD</div>
                        <div class="fw-semibold" id="detail_hsd"></div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <div class="small text-muted">Mã phiếu nhập</div>
                        <div class="fw-semibold" id="detail_ma_pn"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Khu vực hiện tại</div>
                        <span class="badge bg-info text-dark" id="detail_khu_vuc"></span>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Trạng thái lô</div>
                        <span class="badge" id="detail_trang_thai"></span>
                    </div>
                </div>

                <!-- Phân bổ khu vực -->
                <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-geo-alt me-1"></i>Phân bổ theo khu vực</h6>
                <table class="table table-sm table-bordered mb-3">
                    <thead class="table-light">
                        <tr><th>Khu vực</th><th class="text-end" style="width:100px;">Số lượng</th></tr>
                    </thead>
                    <tbody id="detail_locations_body"></tbody>
                </table>

                <!-- Lịch sử luân chuyển gần đây -->
                <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-clock-history me-1"></i>Lịch sử luân chuyển gần đây</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Thời gian</th>
                                <th>Hành trình</th>
                                <th class="text-center" style="width:70px;">SL</th>
                                <th>Người thực hiện</th>
                                <th>Lý do</th>
                            </tr>
                        </thead>
                        <tbody id="detail_history_body"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Luân Chuyển -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="transferModalLabel">
                    <i class="bi bi-arrow-left-right me-2"></i> Thực hiện Luân chuyển
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transfers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_ton_kho_khu_vuc" id="modal_id_ton_kho_khu_vuc">
                <input type="hidden" id="modal_ma_khu_vuc" value="">
                <div class="modal-body bg-light">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                <span class="text-muted fw-bold me-2" style="width: 70px;">Thuốc:</span>
                                <span id="modal_ten_thuoc" class="fw-bold text-dark"></span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="text-muted fw-bold me-2" style="width: 70px;">Số Lô:</span>
                                <span id="modal_so_lo" class="fw-bold text-dark"></span>
                            </div>
                            <div class="row g-2 text-center text-sm">
                                <div class="col-6">
                                    <div class="p-2 border rounded bg-white mt-1">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Từ Khu Vực</div>
                                        <span id="modal_tu_khu_vuc" class="badge bg-secondary"></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 border rounded bg-white mt-1">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Tồn Hiện Tại</div>
                                        <span class="fw-bold text-primary fs-5" id="modal_max_qty"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Chuyển đến Khu vực đích <span class="text-danger">*</span></label>
                        <select name="den_khu_vuc" required class="form-select border-secondary-subtle">
                            <option value="">-- Chọn khu vực --</option>
                            @foreach($khuVucs as $kv)
                                <option value="{{ $kv->ma_khu_vuc }}">{{ $kv->ten_khu_vuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Số lượng chuyển <span class="text-danger">*</span></label>
                        <input type="number" name="so_luong_chuyen" id="modal_so_luong_chuyen" min="1" required class="form-control border-secondary-subtle">
                        <div class="form-text">Tối đa: <strong id="modal_hint_max" class="text-primary">0</strong></div>
                    </div>
                    <div class="alert alert-warning small mb-3" id="lyDoWarning" style="display: none;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>Bắt buộc nhập lý do!</strong> Khi chuyển hàng từ <em>Kho Chờ xử lý</em> trở lại <em>Kho Thành phẩm</em>, bạn phải ghi rõ lý do.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Lý do luân chuyển (Tuỳ chọn)</label>
                        <textarea name="ly_do" rows="2" class="form-control border-secondary-subtle" placeholder="Ghi chú thêm..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Xác nhận chuyển
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let transferModal;
    // Ma trận chuyển kho hợp lệ từ backend
    const allowedTransfers = @json($allowedTransfers);
    
    document.addEventListener("DOMContentLoaded", function() {
        transferModal = new bootstrap.Modal(document.getElementById('transferModal'));

        // Lắng nghe thay đổi dropdown khu vực đích để hiện cảnh báo KV04→KV03
        const denKhuVucSelect = document.querySelector('select[name="den_khu_vuc"]');
        const lyDoTextarea = document.querySelector('textarea[name="ly_do"]');
        const lyDoWarning = document.getElementById('lyDoWarning');

        if (denKhuVucSelect) {
            denKhuVucSelect.addEventListener('change', function() {
                const currentSource = document.getElementById('modal_ma_khu_vuc').value;
                if (currentSource === 'KV04_CHO_XU_LY' && this.value === 'KV03_THANH_PHAM') {
                    lyDoWarning.style.display = 'block';
                    lyDoTextarea.required = true;
                } else {
                    lyDoWarning.style.display = 'none';
                    lyDoTextarea.required = false;
                }
            });
        }
    });

    function openTransferModal(id, tenThuoc, soLo, tuKhuVuc, maxQty, maKhuVuc) {
        document.getElementById('modal_id_ton_kho_khu_vuc').value = id;
        document.getElementById('modal_ma_khu_vuc').value = maKhuVuc;
        document.getElementById('modal_ten_thuoc').textContent = tenThuoc;
        document.getElementById('modal_so_lo').textContent = soLo;
        document.getElementById('modal_tu_khu_vuc').textContent = tuKhuVuc;
        document.getElementById('modal_max_qty').textContent = maxQty;
        document.getElementById('modal_hint_max').textContent = maxQty;
        
        const inputSL = document.getElementById('modal_so_luong_chuyen');
        inputSL.max = maxQty;
        inputSL.value = maxQty;

        // Lọc dropdown khu vực đích theo ma trận GSP
        const denKhuVucSelect = document.querySelector('select[name="den_khu_vuc"]');
        const allowed = allowedTransfers[maKhuVuc] || [];
        
        Array.from(denKhuVucSelect.options).forEach(opt => {
            if (opt.value === '') {
                opt.style.display = ''; // Luôn hiện option rỗng
            } else {
                opt.style.display = allowed.includes(opt.value) ? '' : 'none';
            }
        });
        denKhuVucSelect.value = ''; // Reset selection

        // Reset cảnh báo lý do
        document.getElementById('lyDoWarning').style.display = 'none';
        document.querySelector('textarea[name="ly_do"]').required = false;
        
        if (transferModal) {
            transferModal.show();
        }
    }

    function openDetailModal(btn) {
        document.getElementById('detail_thuoc').textContent = btn.dataset.thuoc;
        document.getElementById('detail_so_lo').textContent = btn.dataset.soLo;
        document.getElementById('detail_hsd').textContent = btn.dataset.hsd;
        document.getElementById('detail_ma_pn').textContent = btn.dataset.maPn;
        document.getElementById('detail_khu_vuc').textContent = btn.dataset.khuVuc;

        // Trạng thái lô
        const ttBadge = document.getElementById('detail_trang_thai');
        const tt = btn.dataset.trangThai;
        const ttMap = {
            'dang_ban': ['Đang bán', 'bg-success'],
            'cho_duyet': ['Chờ duyệt', 'bg-secondary'],
            'ngung_ban': ['Ngưng bán', 'bg-warning text-dark'],
            'het_han': ['Hết hạn', 'bg-danger']
        };
        ttBadge.textContent = (ttMap[tt] || [tt, 'bg-light text-dark'])[0];
        ttBadge.className = 'badge ' + (ttMap[tt] || [tt, 'bg-light text-dark'])[1];

        // Phân bổ khu vực
        let locations = [];
        try { locations = JSON.parse(btn.dataset.locations || '[]'); } catch(e) {}
        const locBody = document.getElementById('detail_locations_body');
        locBody.innerHTML = '';
        if (locations.length > 0) {
            locations.forEach(l => {
                locBody.innerHTML += `<tr><td><span class="badge bg-light text-dark border"><i class="bi bi-box me-1"></i>${l.kv}</span></td><td class="text-end fw-bold">${l.sl}</td></tr>`;
            });
        } else {
            locBody.innerHTML = '<tr><td colspan="2" class="text-center text-muted small py-2">Không có dữ liệu</td></tr>';
        }

        // Lịch sử luân chuyển
        let history = [];
        try { history = JSON.parse(btn.dataset.history || '[]'); } catch(e) {}
        const hisBody = document.getElementById('detail_history_body');
        hisBody.innerHTML = '';
        if (history.length > 0) {
            history.forEach(h => {
                hisBody.innerHTML += `<tr>
                    <td class="text-nowrap small">${h.ngay}</td>
                    <td><span class="badge bg-secondary">${h.tu}</span> <i class="bi bi-arrow-right text-muted mx-1"></i> <span class="badge bg-info text-dark">${h.den}</span></td>
                    <td class="text-center fw-bold text-success">+${h.sl}</td>
                    <td class="small">${h.nguoi}</td>
                    <td class="small text-muted">${h.ly_do || '-'}</td>
                </tr>`;
            });
        } else {
            hisBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted small py-2">Chưa có lịch sử luân chuyển</td></tr>';
        }

        new bootstrap.Modal(document.getElementById('detailModal')).show();
    }
</script>
@endpush
@endsection

