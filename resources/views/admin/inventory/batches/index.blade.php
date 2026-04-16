@extends('layouts.app')

@section('title', 'Tồn kho chi tiết theo lô hàng')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-layers-fill "></i>
                <h1 class="content-header-title mb-0">Tồn kho theo lô hàng</h1>
            </div>
            <div class="text-muted small">
                Xem chi tiết tồn kho đến từng lô: số lô, hạn dùng, tồn hiện tại, giá vốn và nhà cung cấp.
            </div>
        </div>
        
    </div>
@endsection

@section('content')
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                        <th class="text-nowrap text-center">Hạn dùng</th>
                        <th class="text-nowrap">Ngày nhập lô</th>
                        <th class="text-nowrap text-end">Tồn lô</th>
                        <th class="text-nowrap text-end">SL có thể bán</th>
                        <th class="text-nowrap text-end">SL đã xuất</th>
                        <th class="text-nowrap">Trạng thái lô</th>
                        <th class="text-nowrap">Nhà cung cấp</th>
                        <th class="text-nowrap text-center">Tình trạng</th>
                        @if(Auth::guard('admin')->user()->hasRole(1, 2, 5))
                        <th class="text-nowrap text-center">Thao tác</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ton_khos as $ton)
                        @php
                            $now = \Carbon\Carbon::now();
                            $hanDung = $ton->han_su_dung;
                            $daysLeft = $now->diffInDays($hanDung, false);
                            
                            $locationsData = \App\Models\TonKhoKhuVuc::with('khuVuc')
                                ->where('ma_thuoc', $ton->ma_thuoc)
                                ->where('ma_phieu_nhap', $ton->ma_phieu_nhap)
                                ->where('so_lo', $ton->so_lo)
                                ->get()
                                ->map(function($kv) {
                                    return [
                                        'khu_vuc' => $kv->khuVuc->ten_khu_vuc ?? $kv->ma_khu_vuc,
                                        'so_luong' => $kv->so_luong
                                    ];
                                })->values()->toArray();
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
                            <td class="text-center">
                                <div class="fw-semibold">{{ $ton->han_su_dung->format('d/m/Y') }}</div>
                                <div class="small mt-1">
                                    @if($daysLeft < 0)
                                        <span class="badge bg-danger">Quá hạn {{ abs((int)$daysLeft) }} ngày</span>
                                    @elseif($daysLeft <= 60)
                                        <span class="badge bg-warning text-dark">Còn {{ (int)$daysLeft }} ngày</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-75">Còn {{ (int)$daysLeft }} ngày</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $ton->ngay_nhap_lo ? $ton->ngay_nhap_lo->format('d/m/Y') : 'N/A' }}</td>
                            <td class="text-end fw-semibold">{{ number_format($ton->so_luong_ton) }}</td>
                            <td class="text-end fw-semibold text-success text-center">{{ number_format($ton->sl_co_the_ban) }}</td>
                            <td class="text-end text-center">{{ number_format($ton->so_luong_da_xuat) }}</td>
                            <td>
                                @if($ton->trang_thai_lo == 'cho_duyet') <span class="badge bg-secondary">Chờ duyệt</span>
                                @elseif($ton->trang_thai_lo == 'dang_ban') <span class="badge bg-success">Đang bán</span>
                                @elseif($ton->trang_thai_lo == 'ngung_ban') <span class="badge bg-warning">Ngưng bán</span>
                                @elseif($ton->trang_thai_lo == 'het_han') <span class="badge bg-danger">Hết hạn</span>
                                @else <span class="badge bg-light text-dark">{{ $ton->trang_thai_lo }}</span>
                                @endif
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
                            </td>
                            @if(Auth::guard('admin')->user()->hasRole(1, 2, 5))
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-info" title="Xem vị trí phân bổ"
                                    data-thuoc="{{ $ton->thuoc->ten_thuoc ?? $ton->ma_thuoc }}"
                                    data-lo="{{ $ton->so_lo }}"
                                    data-locations="{{ htmlspecialchars(json_encode($locationsData)) }}"
                                    onclick="openLocationsModal(this)">
                                    <i class="bi bi-geo-alt"></i>
                                </button>
                                @if($ton->trang_thai_lo == 'dang_ban')
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1" title="Ngưng bán / Cách ly"
                                    onclick="openStopSellingModal('{{ $ton->ma_thuoc }}', '{{ $ton->ma_phieu_nhap }}', '{{ $ton->so_lo }}', '{{ addslashes($ton->thuoc->ten_thuoc ?? 'N/A') }}', {{ $ton->sl_co_the_ban }})">
                                    <i class="bi bi-pause-circle"></i>
                                </button>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted small py-4">
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

    @if(Auth::guard('admin')->user()->hasRole(1, 2, 5))
    <!-- Modal Xem Vị Trí Phân Bổ -->
    <div class="modal fade" id="modalLocations" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-geo-alt me-1"></i> Chi tiết vị trí lưu trữ lô hàng</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="fw-bold">Thuốc:</span> <span id="loc_thuoc_name" class="text-primary fw-semibold"></span><br>
                        <span class="fw-bold">Số lô:</span> <span id="loc_so_lo" class="text-secondary fw-semibold"></span>
                    </div>
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Khu vực lưu trữ</th>
                                <th class="text-end" style="width: 120px;">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody id="loc_table_body">
                            <!-- JS will populate here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalStopSelling" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('batches.stopSelling') }}" method="POST">
                @csrf
                <input type="hidden" name="ma_thuoc" id="stop_ma_thuoc">
                <input type="hidden" name="ma_phieu_nhap" id="stop_ma_phieu_nhap">
                <input type="hidden" name="so_lo" id="stop_so_lo">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title"><i class="bi bi-pause-circle me-1"></i> Ngưng bán / Chuyển xử lý (Cách ly)</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-secondary small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Thao tác này sẽ chuyển trạng thái của lô thành "Ngưng bán" và yêu cầu bạn chuyển một số lượng tồn kho (hiện đang ở Kho Thành phẩm) vào Kho 04 (Chờ xử lý). 
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sản phẩm</label>
                            <input type="text" id="stop_ten_thuoc" class="form-control bg-light" readonly disabled>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">SL có thể bán (KV03)</label>
                                <input type="number" id="stop_ton_hien_tai" class="form-control bg-light" readonly disabled>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-danger">SL chuyển đi cách ly <span class="text-danger">*</span></label>
                                <input type="number" name="so_luong_chuyen" id="stop_so_luong_chuyen" class="form-control border-danger fw-bold text-danger" min="0" required>
                                <div class="form-text">Nhập 0 nếu bạn chỉ muốn đổi trạng thái.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lý do ngưng bán <span class="text-danger">*</span></label>
                            <textarea name="ly_do" class="form-control" rows="2" required placeholder="Ghi rõ lý do ngưng bán..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Xác nhận ngưng bán lô này và chuyển số lượng tương ứng vào kho KV04?')">
                            <i class="bi bi-check-lg me-1"></i> Xác nhận
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

