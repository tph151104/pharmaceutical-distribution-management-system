 @extends('layouts.app')

@section('title', 'Chi tiết Thanh toán')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0">
                <a href="{{ route('payments.history') }}" class="text-decoration-none text-muted me-2"><i class="bi bi-arrow-left"></i></a>
                Biên Lai Thanh Toán: #{{ $thanhToan->ma_thanh_toan }}
            </h1>
        </div>
        <div>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>In biên lai
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm col-md-8 col-lg-6 mx-auto mt-4 printable-area">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4 pb-3 border-bottom">
                <h4 class="fw-bold mb-1">BIÊN LAI GIAO DỊCH</h4>
                <div class="text-muted small">Thời gian: {{ \Carbon\Carbon::parse($thanhToan->ngay_thanh_toan)->format('d/m/Y') }} {{ $thanhToan->created_at->format('H:i:s') }}</div>
            </div>

            <div class="mb-4">
                <div class="row mb-2">
                    <div class="col-sm-5 text-muted">Mã giao dịch:</div>
                    <div class="col-sm-7 fw-semibold">{{ $thanhToan->ma_thanh_toan }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5 text-muted">Loại nghiệp vụ:</div>
                    <div class="col-sm-7 fw-semibold">
                        @if($thanhToan->loai_thanh_toan == 'nhap')
                            <span class="text-danger">Ngân sinh (Trả nợ NCC)</span>
                        @elseif($thanhToan->loai_thanh_toan == 'xuat')
                            <span class="text-success">Nguồn thu (Thu nợ KH)</span>
                        @elseif($thanhToan->ma_phieu_tra_ncc)
                            <span class="text-info">Hoàn trả (Nhận tiền từ NCC)</span>
                        @else
                            <span class="text-warning">Hoàn trả (Trả tiền cho KH)</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5 text-muted">Mã chứng từ gốc:</div>
                    <div class="col-sm-7 fw-semibold">
                        @if($thanhToan->loai_thanh_toan == 'nhap')
                            {{ $thanhToan->ma_phieu_nhap }}
                        @elseif($thanhToan->loai_thanh_toan == 'xuat')
                            {{ $thanhToan->ma_phieu_xuat }}
                        @else
                            {{ $thanhToan->ma_tra_hang ?: $thanhToan->ma_phieu_tra_ncc }}
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5 text-muted">Đối tác giao dịch:</div>
                    <div class="col-sm-7 fw-bold">
                        @if($thanhToan->loai_thanh_toan == 'nhap')
                            {{ $thanhToan->phieuNhap->nhaCungCap->ten_ncc ?? 'N/A' }}
                        @elseif($thanhToan->loai_thanh_toan == 'xuat')
                            {{ $thanhToan->phieuXuat->khachHang->ten_kh ?? 'N/A' }}
                        @elseif($thanhToan->ma_phieu_tra_ncc)
                            {{ $thanhToan->phieuTraNcc->nhaCungCap->ten_ncc ?? 'N/A' }}
                        @else
                            {{ $thanhToan->khachTraHang->khachHang->ten_kh ?? 'N/A' }}
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5 text-muted">Phương thức TT:</div>
                    <div class="col-sm-7 fw-semibold">{{ $thanhToan->phuong_thuc_tt }}</div>
                </div>
                @if($thanhToan->ghi_chu)
                <div class="row mb-2">
                    <div class="col-sm-5 text-muted">Ghi chú:</div>
                    <div class="col-sm-7 fst-italic">{{ $thanhToan->ghi_chu }}</div>
                </div>
                @endif
            </div>

            <div class="bg-light rounded p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-semibold">Tổng nguyên giá chứng từ:</span>
                    <span>{{ number_format($thanhToan->tong_tien) }} đ</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                    <span class="text-muted fw-semibold">Dư nợ còn lại (sau GD):</span>
                    <span class="fw-bold {{ $thanhToan->so_tien_con_no <= 0 ? 'text-success' : 'text-warning' }}">
                        {{ number_format($thanhToan->so_tien_con_no) }} đ
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="fs-5 fw-bold">SỐ TIỀN GIAO DỊCH:</span>
                    <span class="fs-4 fw-bold text-primary">{{ number_format($thanhToan->so_tien_tt) }} VNĐ</span>
                </div>
            </div>

            @if($thanhToan->minh_chung_tt_image)
            <div class="mb-4 text-center">
                <h6 class="fw-bold mb-3">MINH CHỨNG GIAO DỊCH</h6>
                <img src="{{ asset($thanhToan->minh_chung_tt_image) }}" alt="Minh chứng thanh toán" class="img-fluid border rounded shadow-sm img-clickable" style="max-height: 400px; object-fit: contain;" title="Click để phóng to xem kỹ hơn">
            </div>
            @endif

            <div class="row text-center mt-5 pt-3">
                <div class="col-6">
                    <h6 class="fw-bold mb-4">Người nộp/nhận tiền</h6>
                    <div class="text-muted small">(Ký, ghi rõ họ tên)</div>
                </div>
                <div class="col-6">
                    <h6 class="fw-bold mb-4">Kế toán / Thủ quỹ</h6>
                    <div class="text-muted small">(Ký, ghi rõ họ tên)</div>
                </div>
            </div>
            <div class="mt-5 pt-4 text-center">
                <br>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area, .printable-area * {
            visibility: visible;
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none !important;
        }
    }
</style>
@endpush
