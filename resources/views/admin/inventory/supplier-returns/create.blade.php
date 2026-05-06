@extends('layouts.app')

<?php use App\Models\NhaCungCap; ?>

@section('title', 'Tạo Phiếu Trả Hàng Nhà Cung Cấp')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-light border me-2"><i class="bi bi-arrow-left"></i> Trở về</a>
                <h1 class="content-header-title mb-0">Tạo Phiếu Trả Hàng (KV04)</h1>
            </div>
            <div class="text-muted small ms-5 ps-3">
                Lập phiếu trả lô hàng lỗi, cận hạn về lại nhà cung cấp từ kho Chờ Xử Lý.
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary"><i class="bi bi-box-seam me-2"></i>Thông tin Lô hàng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold text-uppercase mb-1">Sản Phẩm</label>
                        <div class="fw-bold">{{ $tonKhoKv->thuoc->ten_thuoc ?? $tonKhoKv->ma_thuoc }}</div>
                        <div class="small text-muted">{{ $tonKhoKv->ma_thuoc }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold text-uppercase mb-1">Số Lô / Phiếu Nhập</label>
                        <div><span class="badge bg-light text-dark border">{{ $tonKhoKv->so_lo }}</span></div>
                        <div class="small mt-1 text-muted"><i class="bi bi-receipt me-1"></i>{{ $tonKhoKv->ma_phieu_nhap }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold text-uppercase mb-1">Tồn trong KV04</label>
                        <div class="fs-4 fw-bold text-danger">{{ number_format($tonKhoKv->so_luong) }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small fw-semibold text-uppercase mb-1">Nhà Cung Cấp Gốc</label>
                        @if($tonKhoKv->phieuNhap && $tonKhoKv->phieuNhap->nhaCungCap)
                            <div class="fw-semibold text-dark">{{ $tonKhoKv->phieuNhap->nhaCungCap->ten_ncc }}</div>
                            <div class="small text-muted">{{ $tonKhoKv->phieuNhap->nhaCungCap->dien_thoai ?? 'N/A' }}</div>
                        @else
                            <div class="text-muted fst-italic">Không rõ</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('supplier-returns.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ma_thuoc" value="{{ $tonKhoKv->ma_thuoc }}">
                        <input type="hidden" name="so_lo" value="{{ $tonKhoKv->so_lo }}">
                        <input type="hidden" name="ma_phieu_nhap" value="{{ $tonKhoKv->ma_phieu_nhap }}">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chọn Nhà Cung Cấp Trả Hàng <span class="text-danger">*</span></label>
                                <select name="ma_ncc" class="form-select" required>
                                    @php
                                        $nccGoc = $tonKhoKv->phieuNhap ? $tonKhoKv->phieuNhap->ma_ncc : null;
                                    @endphp
                                    <option value="">-- Chọn NCC --</option>
                                    @foreach(NhaCungCap::all() as $ncc)
                                        <option value="{{ $ncc->ma_ncc }}" {{ $nccGoc == $ncc->ma_ncc ? 'selected' : '' }}>
                                            {{ $ncc->ten_ncc }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Mặc định chọn NCC đã nhập lô hàng này.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số Lượng Trả <span class="text-danger">*</span></label>
                                <input type="number" name="so_luong_tra" class="form-control" 
                                    min="1" max="{{ $tonKhoKv->so_luong }}" value="{{ $tonKhoKv->so_luong }}" required>
                                <div class="form-text text-danger">Tối đa: {{ number_format($tonKhoKv->so_luong) }}</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý Do Trả Hàng <span class="text-danger">*</span></label>
                            <textarea name="ly_do_tra" class="form-control" rows="3" required placeholder="Ghi rõ lý do tại sao trả lô hàng này về NCC (hàng cận date, bao bì lỗi, thu hồi...)"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ghi Chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú thêm..."></textarea>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('transfers.index') }}" class="btn btn-light me-2">Hủy Bỏ</a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-box-arrow-right me-1"></i> Tạo Phiếu Trả NCC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
