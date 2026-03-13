@extends('layouts.app')

@section('title', 'Tồn kho chi tiết theo lô hàng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Tồn kho theo lô hàng</h1>
            </div>
            <div class="text-muted small">
                Xem chi tiết tồn kho đến từng lô: số lô, hạn dùng, tồn hiện tại, giá vốn và nhà cung cấp.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i>
                Xuất Excel
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('batches.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Sản phẩm</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Mã hoặc tên sản phẩm" value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Số lô</label>
                    <input type="text" name="so_lo" class="form-control form-control-sm" placeholder="Nhập số lô" value="{{ request('so_lo') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Khoảng hạn dùng</label>
                    <div class="d-flex gap-1">
                        <input type="date" name="han_tu" class="form-control form-control-sm" value="{{ request('han_tu') }}">
                        <input type="date" name="han_den" class="form-control form-control-sm" value="{{ request('han_den') }}">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted mb-1">Tình trạng lô</label>
                    <select name="tinh_trang" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="normal" {{ request('tinh_trang') == 'normal' ? 'selected' : '' }}>Bình thường</option>
                        <option value="warning" {{ request('tinh_trang') == 'warning' ? 'selected' : '' }}>Sắp hết hạn</option>
                        <option value="expired" {{ request('tinh_trang') == 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i>
                        Lọc
                    </button>
                    @if(request()->anyFilled(['search', 'so_lo', 'han_tu', 'han_den', 'tinh_trang']))
                        <a href="{{ route('batches.index') }}" class="btn btn-light btn-sm mt-1">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Tổng số lô đang hiện tồn</div>
                    <div class="h4 mb-0">{{ number_format($tongSoLo) }} lô</div>
                    <div class="small text-muted mt-1">
                        Bao gồm tất cả các lô còn tồn số lượng dương.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Lô sắp/đã hết hạn</div>
                    <div class="h4 mb-0 {{ $loSapHetHan > 0 ? 'text-warning' : 'text-success' }}">{{ number_format($loSapHetHan) }} lô</div>
                    <div class="small text-muted mt-1">
                        Hạn dùng trong vòng 60 ngày tới hoặc đã quá hạn.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Giá trị tồn kho theo lô</div>
                    <div class="h4 mb-0 text-primary">₫ {{ number_format($giaTriTonKho, 0, ',', '.') }}</div>
                    <div class="small text-muted mt-1">
                        Tính theo giá vốn nhập của từng lô hàng.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">
                Tồn kho chi tiết theo lô
            </div>
            <div class="small text-muted">
                Dữ liệu hiển thị: <span class="fw-semibold">{{ $ton_khos->total() }}</span> lô
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Ảnh lô</th>
                        <th class="text-nowrap">Sản phẩm</th>
                        <th class="text-nowrap">Số lô</th>
                        <th class="text-nowrap">Hạn dùng</th>
                        <th class="text-nowrap">Ngày nhập lô</th>
                        <th class="text-nowrap text-end">Số ngày còn lại</th>
                        <th class="text-nowrap text-end">Tồn lô</th>
                        <th class="text-nowrap text-end">SL đã xuất</th>
                        <th class="text-nowrap">Trạng thái lô</th>
                        <th class="text-nowrap">Nhà cung cấp</th>
                        <th class="text-nowrap text-center">Tình trạng</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ton_khos as $ton)
                        @php
                            $now = \Carbon\Carbon::now();
                            $hanDung = $ton->han_su_dung;
                            $daysLeft = $now->diffInDays($hanDung, false); // false để lấy số âm gán vào đã hết hạn
                        @endphp
                        <tr>
                            <td>
                                @if($ton->image1)
                                    <img src="{{ asset($ton->image1) }}" alt="..." style="width: 40px; height: 40px; object-fit: cover;" class="rounded border">
                                @else
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center border rounded" style="width: 40px; height: 40px; font-size: 10px;">No img</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $ton->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $ton->ma_thuoc }}</div>
                            </td>
                            <td class="fw-semibold">{{ $ton->so_lo }}</td>
                            <td>{{ $ton->han_su_dung->format('d/m/Y') }}</td>
                            <td>{{ $ton->ngay_nhap_lo ? $ton->ngay_nhap_lo->format('d/m/Y') : 'N/A' }}</td>
                            <td class="text-end">
                                @if($daysLeft < 0)
                                    <span class="text-danger fw-semibold">Quá hạn {{ abs((int)$daysLeft) }} ngày</span>
                                @elseif($daysLeft <= 60)
                                    <span class="text-warning fw-semibold">Còn {{ (int)$daysLeft }} ngày</span>
                                @else
                                    <span class="text-success">{{ (int)$daysLeft }} ngày</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($ton->so_luong_ton) }}</td>
                            <td class="text-end">{{ number_format($ton->so_luong_da_xuat) }}</td>
                            <td>
                                <form action="{{ route('batches.updateStatus') }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="ma_thuoc" value="{{ $ton->ma_thuoc }}">
                                    <input type="hidden" name="ma_phieu_nhap" value="{{ $ton->ma_phieu_nhap }}">
                                    <input type="hidden" name="so_lo" value="{{ $ton->so_lo }}">
                                    <select name="trang_thai_lo" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
                                        <option value="cho_duyet" {{ $ton->trang_thai_lo == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                                        <option value="dang_ban" {{ $ton->trang_thai_lo == 'dang_ban' ? 'selected' : '' }}>Đang bán</option>
                                        <option value="het_han" {{ $ton->trang_thai_lo == 'het_han' ? 'selected' : '' }}>Hết hạn</option>
                                        <option value="ngung_ban" {{ $ton->trang_thai_lo == 'ngung_ban' ? 'selected' : '' }}>Ngưng bán</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 150px;" title="{{ $ton->phieuNhap->nhaCungCap->ten_ncc ?? 'N/A' }}">
                                    {{ $ton->phieuNhap->nhaCungCap->ten_ncc ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($daysLeft < 0)
                                    <span class="badge bg-danger">Hết hạn</span>
                                @elseif($daysLeft <= 60)
                                    <span class="badge bg-warning">Sắp hết hạn</span>
                                @else
                                    <span class="badge bg-success">An toàn</span>
                                @endif
                                
                                <div class="mt-1 d-flex justify-content-center gap-1">
                                    @if($ton->image1)<i class="bi bi-card-image text-primary" title="Có ảnh lưu kho 1"></i>@endif
                                    @if($ton->image2)<i class="bi bi-card-image text-primary" title="Có ảnh lưu kho 2"></i>@endif
                                    @if($ton->image3)<i class="bi bi-card-image text-primary" title="Có ảnh lưu kho 3"></i>@endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted small py-4">
                                Không tìm thấy lô hàng nào phù hợp với bộ lọc hiển thị hoặc kho trống.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($ton_khos->hasPages())
                <div class="px-3 pt-3">
                    {{ $ton_khos->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

