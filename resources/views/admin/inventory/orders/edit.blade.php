@extends('layouts.app')

@section('title', 'Chỉnh Sửa Đơn Hàng ' . $donHang->ma_don_hang)

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Chỉnh Sửa Đơn Hàng</h1>
            <p class="text-muted small mb-0 mt-1">Sửa đổi số lượng sản phẩm nếu kho không đủ hoặc theo yêu cầu khách hàng</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.show', $donHang->ma_don_hang) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay lại chi tiết
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

                <form action="{{ route('admin.orders.update', $donHang->ma_don_hang) }}" method="POST" id="formEditOrder">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mã đơn hàng</label>
                            <input type="text" class="form-control bg-light" value="{{ $donHang->ma_don_hang }}" readonly disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Khách hàng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light" value="{{ $donHang->khachHang->ten_kh ?? $donHang->ma_kh }} - {{ $donHang->khachHang->dien_thoai ?? '' }}" readonly disabled>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-list-check me-2"></i>Chi tiết đơn hàng</h5>
                    

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
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dữ liệu render qua JS -->
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="4" class="text-end">Tổng Tiền:</td>
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
                            <i class="bi bi-save me-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
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

        // Nạp dữ liệu cũ vào mảng
        let orderItems = [];
        
        @foreach($donHang->chiTiet as $ct)
        orderItems.push({
            maThuoc: '{{ $ct->ma_thuoc }}',
            tenThuoc: '{{ addslashes($ct->thuoc->ten_thuoc ?? $ct->ma_thuoc) }}',
            donGia: {{ $ct->don_gia }},
            soLuong: {{ $ct->so_luong }},
            tonKho: {{ $ct->thuoc->tong_ton_kho ?? 0 }}
        });
        @endforeach

        renderTable();


        function renderTable() {
            let tbody = $('#itemsTable tbody');
            let hiddenContainer = $('#hiddenInputsContainer');
            tbody.empty();
            hiddenContainer.empty();
            
            let total = 0;

            if(orderItems.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted">Chưa có sản phẩm nào</td></tr>');
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
        $('#formEditOrder').on('submit', function(e) {
            if(orderItems.length === 0) {
                e.preventDefault();
                alert('Vui lòng thêm ít nhất 1 sản phẩm vào đơn hàng!');
                return false;
            }
        });
    });
</script>
@endpush
