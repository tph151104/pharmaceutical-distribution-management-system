
@extends('layouts.app')

@section('title', 'Lập phiếu nhập kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('imports.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
                <h1 class="content-header-title mb-0">Lập phiếu nhập kho</h1>
            </div>
            <div class="text-muted small">
                Tạo mới chứng từ nhập kho từ nhà cung cấp.
            </div>
        </div>
    </div>
@endsection

@section('content')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('imports.store') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i> Thông tin chung</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Mã Phiếu Nhập</label>
                        <input type="text" class="form-control bg-light" value="Hệ thống tự sinh (PN_...)" readonly disabled>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold mb-1">Nhà cung cấp *</label>
                        <select name="ma_ncc" class="form-select" required>
                            <option value="">-- Chọn Nhà cung cấp --</option>
                            @foreach($nhaCungCaps as $ncc)
                                <option value="{{ $ncc->ma_ncc }}">{{ $ncc->ten_ncc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Ngày lập phiếu (Ngày nhập) *</label>
                        <input type="date" name="ngay_nhap" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary"><i class="bi bi-list-check me-1"></i> Chi tiết mặt hàng (Chứng từ)</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddRow">
                    <i class="bi bi-plus"></i> Thêm dòng
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="chiTietTable">
                        <thead class="table-light small text-muted text-center">
                            <tr>
                                <th width="30%">Sản phẩm</th> 
                                <th>Ngày sản xuất</th>
                                <th>Số đăng ký</th>
                                <th>Hạn dùng (Dự)</th>
                                <th>Số lượng</th>
                                <th>Đơn giá nhập</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="chiTietBody">
                            <tr>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="ten_thuoc_0" class="form-control bg-white" placeholder="-- Bấm kính lúp để chọn --" readonly required>
                                        <input type="hidden" name="chi_tiet[0][ma_thuoc]" id="ma_thuoc_0" required>
                                        
                                        <button type="button" class="btn btn-outline-primary btn-open-modal" data-row="0" title="Tìm kiếm nâng cao">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><input type="date" name="chi_tiet[0][ngay_san_xuat]" class="form-control form-control-sm" required></td>
                                <td><input type="text" name="chi_tiet[0][so_dang_ky]" class="form-control form-control-sm" placeholder="SĐK"></td>
                                <td><input type="date" name="chi_tiet[0][han_su_dung]" class="form-control form-control-sm" required></td>
                                <td><input type="number" name="chi_tiet[0][so_luong_nhap]" class="form-control form-control-sm" min="1" required></td>
                                <td><input type="number" name="chi_tiet[0][don_gia_nhap]" class="form-control form-control-sm" min="0" step="100" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Lưu Hóa Đơn Lập Phiếu
                </button>
            </div>
        </div>
    </form>

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
                                    <th>Nhóm</th>
                                    <th>Hàm lượng</th>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIdx = 1;
            const btnAddRow = document.getElementById('btnAddRow');
            const tableBody = document.getElementById('chiTietBody');

            // Xử lý Thêm dòng
            btnAddRow.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                
                newRow.innerHTML = `
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" id="ten_thuoc_${rowIdx}" class="form-control bg-white" placeholder="-- Bấm kính lúp để chọn --" readonly required>
                            <input type="hidden" name="chi_tiet[${rowIdx}][ma_thuoc]" id="ma_thuoc_${rowIdx}" required>
                            <button type="button" class="btn btn-outline-primary btn-open-modal" data-row="${rowIdx}" title="Tìm kiếm nâng cao">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </td>
                    <td><input type="date" name="chi_tiet[${rowIdx}][ngay_san_xuat]" class="form-control form-control-sm" required></td>
                    <td><input type="text" name="chi_tiet[${rowIdx}][so_dang_ky]" class="form-control form-control-sm" placeholder="SĐK"></td>
                    <td><input type="date" name="chi_tiet[${rowIdx}][han_su_dung]" class="form-control form-control-sm" required></td>
                    <td><input type="number" name="chi_tiet[${rowIdx}][so_luong_nhap]" class="form-control form-control-sm" min="1" required></td>
                    <td><input type="number" name="chi_tiet[${rowIdx}][don_gia_nhap]" class="form-control form-control-sm" min="0" step="100" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button>
                    </td>
                `;
                tableBody.appendChild(newRow);
                rowIdx++;
            });

            // Xử lý Xóa dòng
            tableBody.addEventListener('click', function(e) {
                if(e.target.closest('.btn-remove-row')) {
                    const rowCounter = tableBody.querySelectorAll('tr').length;
                    if(rowCounter > 1) {
                        e.target.closest('tr').remove();
                    } else {
                        alert("Cần có ít nhất 1 mặt hàng trong phiếu nhập!");
                    }
                }
            });

            // ================= LOGIC XỬ LÝ POPUP TÌM KIẾM =================
            let currentRowForModal = null; // Lưu chỉ số dòng hiện tại đang mở modal

            // Mở Modal
            $(document).on('click', '.btn-open-modal', function() {
                currentRowForModal = $(this).data('row');// Lấy chỉ số dòng hiện tại
                $('#modalAdvancedSearch').modal('show'); // Mở modal
            });

            // Thực hiện Lọc bằng AJAX
            $('#btnAdvSearch').click(function() {
                let keyword = $('#advKeyword').val();// Lấy từ khóa tìm kiếm
                let nhom = $('#advNhomThuoc').val();
                
                $('#advResultBody').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Đang tìm...</td></tr>');// Hiển thị trạng thái đang tìm kiếm

                $.ajax({//Sử dụng $.ajax để gửi yêu cầu ngầm (không tải lại trang) lên server (Laravel Controller)
                // qua Route imports.advancedSearch
                    url: "{{ route('imports.advancedSearch') }}", 
                    type: "GET",
                    data: { keyword: keyword, nhom_thuoc: nhom },// Gửi dữ liệu tìm kiếm
                    success: function(response) {// Hàm này sẽ được gọi khi server trả về kết quả
                        let html = '';// Khởi tạo biến html để chứa mã HTML của bảng kết quả
                        if(response.length === 0) {
                            html = '<tr><td colspan="5" class="text-center text-danger py-3">Không tìm thấy sản phẩm nào khớp với điều kiện!</td></tr>';
                        } else {
                            response.forEach(item => {
                                let tenNhom = item.nhom_thuoc ? item.nhom_thuoc.ten_nhom : '-';
                                let hamLuong = item.ham_luong ? item.ham_luong : '';
                                let tenHienThi = item.ten_thuoc + (hamLuong ? ' (' + hamLuong + ')' : '');
                                
                                html += `
                                    <tr>
                                        <td class="fw-bold text-success">${item.ma_thuoc}</td>
                                        <td>${item.ten_thuoc}</td>
                                        <td>${tenNhom}</td>
                                        <td>${hamLuong || '-'}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-success btn-choose-product" 
                                                data-id="${item.ma_thuoc}"
                                                data-text="${tenHienThi}">
                                                Chọn
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        }
                        $('#advResultBody').html(html);// Hiển thị kết quả tìm kiếm
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

                // 1. Điền tên hiển thị ra ô text bên ngoài
                $('#ten_thuoc_' + currentRowForModal).val(text);
                
                // 2. Điền mã thuốc vào ô input ẩn để gửi lên form
                $('#ma_thuoc_' + currentRowForModal).val(id);
                
                // Đóng Modal
                $('#modalAdvancedSearch').modal('hide');
            });
        });
    </script>
@endsection