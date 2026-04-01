@extends('layouts.app')

@section('title', 'Danh sách phiếu nhập')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="content-header-title mb-0">Quản lý nhập hàng</h1>
            </div>
            <div class="text-muted small">
                Danh sách các phiếu lập nhập kho từ nhà cung cấp theo quy trình
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('imports.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Lập phiếu nhập
            </a>
        </div>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('imports.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted mb-1">Mã Phiếu</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nhập mã phiếu..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Trạng thái phiếu</label>
                    <select name="trang_thai" class="form-select form-select-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="cho_nhap_kho" {{ request('trang_thai') == 'cho_nhap_kho' ? 'selected' : '' }}>Chờ nhập kho</option>
                        <option value="da_nhap_kho" {{ request('trang_thai') == 'da_nhap_kho' ? 'selected' : '' }}>Đã nhập kho (Thành công)</option>
                        <option value="doi_hang_ve" {{ request('trang_thai') == 'doi_hang_ve' ? 'selected' : '' }}>Đợi hàng về kho</option>
                        <option value="da_huy" {{ request('trang_thai') == 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i>
                        Tìm phiếu
                    </button>
                    @if(request()->anyFilled(['search', 'trang_thai']))
                        <a href="{{ route('imports.index') }}" class="btn btn-light btn-sm mt-1">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="fw-semibold small text-uppercase text-muted">Danh sách chứng từ</div>
            <div class="small text-muted">
                Dữ liệu hiển thị: <span class="fw-semibold">{{ $phieuNhaps->total() }}</span> phiếu
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                    <tr>
                        <th class="text-nowrap">Mã Phiếu</th>
                        <th class="text-nowrap">Ngày nhập chứng từ</th>
                        <th class="text-nowrap">Nhà cung cấp</th>
                        <th class="text-nowrap text-end">Tổng tiền hóa đơn</th>
                        <th class="text-nowrap text-center">Trạng thái phiếu</th>
                        <th class="text-nowrap text-center">Kiểm hàng thực tế</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($phieuNhaps as $phieu)
                        <tr>
                            <td class="fw-semibold">{{ $phieu->ma_phieu_nhap }}</td>
                            <td>{{ $phieu->ngay_nhap->format('d/m/Y') }}</td>
                            <td>{{ $phieu->nhaCungCap->ten_ncc ?? 'N/A' }}</td>
                            <td class="text-end fw-semibold text-primary">{{ number_format($phieu->tong_tien, 0, ',', '.') }}đ</td>
                            <td class="text-center">
                                @if($phieu->trang_thai_phieu_nhap == 'cho_nhap_kho')
                                    <span class="badge bg-secondary">Chờ nhập kho</span>
                                @elseif($phieu->trang_thai_phieu_nhap == 'da_nhap_kho')
                                    <span class="badge bg-success">Thành công</span>
                                @elseif($phieu->trang_thai_phieu_nhap == 'doi_hang_ve')
                                    <span class="badge bg-warning">Đợi hàng về</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @if($phieu->trang_thai_phieu_nhap == 'doi_hang_ve')
                                        @php
                                            $daNhapMotPhan = $phieu->chiTiet->sum('so_luong_thuc_te') > 0;
                                        @endphp
                                        <!-- Actions when waiting for goods -->
                                        @if(!$daNhapMotPhan)
                                        <a href="{{ route('imports.edit', $phieu->ma_phieu_nhap) }}" class="btn btn-sm btn-outline-primary me-2" title="Sửa đơn hàng">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        @endif
                                        <form action="{{ route('imports.markArrived', $phieu->ma_phieu_nhap) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận hàng đã về kho? Bạn sẽ có thể thực hiện kiểm hàng sau bước này.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success me-2" title="Hàng đã về">
                                                <i class="bi bi-box-arrow-in-down"></i> {{ $daNhapMotPhan ? 'Về kho đợt tiếp' : 'Về kho' }}
                                            </button>
                                        </form>
                                        @if(!$daNhapMotPhan)
                                        <form action="{{ route('imports.destroy', $phieu->ma_phieu_nhap) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xoá phiếu nhập này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xoá phiếu">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                        @endif
                                    @elseif($phieu->trang_thai_phieu_nhap == 'cho_nhap_kho')
                                        <!-- Actions when goods have arrived, waiting for inspection -->
                                        <a href="{{ route('imports.inspect', $phieu->ma_phieu_nhap) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-box-seam me-1"></i> Thực hiện kiểm hàng
                                        </a>
                                    @else
                                        <!-- Finished or Canceled -->
                                        <a href="{{ route('imports.inspect', $phieu->ma_phieu_nhap) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Xem chi tiết
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted small py-4">
                                Không tìm thấy phiếu nhập nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($phieuNhaps->hasPages())
                <div class="px-3 pt-3">
                    {{ $phieuNhaps->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