@push('scripts')
<script>
    function openLocationsModal(btn) {
        document.getElementById('loc_thuoc_name').textContent = btn.getAttribute('data-thuoc');
        document.getElementById('loc_so_lo').textContent = btn.getAttribute('data-lo');
        
        let locations = [];
        try {
            locations = JSON.parse(btn.getAttribute('data-locations') || '[]');
        } catch(e) {}

        const tbody = document.getElementById('loc_table_body');
        tbody.innerHTML = '';
        
        let hasValidLocation = false;
        if (locations.length > 0) {
            locations.forEach(loc => {
                if (loc.so_luong > 0) {
                    hasValidLocation = true;
                    tbody.innerHTML += `<tr>
                        <td><span class="badge bg-light text-dark border border-secondary"><i class="bi bi-box me-1"></i> ${loc.khu_vuc}</span></td>
                        <td class="text-end fw-bold text-primary px-3">${loc.so_luong}</td>
                    </tr>`;
                }
            });
        }
        
        if (!hasValidLocation) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted small py-4 fst-italic">Lô hàng này hiện không có tồn trong các khu vực lưu trữ (hoặc số lượng bằng 0).</td></tr>';
        }
        
        new bootstrap.Modal(document.getElementById('modalLocations')).show();
    }

    function openStopSellingModal(maThuoc, maPhieuNhap, soLo, tenThuoc, tonHienTai) {
        document.getElementById('stop_ma_thuoc').value = maThuoc;
        document.getElementById('stop_ma_phieu_nhap').value = maPhieuNhap;
        document.getElementById('stop_so_lo').value = soLo;
        document.getElementById('stop_ten_thuoc').value = tenThuoc;
        document.getElementById('stop_ton_hien_tai').value = tonHienTai;
        document.getElementById('stop_so_luong_chuyen').value = tonHienTai;
        document.getElementById('stop_so_luong_chuyen').max = tonHienTai;
        new bootstrap.Modal(document.getElementById('modalStopSelling')).show();
    }
</script>
@endpush
@endsection
