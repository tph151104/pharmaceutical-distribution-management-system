@extends('layouts.app')

@section('title', 'Kiểm hàng & Nhập kho')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('imports.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
                <h1 class="content-header-title mb-0">Kiểm Hàng Phiếu <strong>{{ $phieuNhap->ma_phieu_nhap }}</strong></h1>
            </div>
            <div class="text-muted small">
                Kiểm tra sản phẩm thực tế, điều chỉnh lô/HSD nếu sai lệch, chụp ảnh làm chứng và xác nhận nhập kho.
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
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cho phép thao tác confirm nếu chưa duyệt thành công -->
    @if(in_array($phieuNhap->trang_thai_phieu_nhap, ['cho_nhap_kho', 'doi_hang_ve']))
        <form action="{{ route('imports.confirm', $phieuNhap->ma_phieu_nhap) }}" method="POST" enctype="multipart/form-data">
            @csrf
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-9">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7 border-end">
                            <h6 class="text-primary mb-3"><i class="bi bi-file-earmark-text me-1"></i> Thông tin chứng từ</h6>
                            <div class="row g-2 small">
                                <div class="col-5 text-muted">Mã chứng từ:</div>
                                <div class="col-7 fw-semibold">{{ $phieuNhap->ma_phieu_nhap }}</div>
                                <div class="col-5 text-muted">Nhà cung cấp:</div>
                                <div class="col-7 fw-semibold">{{ $phieuNhap->nhaCungCap->ten_ncc ?? 'N/A' }}</div>
                                <div class="col-5 text-muted">Người lập:</div>
                                <div class="col-7 fw-semibold">{{ $phieuNhap->nguoiLap->ho_ten_nd ?? 'N/A' }}</div>
                                <div class="col-5 text-muted">Ngày ghi trên vé:</div>
                                <div class="col-7">{{ $phieuNhap->ngay_nhap->format('d/m/Y') }}</div>
                                <div class="col-5 text-muted">Trạng thái:</div>
                                <div class="col-7">
                                    @if($phieuNhap->trang_thai_phieu_nhap == 'cho_nhap_kho')
                                        <span class="badge bg-secondary">Chờ nhập kho</span>
                                    @elseif($phieuNhap->trang_thai_phieu_nhap == 'da_nhap_kho')
                                        <span class="badge bg-success">Thành công</span>
                                    @elseif($phieuNhap->trang_thai_phieu_nhap == 'doi_hang_ve')
                                        <span class="badge bg-warning">Đợi hàng về</span>
                                    @endif
                                </div>
                                @if($phieuNhap->giay_to_lien_quan || $phieuNhap->tieu_lieu_lien_quan)
                                    <div class="col-12 mt-3 pt-2 border-top">
                                        <div class="text-muted mb-1 small fw-semibold">Tài liệu đính kèm:</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($phieuNhap->giay_to_lien_quan)
                                                <a href="{{ asset($phieuNhap->giay_to_lien_quan) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none p-2">
                                                    <i class="bi bi-file-earmark-pdf"></i> Giấy tờ liên quan
                                                </a>
                                            @endif
                                            @if($phieuNhap->tieu_lieu_lien_quan)
                                                @if(Str::contains($phieuNhap->tieu_lieu_lien_quan, ['/', '\\', '.']))
                                                    <a href="{{ asset($phieuNhap->tieu_lieu_lien_quan) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none p-2">
                                                        <i class="bi bi-file-earmark-text"></i> Tài liệu liên quan
                                                    </a>
                                                @else
                                                    <span class="badge bg-light text-dark border p-2" title="Ghi chú hệ thống">
                                                        <i class="bi bi-info-circle me-1"></i> {{ $phieuNhap->tieu_lieu_lien_quan }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5 ps-md-4">
                            <h6 class="text-primary mb-3"><i class="bi bi-image me-1"></i> Hình ảnh tổng lô hàng</h6>
                            <div class="text-center">
                                @if($phieuNhap->image1)
                                    <div class="mb-2">
                                        <a href="javascript:void(0)">
                                            <img src="{{ asset($phieuNhap->image1) }}" class="img-thumbnail img-clickable" style="max-height: 120px; object-fit: contain;" alt="Ảnh tổng lô hàng" title="Click để phóng to">
                                        </a>
                                    </div>
                                @endif
                                @if(in_array($phieuNhap->trang_thai_phieu_nhap, ['cho_nhap_kho', 'doi_hang_ve']))
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="phieu_nhap_image" class="form-control" accept="image/*">
                                    </div>
                                    <div class="small text-muted mt-1">Chụp ảnh toàn bộ lô hàng khi vừa nhập về</div>
                                @elseif(!$phieuNhap->image1)
                                    <div class="py-3 text-muted border rounded bg-light small">
                                        <i class="bi bi-camera-off d-block fs-3 mb-1"></i>
                                        Không có ảnh tổng lô
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <div class="text-muted small text-uppercase mb-2 fw-bold">Tổng hàng trên giấy</div>
                    <div class="h2 mb-0 text-primary fw-bold">{{ number_format($phieuNhap->chiTiet->sum('so_luong_nhap')) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if(in_array($phieuNhap->trang_thai_phieu_nhap, ['cho_nhap_kho', 'doi_hang_ve']))
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary"><i class="bi bi-box-seam me-1"></i> Đối chiếu chi tiết</h6>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="form-check m-0" title="Tick vào đây nếu NCC không giao thêm hàng nữa. Phiếu sẽ đóng và chốt công nợ theo số lượng thực nhận.">
                            <input class="form-check-input" type="checkbox" name="force_close" id="forceCloseCheck" value="1">
                            <label class="form-check-label text-danger small fw-bold" for="forceCloseCheck">
                                <i class="bi bi-exclamation-triangle"></i> Ép đóng phiếu (Nhận thiếu)
                            </label>
                        </div>
                        <!-- Nút xác nhận hoàn tất -->
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc chắn muốn xác nhận nhập kho? Số lượng thực tế sẽ ghi nhận vào Tồn Kho.');">
                            <i class="bi bi-check2-circle me-1"></i> Hoàn tất & Nhập kho
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light small text-muted text-center">
                                <tr>
                                    <th width="200">Sản phẩm</th>
                                    <th>Số lượng theo CT</th>
                                    <th>Số Lô (Nội bộ)</th>
                                    <th>Số Lô SX</th>
                                    <th>NSX/ SĐK</th>
                                    <th width="150">Hạn Sử Dụng</th>
                                    <th width="100">SL Thực Nhận</th>
                                    <th width="250">Ghi nhận hình ảnh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($phieuNhap->chiTiet as $index => $item)
                                    @php
                                        $tonKey = $item->ma_thuoc . '_' . $item->so_lo;
                                        $tonModel = $tonKhos->get($tonKey);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                            <div class="small text-muted">{{ $item->thuoc->ma_thuoc ?? $item->ma_thuoc }}</div>
                                            <!-- Lưu key chìm để controller biết đang duyệt dòng nào -->
                                            <input type="hidden" name="items[{{$index}}][ma_thuoc]" value="{{ $item->ma_thuoc }}">
                                            <input type="hidden" name="items[{{$index}}][original_so_lo]" value="{{ $item->so_lo }}">
                                        </td>
                                        <td class="text-center small">
                                            <div class="text-muted">SL: <span class="fw-bold text-dark">{{ $item->so_luong_nhap }}</span></div>
                                            <div class="text-muted">Lô: {{ $item->so_lo }}</div>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{$index}}][so_lo]" class="form-control form-control-sm text-center bg-light" value="{{ old('items.'.$index.'.so_lo', $item->so_lo) }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{$index}}][so_lo_sx]" class="form-control form-control-sm text-center bg-light" value="{{ old('items.'.$index.'.so_lo_sx', $item->so_lo_sx) }}" readonly>
                                        </td>
                                        <td>
                                            <div class="mb-1"><span class="small text-muted">NSX:</span> <strong>{{ $item->ngay_san_xuat ? $item->ngay_san_xuat->format('d/m/Y') : 'N/A' }}</strong></div>
                                            <div><span class="small text-muted">SĐK:</span> <strong>{{ $item->so_dang_ky ?? 'N/A' }}</strong></div>
                                            <input type="hidden" name="items[{{$index}}][ngay_san_xuat]" value="{{ $item->ngay_san_xuat ? $item->ngay_san_xuat->format('Y-m-d') : '' }}">
                                            <input type="hidden" name="items[{{$index}}][so_dang_ky]" value="{{ $item->so_dang_ky }}">
                                        </td>
                                        <td>
                                            @php
                                                $today = date('Y-m-d');
                                                $nsx = $item->ngay_san_xuat ? $item->ngay_san_xuat->format('Y-m-d') : $today;
                                                $minExpiry = $nsx > $today ? $nsx : $today;
                                                $currentHsd = $item->han_su_dung ? $item->han_su_dung->format('Y-m-d') : '';
                                                // Kiểm tra xem lô này đã từng có hàng nhập kho chưa
                                                $hasStock = ($tonModel && ($tonModel->so_luong_ton + $tonModel->so_luong_da_xuat > 0));
                                            @endphp
                                            <input type="date" name="items[{{$index}}][han_su_dung]" 
                                                class="form-control form-control-sm text-center input-hsd" 
                                                min="{{ $minExpiry }}"
                                                data-original="{{ $currentHsd }}"
                                                data-has-stock="{{ $hasStock ? 'true' : 'false' }}"
                                                data-lo="{{ $item->so_lo }}"
                                                value="{{ old('items.'.$index.'.han_su_dung', $currentHsd) }}" 
                                                required>
                                        </td>
                                        <td>
                                            <!-- Số lượng thực tế màu viền khác nhau nếu bé hơn số lượng CT -->
                                            <input type="number" name="items[{{$index}}][so_luong_thuc_te]" 
                                                class="form-control form-control-sm text-center fw-bold text-primary" 
                                                value="{{ old('items.'.$index.'.so_luong_thuc_te', $item->so_luong_thuc_te ?? 0) }}" min="{{ $item->so_luong_thuc_te ?? 0 }}" max="{{ $item->so_luong_nhap }}" required>   
                                        </td>
                                        <td>
                                            <!-- Ảnh chi tiết lô hàng (lưu vào TonKho.image1) -->
                                            <div>
                                                <label class="form-label small text-muted mb-0"><i class="bi bi-camera me-1"></i>Ảnh chi tiết lô</label>
                                                <div class="d-flex gap-1 align-items-center">
                                                    @if($tonModel && $tonModel->image1) <img src="{{ asset($tonModel->image1) }}" width="44" height="44" class="rounded object-fit-cover shadow-sm border"> @endif
                                                    <input type="file" name="image_lot_{{ $item->ma_thuoc }}_{{ $item->so_lo }}" class="form-control form-control-sm" style="font-size: 10px;" accept="image/*">
                                                </div>
                                                <div class="small text-muted" style="font-size: 9px;">Ảnh sản phẩm thực tế trong lô</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Form upload tài liệu chung của phiếu nhập -->
                    <div class="row g-3 p-3 bg-light border-top border-bottom-0 m-0">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold mb-1">Tải lên giấy chứng nhận an toàn</label>
                            <input type="file" name="giay_to_lien_quan" class="form-control" accept="image/*,.pdf">
                            @if($phieuNhap->giay_to_lien_quan)
                                <div class="mt-1 small"><a href="{{ asset($phieuNhap->giay_to_lien_quan) }}" target="_blank"><i class="bi bi-file-earmark-check"></i> Đã tải lên tài liệu</a></div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold mb-1">Tải lên Tài liệu liên quan (Tùy chọn)</label>
                            <input type="file" name="tieu_lieu_lien_quan" class="form-control" accept="image/*,.pdf">
                            @if($phieuNhap->tieu_lieu_lien_quan)
                                <div class="mt-1 small"><a href="{{ asset($phieuNhap->tieu_lieu_lien_quan) }}" target="_blank"><i class="bi bi-file-earmark-check"></i> Đã tải lên tài liệu</a></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <!-- Nếu đã nhập xong, chỉ Read-only -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center text-success bg-success bg-opacity-10 py-4">
                <i class="bi bi-check-circle display-4 mb-2"></i>
                <h5 class="mb-0">Phiếu kiểm hàng đã hoàn tất nhập kho</h5>
                <p class="text-muted small mt-1">Các lô hàng thực tế đã được phản ánh vào hệ thống tồn kho.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h6 class="mb-0 text-primary"><i class="bi bi-box-seam me-1"></i> Đối chiếu chi tiết (Đã khóa)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light small text-muted text-center">
                            <tr>
                                <th width="200">Sản phẩm</th>
                                <th>Theo CT</th>
                                <th>Lô/ Số lô SX </th>
                                <th>NSX / SĐK / HSD</th>
                                <th>SL Thực tế</th>
                                <th>Diễn giải kết quả</th>
                                <th width="200">Ghi nhận hình ảnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phieuNhap->chiTiet as $item)
                                @php
                                    // Tìm dữ liệu tồn kho hiện tại để lấy ảnh
                                    $tonKey = $item->ma_thuoc . '_' . $item->so_lo;
                                    $tonModel = $tonKhos->get($tonKey);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                    </td>
                                    <td class="text-center small">
                                        <div class="text-muted">SL: <span class="fw-bold text-dark">{{ $item->so_luong_nhap }}</span></div>
                                    </td>
                                    <td class="text-center small">
                                        <div>Lô NB: <b>{{ $item->so_lo }}</b></div>
                                        <div>Lô SX: <b>{{ $item->so_lo_sx }}</b></div>
                                    </td>
                                    <td class="text-center small">
                                        <div>NSX: <b>{{ $item->ngay_san_xuat ? $item->ngay_san_xuat->format('d/m/Y') : 'N/A' }}</b></div>
                                        <div>SĐK: <b>{{ $item->so_dang_ky ?? 'N/A' }}</b></div>
                                        <div>HSD: {{ $item->han_su_dung ? $item->han_su_dung->format('d/m/Y') : '' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $item->so_luong_thuc_te < $item->so_luong_nhap ? 'bg-warning text-dark' : 'bg-success' }} fs-6">
                                            {{ $item->so_luong_thuc_te }}
                                        </span>
                                    </td>
                                    <td class="small text-muted text-center">
                                        @if($item->so_luong_thuc_te < $item->so_luong_nhap)
                                            <span class="text-danger">Thiếu {{ $item->so_luong_nhap - $item->so_luong_thuc_te }} so với C.Từ</span>
                                        @else
                                            <span class="text-success">Khớp số lượng</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">
                                            @if($tonModel && $tonModel->image1)
                                                <div class="text-center">
                                                    <img src="{{ asset($tonModel->image1) }}" width="60" height="60" class="rounded object-fit-cover shadow-sm border img-clickable" title="Click để phóng to xem kỹ hơn">
                                                    <div class="small text-muted" style="font-size: 9px;">Ảnh chi tiết</div>
                                                </div>
                                            @else
                                                <span class="text-muted small">Không có ảnh chi tiết</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@push('scripts')
