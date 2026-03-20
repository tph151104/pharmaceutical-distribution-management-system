@extends('layouts.app')

@section('title', 'Lập phiếu xuất kho mới')

@section('content-header')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-light border me-2"><i class="bi bi-arrow-left"></i> Trở về</a>
        <h1 class="content-header-title mb-0">Lập phiếu xuất kho mới</h1>
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
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="mb-0 fw-bold text-primary">Bước 1: Chọn đơn hàng chờ xuất</h5>
                    <p class="text-muted small mb-0">Hệ thống chỉ hiển thị các đơn hàng đã được Kế toán duyệt (trạng thái: Đã duyệt).</p>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4 mt-2">
                            <label for="ma_don_hang" class="form-label fw-bold">Chọn Đơn đặt hàng sỉ <span class="text-danger">*</span></label>
                            <select name="ma_don_hang" id="ma_don_hang" class="form-select form-select-lg" required>
                                <option value="">-- Click để chọn đơn hàng --</option>
                                @foreach($donHangs as $dh)
                                    <option value="{{ $dh->ma_don_hang }}" 
                                        {{ $selectedOrderId == $dh->ma_don_hang ? 'selected' : '' }}>
                                        {{ $dh->ma_don_hang }} - KH: {{ $dh->khachHang->ten_kh ?? 'N/A' }} 
                                        (Duyệt ngày: {{ $dh->updated_at->format('d/m/Y H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            @if($donHangs->isEmpty())
                                <div class="form-text text-danger mt-2">
                                    <i class="bi bi-exclamation-triangle"></i> Hiện không có đơn hàng nào đang chờ xuất kho.
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info border-info-subtle bg-info-subtle py-2">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Bước 2:</strong> Hệ thống sẽ tạo Phiếu xuất dự thảo dựa trên các sản phẩm khách đã mua và tự động phân bổ Lô cận Date (FEFO).
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pb-2">
                            <a href="{{ route('sales.index') }}" class="btn btn-light px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" {{ $donHangs->isEmpty() ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-right-circle me-1"></i> Tạo Phiếu & Phân Bổ Lô
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
