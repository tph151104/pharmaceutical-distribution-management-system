@extends('layouts.app')

@section('title', 'Lịch sử Thanh toán')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-0">
                <a href="{{ route('payments.index') }}" class="text-decoration-none text-muted me-2"><i class="bi bi-arrow-left"></i></a>
                Lịch sử Thanh toán
            </h1>
            <p class="text-muted small mb-0 mt-1">Danh sách các giao dịch thanh toán đã thực hiện</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Bộ lọc & Chuyển Tab -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="payment-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{ route('payments.history', ['tab' => 'xuat', 'group_by' => request('group_by'), 'search' => request('search'), 'from_date' => request('from_date'), 'to_date' => request('to_date')]) }}" 
                       class="nav-link {{ $tab == 'xuat' ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-circle me-1"></i>Công nợ Phải thu (Khách hàng)
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('payments.history', ['tab' => 'nhap', 'group_by' => request('group_by'), 'search' => request('search'), 'from_date' => request('from_date'), 'to_date' => request('to_date')]) }}" 
                       class="nav-link {{ $tab == 'nhap' ? 'active bg-danger' : 'text-danger' }}">
                        <i class="bi bi-arrow-up-circle me-1"></i>Công nợ Phải trả (NCC)
                    </a>
                </li>
            </ul>

            <form action="{{ route('payments.history') }}" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="col-md-3">
                    <label class="form-label small fw-medium">Tìm mã giao dịch / mã phiếu</label>
                    <input type="text" name="search" class="form-control form-control-sm" value="{{ $search }}" placeholder="Nhập mã...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-medium">Từ ngày (Ngày thanh toán)</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-medium">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}">
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch mt-3 ms-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="groupByBtn" name="group_by" value="true" {{ $groupBy == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label small fw-medium text-dark" for="groupByBtn">Gom nhóm theo Mã Phiếu</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-search me-1"></i>Lọc</button>
                    <a href="{{ route('payments.history.export', ['tab' => $tab, 'search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate]) }}" target="_blank" class="btn btn-sm btn-success flex-grow-1" title="Xuất Excel">
                        <i class="bi bi-file-earmark-excel"></i> Xuất
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                @if($groupBy == 'true')
                    {{-- CHẾ ĐỘ GOM NHÓM --}}
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 border-bottom-0">Mã Phiếu</th>
                            <th class="border-bottom-0">{{ $tab == 'nhap' ? 'Nhà Cung Cấp' : 'Khách Hàng' }}</th>
                            <th class="border-bottom-0 text-center">Số giao dịch</th>
                            <th class="border-bottom-0 text-end">Tổng đã trả (trong kỳ lọc)</th>
                            <th class="pe-3 border-bottom-0 text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $maPhieu => $groupTx)
                            @php
                                $firstTx = $groupTx->first();
                                $doiTuong = $tab == 'nhap' ? ($firstTx->phieuNhap->nhaCungCap->ten_ncc ?? 'N/A') : ($firstTx->phieuXuat->khachHang->ten_kh ?? 'N/A');
                                $tongTienNhom = $groupTx->sum('so_tien_tt');
                            @endphp
                            <tr class="table-secondary border-bottom">
                                <td class="ps-3 fw-bold text-primary">{{ $maPhieu ?? 'Không chỉ định' }}</td>
                                <td class="fw-semibold">{{ $doiTuong }}</td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $groupTx->count() }} GD</span></td>
                                <td class="text-end fw-bold {{ $tab == 'nhap' ? 'text-danger' : 'text-success' }}">
                                    {{ $tab == 'nhap' ? '-' : '+' }}{{ number_format($tongTienNhom) }}đ
                                </td>
                                <td class="pe-3 text-end">
                                    <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Str::slug($maPhieu) }}">
                                        <i class="bi bi-chevron-down"></i> Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                            <tr class="p-0 border-0">
                                <td colspan="5" class="p-0 border-0">
                                    <div class="collapse" id="collapse-{{ \Str::slug($maPhieu) }}">
                                        <table class="table table-sm table-bordered mb-0 bg-light w-100">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4">Ngày GD</th>
                                                    <th>Mã GD</th>
                                                    <th>Phương thức</th>
                                                    <th class="text-end">Số Tiền (VNĐ)</th>
                                                    <th class="text-center pe-3">In Hóa đơn</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($groupTx as $tx)
                                                <tr>
                                                    <td class="ps-4 text-muted small">{{ \Carbon\Carbon::parse($tx->ngay_thanh_toan)->format('d/m/Y H:i') }}</td>
                                                    <td class="fw-medium text-primary small">{{ $tx->ma_thanh_toan }}</td>
                                                    <td class="small">{{ $tx->phuong_thuc_tt }}</td>
                                                    <td class="text-end fw-bold small {{ $tab == 'nhap' ? 'text-danger' : 'text-success' }}">
                                                        {{ number_format($tx->so_tien_tt) }}
                                                    </td>
                                                    <td class="text-center pe-3">
                                                        <a href="{{ route('payments.show', $tx->ma_thanh_toan) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0" style="font-size: 0.8rem;">
                                                            <i class="bi bi-printer"></i> In HĐ
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu.</td></tr>
                        @endforelse
                    </tbody>
                @else
                    {{-- CHẾ ĐỘ PHẲNG (MẶC ĐỊNH) --}}
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 border-bottom-0">Ngày GD</th>
                            <th class="border-bottom-0">Mã GD</th>
                            <th class="border-bottom-0">Mã Phiếu</th>
                            <th class="border-bottom-0">{{ $tab == 'nhap' ? 'Nhà Cung Cấp' : 'Khách Hàng' }}</th>
                            <th class="border-bottom-0">Phương thức TT</th>
                            <th class="border-bottom-0 text-end">Số Tiền (VNĐ)</th>
                            <th class="pe-3 border-bottom-0 text-center">Khác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($tx->ngay_thanh_toan)->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ $tx->created_at->format('H:i:s') }}</div>
                                </td>
                                <td><span class="fw-semibold text-primary">{{ $tx->ma_thanh_toan }}</span></td>
                                <td>
                                    <span class="fw-medium">{{ $tab == 'nhap' ? $tx->ma_phieu_nhap : $tx->ma_phieu_xuat }}</span>
                                </td>
                                <td>
                                    {{ $tab == 'nhap' ? ($tx->phieuNhap->nhaCungCap->ten_ncc ?? 'N/A') : ($tx->phieuXuat->khachHang->ten_kh ?? 'N/A') }}
                                </td>
                                <td>{{ $tx->phuong_thuc_tt }}</td>
                                <td class="text-end fw-bold {{ $tab == 'nhap' ? 'text-danger' : 'text-success' }}">
                                    {{ $tab == 'nhap' ? '-' : '+' }}{{ number_format($tx->so_tien_tt) }}
                                </td>
                                <td class="pe-3 text-center">
                                    <a href="{{ route('payments.show', $tx->ma_thanh_toan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-printer"></i> In HĐ
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có lịch sử giao dịch thanh toán nào phù hợp.</td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
                </table>
            </div>
            
            @if($groupBy != 'true' && $transactions->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
