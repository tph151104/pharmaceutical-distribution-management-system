@extends('layouts.app')

@section('title', 'Thống Kê Tồn Kho Theo Lô')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-header-title mb-1">Báo Cáo Tồn Kho Theo Lô</h1>
            <p class="text-muted small mb-0">Theo dõi chi tiết số lượng tồn, hạn sử dụng và cảnh báo hàng sắp hết hạn.</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Form tìm kiếm nhanh tên thuốc -->
            <form action="{{ route('reports.stock') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm tên thuốc..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary px-3">Lọc</button>
            </form>
            <a href="{{ route('reports.stock.export', request()->query()) }}" class="btn btn-success btn-sm px-3">
                <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-layers me-2"></i>Thống Kê Tồn Kho Theo Lô Hàng</h5>
                <div>
                   <span class="badge bg-danger me-1">Đã hết hạn / &lt; 3 tháng</span>
                   <span class="badge bg-warning text-dark me-1">Sắp hết hạn (&lt; 6 tháng)</span>
                   <span class="badge bg-success">An toàn</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="stockTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Mã Thuốc</th>
                                <th>Tên Thuốc</th>
                                <th>Số Lô</th>
                                <th>Ngày Nhập</th>
                                <th>Hạn Sử Dụng</th>
                                <th>Số Lượng Tồn</th>
                                <th>Trạng Thái Lô</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tonKho as $ton)
                                @php
                                    $hanSuDung = \Carbon\Carbon::parse($ton->han_su_dung);
                                    
                                    $statusClass = '';
                                    $textClass = 'text-success fw-medium'; // Mặc định an toàn
                                    
                                    if ($hanSuDung->lt($today)) {
                                        $statusClass = 'table-danger';
                                        $textClass = 'text-danger fw-bold';
                                    } elseif ($hanSuDung->lte($threeMonthsFromNow)) {
                                        $statusClass = 'table-danger';
                                        $textClass = 'text-danger fw-bold';
                                    } elseif ($hanSuDung->lte($sixMonthsFromNow)) {
                                        $statusClass = 'table-warning';
                                        $textClass = 'text-warning text-dark fw-bold';
                                    }
                                @endphp
                                <tr class="{{ $statusClass }}">
                                    <td class="ps-4 text-muted">{{ $ton->ma_thuoc }}</td>
                                    <td class="fw-semibold text-dark">{{ $ton->thuoc->ten_thuoc ?? 'N/A' }}</td>
                                    <td><span class="badge bg-light text-dark border border-secondary font-monospace">{{ $ton->so_lo }}</span></td>
                                    <td>{{ $ton->ngay_nhap_lo ? $ton->ngay_nhap_lo->format('d/m/Y') : '' }}</td>
                                    <td class="{{ $textClass }}">
                                        {{ $hanSuDung->format('d/m/Y') }}
                                        @if($hanSuDung->lt($today))
                                            <br><small class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>(Đã quá hạn)</small>
                                        @endif
                                    </td>
                                    <td class="fw-bold px-3">{{ number_format($ton->so_luong_ton) }}</td>
                                    <td>
                                        @if($ton->trang_thai_lo === 'dang_ban')
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Đang bán</span>
                                        @elseif($ton->trang_thai_lo === 'ngung_ban')
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">Ngừng bán</span>
                                        @elseif($ton->trang_thai_lo === 'cho_duyet')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Chờ duyệt</span>
                                        @else
                                            <span class="badge bg-danger">{{ $ton->trang_thai_lo }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Không có dữ liệu tồn kho nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $tonKho->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Dành cho sau này nếu muốn chuyển trang này thành DataTables
</script>
@endpush
@endsection
