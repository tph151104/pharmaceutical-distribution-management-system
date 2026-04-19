@extends('layouts.app')

@section('title', 'Lập phiếu xuất kho mới')

@section('content-header')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-light border me-2"><i class="bi bi-arrow-left"></i> Trở về</a>
        <h1 class="content-header-title mb-0"><i class="bi bi-box-arrow-up text-primary me-2"></i>Lập phiếu xuất kho mới</h1>
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

    <div class="row g-4">
        {{-- Panel trái: Tìm kiếm & Danh sách đơn hàng --}}
        <div class="col-lg-8">
            {{-- Bộ lọc tìm kiếm --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-funnel me-2"></i>Tìm kiếm đơn hàng đã duyệt</h5>
                    <p class="text-muted small mb-0">Hệ thống chỉ hiển thị các đơn hàng đã được duyệt (chờ xuất kho)</p>
                </div>
                <div class="card-body pb-3">
                    <form action="{{ route('sales.create') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Tìm kiếm</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Mã đơn, tên KH..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Khách hàng</label>
                            <select name="ma_kh" class="form-select">
                                <option value="">-- Tất cả --</option>
                                @foreach($khachHangs as $kh)
                                    <option value="{{ $kh->ma_kh }}" {{ request('ma_kh') == $kh->ma_kh ? 'selected' : '' }}>
                                        {{ $kh->ten_kh }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Đến ngày</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search me-1"></i>Lọc</button>
                            @if(request()->hasAny(['search', 'ma_kh', 'from_date', 'to_date']))
                                <a href="{{ route('sales.create') }}" class="btn btn-light"><i class="bi bi-x-circle"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danh sách đơn hàng dạng bảng --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Đơn hàng chờ xuất kho</h6>
                    <span class="badge bg-primary">{{ $donHangs->count() }} đơn</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Mã Đơn Hàng</th>
                                <th>Khách Hàng</th>
                                <th>Ngày Đặt</th>
                                <th>Người Duyệt</th>
                                <th class="text-end">Tổng Tiền</th>
                                <th class="text-end">SL SP</th>
                                <th class="text-center pe-3">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donHangs as $dh)
                                <tr id="row-{{ $dh->ma_don_hang }}" class="order-row" style="cursor: pointer;" 
                                    onclick="previewOrder('{{ $dh->ma_don_hang }}', this)">
                                    <td class="ps-3">
                                        <div class="fw-bold text-primary">{{ $dh->ma_don_hang }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $dh->khachHang->ten_kh ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $dh->khachHang->so_dien_thoai ?? '' }}</div>
                                    </td>
                                    <td class="small">{{ $dh->ngay_dat->format('d/m/Y') }}</td>
                                    <td class="small">{{ $dh->nguoiDuyet->ho_ten_nd ?? '-' }}</td>
                                    <td class="text-end fw-bold">{{ number_format($dh->tong_tien) }}đ</td>
                                    <td class="text-end">
                                        <span class="badge bg-secondary">{{ $dh->chiTiet->count() }}</span>
                                    </td>
                                    <td class="text-center pe-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="event.stopPropagation(); previewOrder('{{ $dh->ma_don_hang }}', document.getElementById('row-{{ $dh->ma_don_hang }}'))">
                                            <i class="bi bi-eye me-1"></i>Xem
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Không có đơn hàng nào đang chờ xuất kho.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Panel phải: Preview & Tạo phiếu --}}
        <div class="col-lg-4">
            {{-- Preview sản phẩm --}}
            <div class="card border-0 shadow-sm mb-3" id="previewCard" style="display: none;">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-eye me-2"></i>Chi tiết đơn hàng <span id="previewOrderId" class="text-dark"></span></h6>
                </div>
                <div class="card-body pb-2">
                    <div class="mb-2 small">
                        <strong>Khách hàng:</strong> <span id="previewCustomer">-</span>
                    </div>
                    <div class="mb-2 small">
                        <strong>Ngày đặt:</strong> <span id="previewDate">-</span>
                    </div>
                    <div class="mb-3 small">
                        <strong>Người duyệt:</strong> <span id="previewApprover" class="text-success">-</span>
                    </div>
                </div>
                {{-- Danh sách sản phẩm --}}
                <div class="table-responsive">
                    <table class="table table-sm mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Sản phẩm</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end pe-3">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="previewItems">
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-3">Tổng:</td>
                                <td class="text-end pe-3 fw-bold text-primary" id="previewTotal">0đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Form tạo phiếu --}}
            <div class="card border-0 shadow-sm border-top border-primary border-3" id="createCard" style="display: none;">
                <div class="card-body">
                    <form action="{{ route('sales.store') }}" method="POST" id="createForm">
                        @csrf
                        <input type="hidden" name="ma_don_hang" id="selectedOrderId" value="">
                        
                        <div class="alert alert-info border-info-subtle bg-info-subtle py-2 small">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Hệ thống sẽ tạo Phiếu xuất dự thảo và tự động phân bổ Lô cận Date (FEFO).
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                            <i class="bi bi-arrow-right-circle me-1"></i> Tạo Phiếu Xuất & Phân Bổ Lô
                        </button>
                    </form>
                </div>
            </div>

            {{-- Placeholder --}}
            <div class="card border-0 shadow-sm" id="placeholderCard">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-hand-index fs-1 d-block mb-3 text-primary opacity-50"></i>
                    <h6 class="fw-bold">Chọn đơn hàng để xem chi tiết</h6>
                    <p class="small mb-0">Click vào một đơn hàng từ danh sách bên trái để xem sản phẩm và tạo phiếu xuất.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const fmt = new Intl.NumberFormat('vi-VN');
let currentSelectedRow = null;

function previewOrder(maDH, rowEl) {
    // Highlight row
    document.querySelectorAll('.order-row').forEach(r => r.classList.remove('table-active'));
    if (rowEl) rowEl.classList.add('table-active');
    currentSelectedRow = rowEl;

    fetch(`{{ url('sales/order-detail') }}/${maDH}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('previewOrderId').textContent = data.ma_don_hang;
            document.getElementById('previewCustomer').textContent = data.ten_kh;
            document.getElementById('previewDate').textContent = data.ngay_dat;
            document.getElementById('previewApprover').textContent = data.nguoi_duyet;
            document.getElementById('previewTotal').textContent = fmt.format(data.tong_tien) + 'đ';
            document.getElementById('selectedOrderId').value = data.ma_don_hang;

            let html = '';
            data.items.forEach(item => {
                html += `
                    <tr>
                        <td class="ps-3">
                            <div class="fw-medium">${item.ten_thuoc}</div>
                            <div class="text-muted" style="font-size:11px">${item.ma_thuoc} • ${item.don_vi_tinh}</div>
                        </td>
                        <td class="text-center fw-bold">${item.so_luong}</td>
                        <td class="text-end">${fmt.format(item.don_gia)}đ</td>
                        <td class="text-end pe-3 fw-bold">${fmt.format(item.thanh_tien)}đ</td>
                    </tr>`;
            });
            document.getElementById('previewItems').innerHTML = html;

            document.getElementById('previewCard').style.display = 'block';
            document.getElementById('createCard').style.display = 'block';
            document.getElementById('placeholderCard').style.display = 'none';
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi khi tải chi tiết đơn hàng.');
        });
}

// Auto-select if order_id provided
@if($selectedOrderId)
document.addEventListener('DOMContentLoaded', function() {
    const row = document.getElementById('row-{{ $selectedOrderId }}');
    if (row) previewOrder('{{ $selectedOrderId }}', row);
});
@endif
</script>
@endpush
