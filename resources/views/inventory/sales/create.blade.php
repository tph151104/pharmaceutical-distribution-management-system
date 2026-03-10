@extends('layouts.app')

@section('title', 'Lập phiếu xuất kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-light border me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="content-header-title mb-0">Lập phiếu xuất kho bán sỉ</h1>
            </div>
            <div class="text-muted small">
                Ghi nhận phiếu xuất kho bán sỉ cho khách hàng, theo dõi tồn kho theo lô và công nợ phải thu.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                Lưu tạm
            </button>
            <button class="btn btn-primary btn-sm">
                Lưu &amp; Hoàn tất
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <div class="fw-semibold small text-uppercase text-muted">
                        Thông tin chung
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Khách hàng</label>
                        <input type="text" class="form-control form-control-sm"
                               placeholder="Chọn khách hàng bán sỉ">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Ngày chứng từ</label>
                            <input type="date" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Số phiếu</label>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="Tự sinh hoặc nhập tay">
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Hình thức TT</label>
                            <select class="form-select form-select-sm">
                                <option>Tiền mặt</option>
                                <option>Chuyển khoản</option>
                                <option>Công nợ</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Chiết khấu hóa đơn (%)</label>
                            <input type="number" class="form-control form-control-sm text-end" value="0">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label small text-muted mb-1">Ghi chú</label>
                        <textarea class="form-control form-control-sm" rows="3"
                                  placeholder="Ghi chú thêm (nếu có)"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small text-uppercase text-muted">
                            Chi tiết hàng xuất
                        </div>
                        <div class="text-muted small">
                            Chọn sản phẩm và lô tồn kho để xuất. Hệ thống sẽ trừ tồn theo lô tương ứng.
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="btnAddRowSale">
                        <i class="bi bi-plus-circle me-1"></i>Thêm dòng
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0" id="salesItemsTable">
                            <thead class="table-light small text-muted">
                            <tr>
                                <th style="min-width: 170px;">Sản phẩm</th>
                                <th style="min-width: 110px;">Lô xuất</th>
                                <th style="min-width: 120px;">Hạn dùng</th>
                                <th style="width: 80px;" class="text-end">Tồn lô</th>
                                <th style="width: 80px;" class="text-end">SL xuất</th>
                                <th style="width: 100px;" class="text-end">Đơn giá</th>
                                <th style="width: 80px;" class="text-end">CK %</th>
                                <th style="width: 120px;" class="text-end">Thành tiền</th>
                                <th style="width: 40px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-muted small" id="emptySaleRow">
                                <td colspan="9" class="text-center py-3">
                                    Nhấn <strong>Thêm dòng</strong> để bắt đầu xuất hàng.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Tổng tiền hàng</span>
                                <span class="fw-semibold" id="saleSubtotal">₫ 0</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Chiết khấu</span>
                                <span class="fw-semibold" id="saleDiscount">₫ 0</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="fw-semibold">Tổng cộng</span>
                                <span class="fw-semibold text-primary" id="saleGrandTotal">₫ 0</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Khách thanh toán</span>
                                <span>₫ 0</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Công nợ phát sinh</span>
                                <span>₫ 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const itemsTableBody = document.querySelector('#salesItemsTable tbody');
            const btnAddRow = document.getElementById('btnAddRowSale');
            const emptyRow = document.getElementById('emptySaleRow');

            const subtotalEl = document.getElementById('saleSubtotal');
            const discountEl = document.getElementById('saleDiscount');
            const grandTotalEl = document.getElementById('saleGrandTotal');

            function formatCurrency(value) {
                return '₫ ' + Number(value || 0).toLocaleString('vi-VN');
            }

            function recalcTotals() {
                let subtotal = 0;
                let discountTotal = 0;

                itemsTableBody.querySelectorAll('tr').forEach(function (row) {
                    if (row.dataset.row !== 'sale-item') return;

                    const qty = parseFloat(row.querySelector('.sale-qty').value) || 0;
                    const price = parseFloat(row.querySelector('.sale-price').value) || 0;
                    const discount = parseFloat(row.querySelector('.sale-discount').value) || 0;

                    const lineSubtotal = qty * price;
                    const lineDiscount = lineSubtotal * discount / 100;
                    const lineTotal = lineSubtotal - lineDiscount;

                    subtotal += lineSubtotal;
                    discountTotal += lineDiscount;

                    row.querySelector('.sale-total').textContent = formatCurrency(lineTotal);
                });

                const grandTotal = subtotal - discountTotal;
                subtotalEl.textContent = formatCurrency(subtotal);
                discountEl.textContent = formatCurrency(discountTotal);
                grandTotalEl.textContent = formatCurrency(grandTotal);
            }

            function bindRowEvents(row) {
                ['sale-qty', 'sale-price', 'sale-discount'].forEach(function (cls) {
                    row.querySelector('.' + cls).addEventListener('input', recalcTotals);
                });

                row.querySelector('.btn-remove-sale-row').addEventListener('click', function () {
                    row.remove();
                    if (!itemsTableBody.querySelector('[data-row="sale-item"]') && emptyRow) {
                        emptyRow.style.display = '';
                    }
                    recalcTotals();
                });
            }

            function addRow() {
                if (emptyRow) {
                    emptyRow.style.display = 'none';
                }

                const tr = document.createElement('tr');
                tr.dataset.row = 'sale-item';
                tr.innerHTML = `
                    <td>
                        <input type="text" class="form-control form-control-sm" placeholder="Chọn sản phẩm">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" placeholder="Chọn lô tồn">
                    </td>
                    <td>
                        <input type="date" class="form-control form-control-sm">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-end" value="0" readonly>
                    </td>
                    <td>
                        <input type="number" min="0" step="1" class="form-control form-control-sm text-end sale-qty" value="0">
                    </td>
                    <td>
                        <input type="number" min="0" step="100" class="form-control form-control-sm text-end sale-price" value="0">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" step="1" class="form-control form-control-sm text-end sale-discount" value="0">
                    </td>
                    <td class="text-end">
                        <span class="sale-total small text-muted">₫ 0</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-remove-sale-row">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </td>
                `;

                itemsTableBody.appendChild(tr);
                bindRowEvents(tr);
                recalcTotals();
            }

            if (btnAddRow) {
                btnAddRow.addEventListener('click', addRow);
            }
        })();
    </script>
@endpush

