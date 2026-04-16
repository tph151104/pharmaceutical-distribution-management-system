@extends('layouts.app')

@section('title', 'Tạo Đơn Hàng Mới')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-cart-plus text-primary me-2"></i>Tạo Đơn Hàng Mới</h1>
            <p class="text-muted small mb-0 mt-1">Dành cho nhân viên bán hàng tiếp nhận yêu cầu từ khách qua điện thoại/tin nhắn</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>
@endsection

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.orders.store') }}" method="POST" id="formCreateOrder">
                    @csrf
                    <!-- Chọn Khách hàng -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Khách hàng <span class="text-danger">*</span></label>
                        <select name="ma_kh" class="form-select select2" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($khachHangs as $kh)
                                <option value="{{ $kh->ma_kh }}">{{ $kh->ten_kh }} ({{ $kh->dien_thoai }} - {{ $kh->ma_kh }})</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-list-check me-2"></i>Chi tiết đơn hàng</h5>
                    
                     <!-- Form thêm sản phẩm (không submit) -->
                     <div class="row g-2 align-items-end mb-3 bg-light p-3 rounded border">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Chọn Thuốc <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="ten_thuoc_hien_thi" class="form-control bg-white" placeholder="-- Bấm kính lúp để chọn --" readonly>
                                <input type="hidden" id="ma_thuoc_an">
                                <input type="hidden" id="gia_thuoc_an">
                                <input type="hidden" id="ton_kho_an">
                                <button type="button" class="btn btn-outline-primary" id="btn-open-modal" 
                                        data-bs-toggle="modal" data-bs-target="#modalAdvancedSearch" title="Tìm kiếm nâng cao">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Tồn kho</label>
                            <input type="text" id="input-stock" class="form-control" readonly style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Đơn giá (đ)</label>
                            <input type="text" id="input-price" class="form-control" readonly style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" id="input-qty" class="form-control" min="1" value="1">
                        </div>
                        <div class="col-md-1 d-grid">
                            <button type="button" id="btn-add-item" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm</button>
                        </div>
                    </div>

                    <!-- Bảng danh sách đã chọn -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="15%">Mã Thuốc</th>
                                    <th width="30%">Tên Thuốc</th>
                                    <th width="15%" class="text-end">Đơn Giá</th>
                                    <th width="15%" class="text-center">Số Lượng</th>
                                    <th width="15%" class="text-end">Thành Tiền</th>
                                    <th width="5%" class="text-center"><i class="bi bi-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dữ liệu render qua JS -->
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="5" class="text-end">Tổng Tiền:</td>
                                    <td class="text-end text-primary" colspan="2">
                                        <span id="grandTotal">0</span> đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Container chứa các input hidden để submit -->
                    <div id="hiddenInputsContainer"></div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4 fw-medium" id="btn-submit-form">
                            <i class="bi bi-check-circle me-2"></i>Lưu đơn hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAdvancedSearch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary"><i class="bi bi-search me-2"></i>Tìm kiếm thuốc nâng cao</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3 align-items-end bg-white p-3 border rounded shadow-sm">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold mb-1">Từ khóa (Mã/Tên)</label>
                        <input type="text" id="advKeyword" class="form-control form-control-sm" placeholder="Nhập tên thuốc...">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold mb-1">Nhóm thuốc</label>
                        <select id="advNhomThuoc" class="form-select form-select-sm">
                            <option value="">-- Tất cả nhóm --</option>
                            @if(isset($nhom_thuocs))
                                @foreach($nhom_thuocs as $nhom)
                                    <option value="{{ $nhom->ma_nhom }}">{{ $nhom->ten_nhom }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" id="btnAdvSearch" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Lọc
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Tên thuốc</th>
                                <th>Tồn kho</th>
                                <th>Đơn giá</th>
                                <th width="80" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="advResultBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-box-seam fs-3 d-block mb-2"></i>
                                    Nhập điều kiện và bấm <b>Lọc</b> để tìm kiếm
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Init select2
        $('.select2').select2({
            width: '100%',
            placeholder: "-- Chọn --"
        });

        let orderItems = [];

        // Hiển thị Modal Tìm kiếm thuốc
        $('#btn-open-modal').on('click', function() {
            $('#modalAdvancedSearch').modal('show');
        });

        // Thực hiện Lọc bằng AJAX
        $('#btnAdvSearch').click(function() {
            let keyword = $('#advKeyword').val();
            let nhom = $('#advNhomThuoc').val();
            
            $('#advResultBody').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Đang tìm...</td></tr>');

            $.ajax({
                url: "{{ route('admin.orders.advancedSearch') }}", 
                type: "GET",
                data: { keyword: keyword, nhom_thuoc: nhom },
                success: function(response) {
                    let html = '';
                    if(response.length === 0) {
                        html = '<tr><td colspan="5" class="text-center text-danger py-3">Không tìm thấy sản phẩm nào khớp với điều kiện!</td></tr>';
                    } else {
                        response.forEach(item => {
                            let giaBan = item.gia_ban_de_xuat ? item.gia_ban_de_xuat : 0;
                            let tonKho = item.ton_kho_hien_tai ? item.ton_kho_hien_tai : 0;
                            let donVi = item.don_vi_tinh ? item.don_vi_tinh.ten_dvt : '';
                            let tenHienThi = item.ten_thuoc + (donVi ? ' (' + donVi + ')' : '');
                            
                            html += `
                                <tr>
                                    <td class="fw-bold text-success">${item.ma_thuoc}</td>
                                    <td>${tenHienThi}</td>
                                    <td class="text-primary fw-medium">${new Intl.NumberFormat('vi-VN').format(tonKho)}</td>
                                    <td>${new Intl.NumberFormat('vi-VN').format(giaBan)} đ</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-success btn-choose-product" 
                                            data-id="${item.ma_thuoc}"
                                            data-text="${item.ten_thuoc}"
                                            data-stock="${tonKho}"
                                            data-price="${giaBan}">
                                            Chọn
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#advResultBody').html(html);
                },
                error: function() {
                    $('#advResultBody').html('<tr><td colspan="5" class="text-center text-danger py-3">Lỗi kết nối. Hãy kiểm tra lại Route/Controller!</td></tr>');
                }
            });
        });

        // Xử lý khi bấm nút CHỌN trên bảng kết quả
        $(document).on('click', '.btn-choose-product', function() {
            let id = $(this).data('id');
            let text = $(this).data('text');
            let stock = $(this).data('stock');
            let price = $(this).data('price');

            $('#ten_thuoc_hien_thi').val(text);
            $('#ma_thuoc_an').val(id);
            $('#gia_thuoc_an').val(price);
            $('#ton_kho_an').val(stock);

            $('#input-stock').val(stock);
            $('#input-price').val(new Intl.NumberFormat('vi-VN').format(price));
            $('#input-qty').attr('max', stock);
            $('#input-qty').val(1);
            
            $('#modalAdvancedSearch').modal('hide');
        });

        // Nút thêm sản phẩm vào danh sách tạm
        $('#btn-add-item').on('click', function() {
            let maThuoc = $('#ma_thuoc_an').val();
            
            if(!maThuoc) {
                alert('Vui lòng chọn thuốc!');
                return;
            }

            let tenThuoc = $('#ten_thuoc_hien_thi').val();
            let donGia = parseInt($('#gia_thuoc_an').val()) || 0;
            let tonKho = parseInt($('#ton_kho_an').val()) || 0;
            let soLuong = parseInt($('#input-qty').val()) || 0;

            if(soLuong <= 0) {
                alert('Số lượng phải lớn hơn 0!');
                return;
            }

            if(soLuong > tonKho) {
                alert('Số lượng không đủ trong kho!');
                return;
            }

            // check duplicate
            let existingItem = orderItems.find(i => i.maThuoc === maThuoc);
            if(existingItem) {
                if((existingItem.soLuong + soLuong) > tonKho) {
                    alert('Tổng số lượng trong đơn vượt quá tồn kho hiện tại!');
                    return;
                }
                existingItem.soLuong += soLuong;
            } else {
                orderItems.push({
                    maThuoc: maThuoc,
                    tenThuoc: tenThuoc,
                    donGia: donGia,
                    soLuong: soLuong,
                    tonKho: tonKho
                });
            }

            renderTable();
            
            // reset form nhỏ
            $('#ten_thuoc_hien_thi').val('');
            $('#ma_thuoc_an').val('');
            $('#input-stock').val('');
            $('#input-price').val('');
            $('#input-qty').val(1);
        });

        function renderTable() {
            let tbody = $('#itemsTable tbody');
            let hiddenContainer = $('#hiddenInputsContainer');
            tbody.empty();
            hiddenContainer.empty();
            
            let total = 0;

            if(orderItems.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center text-muted">Chưa có sản phẩm nào</td></tr>');
            } else {
                orderItems.forEach((item, index) => {
                    let thanhTien = item.soLuong * item.donGia;
                    total += thanhTien;

                    let row = `<tr>
                        <td class="text-center">${index + 1}</td>
                        <td class="font-monospace">${item.maThuoc}</td>
                        <td class="fw-medium">${item.tenThuoc}</td>
                        <td class="text-end">${new Intl.NumberFormat('vi-VN').format(item.donGia)}</td>
                        <td class="text-center" style="width: 120px;">
                            <input type="number" class="form-control form-control-sm text-center input-change-qty" 
                                data-index="${index}" value="${item.soLuong}" min="1" max="${item.tonKho}">
                        </td>
                        <td class="text-end fw-semibold text-primary">${new Intl.NumberFormat('vi-VN').format(thanhTien)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-remove-item" data-index="${index}">
                                <i class="bi bi-x-circle fs-5"></i>
                            </button>
                        </td>
                    </tr>`;
                    tbody.append(row);

                    // Add hidden inputs for submission
                    hiddenContainer.append(`
                        <input type="hidden" name="items[${index}][ma_thuoc]" value="${item.maThuoc}">
                        <input type="hidden" name="items[${index}][so_luong]" value="${item.soLuong}">
                    `);
                });
            }

            $('#grandTotal').text(new Intl.NumberFormat('vi-VN').format(total));
        }

        // Xóa dòng
        $(document).on('click', '.btn-remove-item', function() {
            let index = $(this).data('index');
            orderItems.splice(index, 1);
            renderTable();
        });

        // Sửa số lượng trực tiếp
        $(document).on('change', '.input-change-qty', function() {
            let index = $(this).data('index');
            let newQty = parseInt($(this).val()) || 1;
            let item = orderItems[index];

            if(newQty > item.tonKho) {
                alert('Vượt tồn kho!');
                $(this).val(item.tonKho);
                item.soLuong = item.tonKho;
            } else if (newQty < 1) {
                $(this).val(1);
                item.soLuong = 1;
            } else {
                item.soLuong = newQty;
            }
            renderTable();
        });

        // Submit form validation
        $('#formCreateOrder').on('submit', function(e) {
            if(orderItems.length === 0) {
                e.preventDefault();
                alert('Vui lòng thêm ít nhất 1 sản phẩm vào đơn hàng!');
                return false;
            }
        });
    });
</script>
@endpush
