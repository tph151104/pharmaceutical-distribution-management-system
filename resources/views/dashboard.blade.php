@extends('layouts.app')

@section('title', 'Dashboard - Hệ thống phân phối thuốc tây')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Dashboard</h1>
                <span class="chip">
                    <i class="bi bi-activity"></i>
                    Trực tuyến
                </span>
            </div>
            <div class="text-muted small">
                Tổng quan nhập - xuất kho, tồn kho lô hàng, công nợ nhà cung cấp & khách hàng.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i>
                Xuất báo cáo nhanh
            </button>
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Tạo phiếu xuất kho
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Giá trị tồn kho</div>
                        <div class="h4 mb-0">₫ 2.150.000.000</div>
                        <div class="small text-success mt-1">
                            <i class="bi bi-arrow-up-right me-1"></i>+8.2% so với tháng trước
                        </div>
                    </div>
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-safe2-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Lô sắp hết hạn</div>
                        <div class="h4 mb-0">24 lô</div>
                        <div class="small text-warning mt-1">
                            <i class="bi bi-exclamation-triangle me-1"></i>Cần xử lý trong 30 ngày
                        </div>
                    </div>
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-alarm"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Doanh số bán sỉ (tháng)</div>
                        <div class="h4 mb-0">₫ 980.000.000</div>
                        <div class="small text-success mt-1">
                            <i class="bi bi-graph-up-arrow me-1"></i>+12.5% so với tháng trước
                        </div>
                    </div>
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted text-uppercase small fw-semibold mb-1">Công nợ (ròng)</div>
                        <div class="h4 mb-0">₫ 320.000.000</div>
                        <div class="small text-muted mt-1">
                            Phải trả NCC <span class="fw-semibold text-danger">₫ 580M</span>,
                            phải thu KH <span class="fw-semibold text-success">₫ 900M</span>
                        </div>
                    </div>
                    <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-journal-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Biểu đồ nhập - xuất kho (6 tháng gần nhất)</div>
                        <div class="text-muted small">Tổng hợp giá trị nhập kho và xuất kho</div>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active">6 tháng</button>
                        <button type="button" class="btn btn-outline-secondary">12 tháng</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted small py-5">
                        (Sau này sẽ gắn chart thật. Hiện tại là placeholder cho biểu đồ.)
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Cảnh báo lô sắp hết hạn</div>
                        <div class="text-muted small">Trong vòng 60 ngày tới</div>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis">24 lô</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div class="me-2">
                                <div class="fw-semibold">Paracetamol 500mg</div>
                                <div class="text-muted">Lô: PARA2309-01 • HSD: 15/04/2026</div>
                            </div>
                            <span class="badge bg-warning-subtle text-warning-emphasis">Tồn: 120 hộp</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div class="me-2">
                                <div class="fw-semibold">Amoxicillin 500mg</div>
                                <div class="text-muted">Lô: AMOX2310-05 • HSD: 02/05/2026</div>
                            </div>
                            <span class="badge bg-warning-subtle text-warning-emphasis">Tồn: 80 hộp</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div class="me-2">
                                <div class="fw-semibold">Vitamin C 500mg</div>
                                <div class="text-muted">Lô: VITC2311-02 • HSD: 20/05/2026</div>
                            </div>
                            <span class="badge bg-warning-subtle text-warning-emphasis">Tồn: 50 lọ</span>
                        </div>
                        <a href="#" class="small mt-2 d-inline-flex align-items-center text-decoration-none">
                            Xem tất cả danh sách hết hạn
                            <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

