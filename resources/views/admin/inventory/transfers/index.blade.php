@extends('layouts.app')

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
        <div class="card-header bg-light py-3">
            <form action="{{ route('transfers.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1">Tìm Số lô / Tên thuốc</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Từ khoá...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small mb-1">Khu vực kho</label>
                    <select name="khu_vuc" class="form-select">
                        <option value="">-- Tất cả khu vực --</option>
                        @foreach($khuVucs as $kv)
                            <option value="{{ $kv->ma_khu_vuc }}" {{ request('khu_vuc') == $kv->ma_khu_vuc ? 'selected' : '' }}>{{ $kv->ten_khu_vuc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-funnel me-1"></i> Lọc kết quả
                    </button>
                    @if(request()->has('search') || request()->has('khu_vuc'))
                    <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    @endif
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
                                <button onclick="openTransferModal({{ $item->id }}, '{{ $item->thuoc ? $item->thuoc->ten_thuoc : 'N/A' }}', '{{ $item->so_lo }}', '{{ $item->khuVuc->ten_khu_vuc }}', {{ $item->so_luong }})" 
                                    class="btn btn-sm btn-primary d-inline-flex align-items-center shadow-sm">
                                    <i class="bi bi-arrow-left-right me-1"></i> Luân Chuyển
                                </button>
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

<!-- Modal Bootstrap 5 -->
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
                <div class="modal-body bg-light">
                    <!-- Detail info -->
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

                    <!-- Form inputs -->
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
    
    document.addEventListener("DOMContentLoaded", function() {
        transferModal = new bootstrap.Modal(document.getElementById('transferModal'));
    });

    function openTransferModal(id, tenThuoc, soLo, tuKhuVuc, maxQty) {
        document.getElementById('modal_id_ton_kho_khu_vuc').value = id;
        document.getElementById('modal_ten_thuoc').textContent = tenThuoc;
        document.getElementById('modal_so_lo').textContent = soLo;
        document.getElementById('modal_tu_khu_vuc').textContent = tuKhuVuc;
        document.getElementById('modal_max_qty').textContent = maxQty;
        document.getElementById('modal_hint_max').textContent = maxQty;
        
        const inputSL = document.getElementById('modal_so_luong_chuyen');
        inputSL.max = maxQty;
        inputSL.value = maxQty; // Default to max
        
        if (transferModal) {
            transferModal.show();
        }
    }
</script>
@endpush
@endsection
