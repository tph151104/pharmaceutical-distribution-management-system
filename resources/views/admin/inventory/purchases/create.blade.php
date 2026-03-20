@extends('layouts.app')

@section('title', 'Lập phiếu nhập kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-light border me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="content-header-title mb-0">Lập phiếu nhập kho</h1>
            </div>
            <div class="text-muted small">
                Ghi nhận phiếu nhập hàng từ nhà cung cấp, kèm thông tin lô, hạn dùng và giá vốn.
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
                        <label class="form-label small text-muted mb-1">Nhà cung cấp</label>
                        <input type="text" class="form-control form-control-sm"
                               placeholder="Chọn nhà cung cấp">
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
                            Chi tiết hàng hóa
                        </div>
                        <div class="text-muted small">
                            Thêm các dòng hàng nhập theo từng lô, hạn dùng.
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">
                        <i class="bi bi-plus-circle me-1"></i>Thêm dòng
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0" id="itemsTable">
                            <thead class="table-light small text-muted">
                            <tr>
                                <th style="min-width: 180px;">Sản phẩm</th>
                                <th style="min-width: 110px;">Số lô</th>
                                <th style="min-width: 120px;">Hạn dùng</th>
                                <th style="width: 80px;" class="text-end">SL</th>
                                <th style="width: 100px;" class="text-end">Đơn giá</th>
                                <th style="width: 80px;" class="text-end">VAT %</th>
                                <th style="width: 120px;" class="text-end">Thành tiền</th>
                                <th style="width: 40px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-muted small" id="emptyRow">
                                <td colspan="8" class="text-center py-3">
                                    Nhấn <strong>Thêm dòng</strong> để bắt đầu nhập hàng.
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
                                <span class="fw-semibold" id="subtotal">₫ 0</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Tổng VAT</span>
                                <span class="fw-semibold" id="vatTotal">₫ 0</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="fw-semibold">Tổng cộng</span>
                                <span class="fw-semibold text-primary" id="grandTotal">₫ 0</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Thanh toán ngay</span>
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
            const itemsTableBody = document.querySelector('#itemsTable tbody');
            const btnAddRow = document.getElementById('btnAddRow');
            const emptyRow = document.getElementById('emptyRow');

            const subtotalEl = document.getElementById('subtotal');
            const vatTotalEl = document.getElementById('vatTotal');
            const grandTotalEl = document.getElementById('grandTotal');

            function formatCurrency(value) {
                return '₫ ' + Number(value || 0).toLocaleString('vi-VN');
            }

            function recalcTotals() {
                let subtotal = 0;
                let vatTotal = 0;

                itemsTableBody.querySelectorAll('tr').forEach(function (row) {
                    if (row.dataset.row !== 'item') return;

                    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                    const price = parseFloat(row.querySelector('.item-price').value) || 0;
                    const vat = parseFloat(row.querySelector('.item-vat').value) || 0;

                    const lineSubtotal = qty * price;
                    const lineVat = lineSubtotal * vat / 100;
                    const lineTotal = lineSubtotal + lineVat;

                    subtotal += lineSubtotal;
                    vatTotal += lineVat;

                    row.querySelector('.item-total').textContent = formatCurrency(lineTotal);
                });

                const grandTotal = subtotal + vatTotal;
                subtotalEl.textContent = formatCurrency(subtotal);
                vatTotalEl.textContent = formatCurrency(vatTotal);
                grandTotalEl.textContent = formatCurrency(grandTotal);
            }

            function bindRowEvents(row) {
                ['item-qty', 'item-price', 'item-vat'].forEach(function (cls) {
                    row.querySelector('.' + cls).addEventListener('input', recalcTotals);
                });

                row.querySelector('.btn-remove-row').addEventListener('click', function () {
                    row.remove();
                    if (!itemsTableBody.querySelector('[data-row="item"]') && emptyRow) {
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
                tr.dataset.row = 'item';
                tr.innerHTML = `
                    <td>
                        <input type="text" class="form-control form-control-sm" placeholder="Chọn sản phẩm">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" placeholder="Số lô">
                    </td>
                    <td>
                        <input type="date" class="form-control form-control-sm">
                    </td>
                    <td>
                        <input type="number" min="0" step="1" class="form-control form-control-sm text-end item-qty" value="0">
                    </td>
                    <td>
                        <input type="number" min="0" step="100" class="form-control form-control-sm text-end item-price" value="0">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" step="1" class="form-control form-control-sm text-end item-vat" value="8">
                    </td>
                    <td class="text-end">
                        <span class="item-total small text-muted">₫ 0</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-remove-row">
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

