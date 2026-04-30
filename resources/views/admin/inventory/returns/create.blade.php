@extends('layouts.app')

@section('title', 'Tạo đơn trả hàng (NV)')

@section('content-header')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.returns.index') }}" class="btn btn-sm btn-light border me-2"><i class="bi bi-arrow-left"></i> Trở về</a>
        <h1 class="content-header-title mb-0"><i class="bi bi-arrow-return-left text-warning me-2"></i>Tạo đơn trả hàng thay khách hàng</h1>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.returns.store') }}" method="POST" enctype="multipart/form-data" id="returnForm">
        @csrf
        <div class="row g-4">
            {{-- Panel trái: Chọn đơn hàng --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-search me-2"></i>Chọn đơn hàng</h5>
                        <p class="text-muted small mb-0">Chỉ hiển thị đơn hàng đã hoàn thành và chưa có yêu cầu trả hàng</p>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <select name="ma_don_hang" id="selectDonHang" class="form-select form-select-lg" required>
                                <option value="">-- Chọn đơn hàng --</option>
                                @foreach($donHangs as $dh)
                                    <option value="{{ $dh->ma_don_hang }}" {{ old('ma_don_hang') == $dh->ma_don_hang ? 'selected' : '' }}>
                                        {{ $dh->ma_don_hang }} — KH: {{ $dh->khachHang->ten_kh ?? 'N/A' }}
                                        ({{ $dh->ngay_dat->format('d/m/Y') }} — {{ number_format($dh->tong_tien) }}đ)
                                    </option>
                                @endforeach
                            </select>
                            @if($donHangs->isEmpty())
                                <div class="form-text text-danger mt-2">
                                    <i class="bi bi-exclamation-triangle"></i> Hiện không có đơn hàng nào đủ điều kiện (đã hoàn thành và chưa có yêu cầu trả).
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Chi tiết sản phẩm (AJAX load) --}}
                <div class="card border-0 shadow-sm mt-3" id="orderDetailCard" style="display: none;">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Sản phẩm trong đơn hàng</h6>
                        <span class="badge bg-primary" id="orderBadge"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-3">Sản phẩm</th>
                                    <th class="text-center">SL Đã mua</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center" style="width: 140px;">SL Trả <span class="text-danger">*</span></th>
                                    <th>Lý do chi tiết</th>
                                    <th class="text-end pe-3">Tiền hoàn</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsBody">
                                {{-- AJAX rendered --}}
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold ps-3">Tổng tiền hoàn trả dự kiến:</td>
                                    <td class="text-end pe-3 text-danger fw-bold fs-5" id="totalRefund">0đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Panel phải: Thông tin & Submit --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Thông tin khách hàng</h6></div>
                    <div class="card-body" id="customerInfo">
                        <p class="text-muted fst-italic mb-0">Chọn đơn hàng để xem thông tin khách hàng.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="bi bi-chat-quote me-2"></i>Lý do trả hàng</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do chung <span class="text-danger">*</span></label>
                            <textarea name="ly_do_chung" class="form-control" rows="3" required placeholder="Nhập lý do trả hàng chung cho đơn này...">{{ old('ly_do_chung') }}</textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Ảnh minh chứng (Tùy chọn)</label>
                            <input type="file" name="minh_chung_image" class="form-control" accept="image/*">
                            <div class="form-text">Ảnh chụp sản phẩm lỗi, hư hỏng (nếu có)</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm border-top border-warning border-3">
                    <div class="card-body">
                        <div class="alert alert-warning small mb-3 py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Đơn trả hàng sẽ ở trạng thái <strong>Chờ duyệt</strong>. Cần được phê duyệt bởi quản lý.
                        </div>
                        <div class="d-flex align-items-center text-muted small mb-3">
                            <i class="bi bi-person-badge me-2"></i>
                            Người tạo: <strong class="ms-1">{{ auth()->user()->ho_ten_nd }}</strong>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2" id="btnSubmit" disabled>
                            <i class="bi bi-arrow-return-left me-1"></i> Tạo Yêu Cầu Trả Hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectDonHang = document.getElementById('selectDonHang');
    const orderDetailCard = document.getElementById('orderDetailCard');
    const orderItemsBody = document.getElementById('orderItemsBody');
    const totalRefundEl = document.getElementById('totalRefund');
    const orderBadge = document.getElementById('orderBadge');
    const customerInfo = document.getElementById('customerInfo');
    const btnSubmit = document.getElementById('btnSubmit');
    const fmt = new Intl.NumberFormat('vi-VN');

    selectDonHang.addEventListener('change', function () {
        const maDH = this.value;
        if (!maDH) {
            orderDetailCard.style.display = 'none';
            customerInfo.innerHTML = '<p class="text-muted fst-italic mb-0">Chọn đơn hàng để xem thông tin khách hàng.</p>';
            btnSubmit.disabled = true;
            return;
        }

        fetch(`{{ url('admin/returns/order-items') }}/${maDH}`)
            .then(res => res.json())
            .then(data => {
                orderBadge.textContent = data.ma_don_hang;
                customerInfo.innerHTML = `
                    <p class="mb-1"><strong>Khách hàng:</strong> ${data.ten_kh}</p>
                    <p class="mb-1"><strong>Mã KH:</strong> ${data.ma_kh}</p>
                    <p class="mb-0"><strong>Tổng đơn gốc:</strong> <span class="text-primary fw-bold">${fmt.format(data.tong_tien)}đ</span></p>
                `;

                let html = '';
                data.items.forEach((item, idx) => {
                    html += `
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold">${item.ten_thuoc}</div>
                                <div class="small text-muted">${item.ma_thuoc} • ${item.don_vi_tinh}</div>
                                <input type="hidden" name="items[${idx}][ma_thuoc]" value="${item.ma_thuoc}">
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">${item.so_luong_mua}</span>
                                ${item.so_luong_da_tra > 0 ? `<br><small class="text-warning">Đã trả: ${item.so_luong_da_tra}</small>` : ''}
                            </td>
                            <td class="text-end">${fmt.format(item.don_gia)}đ</td>
                            <td class="text-center">
                                <input type="number" name="items[${idx}][so_luong_tra]" 
                                       class="form-control form-control-sm text-center qty-input"
                                       min="0" max="${item.so_luong_co_the_tra}" value="0" 
                                       data-price="${item.don_gia}" data-max="${item.so_luong_co_the_tra}"
                                       ${item.so_luong_co_the_tra === 0 ? 'disabled' : ''}>
                                <div class="form-text small text-muted">Tối đa: ${item.so_luong_co_the_tra}</div>
                            </td>
                            <td>
                                <input type="text" name="items[${idx}][ly_do]" class="form-control form-control-sm" placeholder="Lý do...">
                            </td>
                            <td class="text-end pe-3 fw-bold text-primary refund-cell">0đ</td>
                        </tr>
                    `;
                });

                orderItemsBody.innerHTML = html;
                orderDetailCard.style.display = 'block';
                btnSubmit.disabled = false;

                // Bind qty change events
                document.querySelectorAll('.qty-input').forEach(input => {
                    input.addEventListener('input', recalculate);
                });
                recalculate();
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi khi tải chi tiết đơn hàng.');
            });
    });

    function recalculate() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
            const row = input.closest('tr');
            const qty = Math.min(parseInt(input.value) || 0, parseInt(input.dataset.max));
            const price = parseFloat(input.dataset.price);
            const refund = qty * price;
            total += refund;
            row.querySelector('.refund-cell').textContent = fmt.format(refund) + 'đ';
            
            // Enforce max
            if (parseInt(input.value) > parseInt(input.dataset.max)) {
                input.value = input.dataset.max;
            }
        });
        totalRefundEl.textContent = fmt.format(total) + 'đ';
    }

    // Auto-load if old value exists
    if (selectDonHang.value) {
        selectDonHang.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
