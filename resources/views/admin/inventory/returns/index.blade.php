@extends('layouts.app')

@section('title', 'Quản lý yêu cầu Trả hàng')

@section('content')
<div class="container-fluid content-padding">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-bold"><i class="bi bi-arrow-return-left me-2"></i>Yêu cầu Trả hàng</h5>
            <p class="text-muted mb-0">Quản lý và duyệt các yêu cầu trả hàng từ khách hàng</p>
        </div>
        @if(Auth::guard('admin')->user()->hasRole(1, 3, 5))
        <a href="{{ route('admin.returns.create') }}" class="btn btn-warning fw-semibold shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tạo đơn trả hàng (NV)
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Mã Trả / Đơn Gốc</th>
                            <th>Ngày yêu cầu</th>
                            <th>Đại lý / Khách hàng</th>
                            <th>Lý do</th>
                            <th class="text-end">Hoàn tiền dự kiến</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="pe-4 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($traHangs as $th)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-danger">{{ $th->ma_tra_hang }}</div>
                                    <div class="small text-muted"><i class="bi bi-link-45deg"></i> {{ $th->ma_don_hang }}</div>
                                </td>
                                <td>{{ $th->ngay_yeu_cau->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-medium">{{ $th->khachHang->ten_kh ?? $th->ma_kh }}</div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $th->ly_do_chung }}">
                                        {{ $th->ly_do_chung }}
                                    </div>
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    {{ number_format($th->tong_tien_hoan_tra) }}đ
                                </td>
                                <td class="text-center">
                                    @if($th->trang_thai == 'cho_duyet') <span class="badge bg-warning">Chờ duyệt</span>
                                    @elseif($th->trang_thai == 'da_duyet_nhap_kho') <span class="badge bg-success">Đã nhập kho (KV04)</span>
                                    @elseif($th->trang_thai == 'tu_choi') <span class="badge bg-danger">Từ chối</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.returns.show', $th->ma_tra_hang) }}" class="btn btn-sm btn-outline-primary">
                                        Chi tiết & Duyệt <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Chưa có yêu cầu trả hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($traHangs->hasPages())
                <div class="p-3 border-top">
                    {{ $traHangs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
