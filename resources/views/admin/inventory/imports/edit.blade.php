@extends('layouts.app')

@section('title', 'Sửa phiếu nhập kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('imports.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
                <h1 class="content-header-title mb-0">Sửa phiếu nhập kho</h1>
            </div>
            <div class="text-muted small">
                Cập nhật chứng từ nhập kho chưa nhận hàng.
            </div>
        </div>
    </div>
@endsection

@section('content')
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

    <form action="{{ route('imports.update', $phieuNhap->ma_phieu_nhap) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i> Thông tin chung</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Mã Phiếu Nhập *</label>
                        <input type="text" name="ma_phieu_nhap" class="form-control bg-light" value="{{ $phieuNhap->ma_phieu_nhap }}" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold mb-1">Nhà cung cấp *</label>
                        <select name="ma_ncc" class="form-select" required>
                            <option value="">-- Chọn Nhà cung cấp --</option>
                            @foreach($nhaCungCaps as $ncc)
                                <option value="{{ $ncc->ma_ncc }}" {{ $phieuNhap->ma_ncc == $ncc->ma_ncc ? 'selected' : '' }}>{{ $ncc->ten_ncc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Ngày lập phiếu (Ngày nhập) *</label>
                        <input type="date" name="ngay_nhap" class="form-control" value="{{ $phieuNhap->ngay_nhap->format('Y-m-d') }}" required>
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
                                <th>Sản phẩm</th>
                                <th>Ngày sản xuất</th>
                                <th>Số đăng ký</th>
                                <th>Hạn dùng (Dự)</th>
                                <th>Số lượng (Chứng từ)</th>
                                <th>Đơn giá nhập</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="chiTietBody">
                            @foreach($phieuNhap->chiTiet as $index => $ct)
                            <tr>
                                <td>
                                    <select name="chi_tiet[{{ $index }}][ma_thuoc]" class="form-select form-select-sm select-thuoc" required>
                                        <option value="">-- Chọn Thuốc --</option>
                                        @foreach($thuocs as $thuoc)
                                            <option value="{{ $thuoc->ma_thuoc }}" {{ $ct->ma_thuoc == $thuoc->ma_thuoc ? 'selected' : '' }}>{{ $thuoc->ten_thuoc }} ({{ $thuoc->ma_thuoc }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="date" name="chi_tiet[{{ $index }}][ngay_san_xuat]" class="form-control form-control-sm" value="{{ $ct->ngay_san_xuat ? date('Y-m-d', strtotime($ct->ngay_san_xuat)) : '' }}" required></td>
                                <td><input type="text" name="chi_tiet[{{ $index }}][so_dang_ky]" class="form-control form-control-sm" value="{{ $ct->so_dang_ky }}" placeholder="SĐK (Tùy chọn)"></td>
                                <td><input type="date" name="chi_tiet[{{ $index }}][han_su_dung]" class="form-control form-control-sm" value="{{ $ct->han_su_dung ? date('Y-m-d', strtotime($ct->han_su_dung)) : '' }}" required></td>
                                <td><input type="number" name="chi_tiet[{{ $index }}][so_luong_nhap]" class="form-control form-control-sm" min="1" value="{{ $ct->so_luong_nhap }}" required></td>
                                <td><input type="number" name="chi_tiet[{{ $index }}][don_gia_nhap]" class="form-control form-control-sm" min="0" step="100" value="{{ $ct->don_gia_nhap }}" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Cập nhật Hóa Đơn Lập Phiếu
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIdx = {{ count($phieuNhap->chiTiet) }};
            const btnAddRow = document.getElementById('btnAddRow');
            const tableBody = document.getElementById('chiTietBody');

            btnAddRow.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select name="chi_tiet[${rowIdx}][ma_thuoc]" class="form-select form-select-sm select-thuoc" required>
                            <option value="">-- Chọn Thuốc --</option>
                            @foreach($thuocs as $thuoc)
                                <option value="{{ $thuoc->ma_thuoc }}">{{ $thuoc->ten_thuoc }} ({{ $thuoc->ma_thuoc }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="date" name="chi_tiet[${rowIdx}][ngay_san_xuat]" class="form-control form-control-sm" required></td>
                    <td><input type="text" name="chi_tiet[${rowIdx}][so_dang_ky]" class="form-control form-control-sm" placeholder="SĐK (Tùy chọn)"></td>
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
        });
    </script>
@endsection