<script>
    document.querySelectorAll('.input-hsd').forEach(function(input) {
        input.addEventListener('change', function() {
            const hasStock = this.getAttribute('data-has-stock') === 'true';
            const originalDate = this.getAttribute('data-original');
            const newDate = this.value;
            const soLo = this.getAttribute('data-lo');

            if (hasStock && originalDate && newDate !== originalDate) {
                const confirmChange = confirm(
                    `⚠️ CẢNH BÁO QUAN TRỌNG:\n\n` +
                    `Lô hàng [${soLo}] này đã có số lượng tồn kho từ các đợt nhập trước.\n\n` +
                    `Việc thay đổi Hạn sử dụng sẽ áp dụng cho TOÀN BỘ sản phẩm trong lô này (bao gồm cả hàng cũ đã nhập).\n\n` +
                    `Nếu đợt hàng mới này có hạn dùng khác, bạn nên đổi "Số Lô Nội Bộ" để quản lý tách biệt.\n\n` +
                    `Bạn có chắc chắn vẫn muốn ghi đè hạn dùng mới cho cả lô không?`
                );

                if (!confirmChange) {
                    this.value = originalDate;
                }
            }
        });
    });
</script>

@if(in_array($phieuNhap->trang_thai_phieu_nhap, ['cho_nhap_kho', 'doi_hang_ve']))
<script>
    // === AJAX Polling: Kiểm tra trạng thái phiếu nhập mỗi 5 giây ===
    // Nếu người khác đã xác nhận phiếu trước, sẽ hiện cảnh báo ngay lập tức
    (function() {
        const maPhieuNhap = @json($phieuNhap->ma_phieu_nhap);
        const checkUrl = "{{ route('imports.checkStatus', $phieuNhap->ma_phieu_nhap) }}";
        const currentStatus = @json($phieuNhap->trang_thai_phieu_nhap);
        let polling = null;

        function kiemTraTrangThai() {
            fetch(checkUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                // Nếu trạng thái đã thay đổi
                if (data.trang_thai !== currentStatus) {
                    // Dừng polling
                    clearInterval(polling);

                    // Hiện banner cảnh báo nổi bật
                    let banner = document.createElement('div');
                    banner.className = 'alert alert-danger border-danger shadow-lg d-flex align-items-center gap-3';
                    banner.style.cssText = 'position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:9999; min-width:500px; animation: fadeInDown 0.5s;';
                    banner.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        <div>
                            <strong>⚠ Phiếu này đã được cập nhật hoặc xác nhận bởi người khác!</strong><br>
                            Trạng thái hiện tại: <span class="badge bg-danger">${data.ten_trang_thai}</span><br>
                            <small class="text-muted">Trang sẽ tự động tải lại sau 5 giây...</small>
                        </div>
                    `;
                    document.body.prepend(banner);

                    // Vô hiệu hóa nút xác nhận để không cho bấm nữa
                    let btnConfirm = document.querySelector('button[type="submit"]');
                    if (btnConfirm) {
                        btnConfirm.disabled = true;
                        btnConfirm.classList.add('btn-secondary');
                        btnConfirm.classList.remove('btn-primary', 'btn-success', 'btn-info');
                        btnConfirm.innerHTML = '<i class="bi bi-lock me-2"></i> Đã bị khóa';
                    }

                    // Tự động tải lại trang sau 5 giây
                    setTimeout(() => location.reload(), 5000);
                }
            })
            .catch(() => {
                // Lỗi mạng thì bỏ qua, lần sau sẽ thử lại
            });
        }

        // Bắt đầu polling mỗi 5 giây
        polling = setInterval(kiemTraTrangThai, 4000);
    })();
</script>

<style>
    @keyframes fadeInDown {
        from { opacity: 0; transform: translate(-50%, -30px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
</style>
@endif
@endpush
@endsection
