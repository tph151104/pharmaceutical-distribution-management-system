@extends('layouts.app')

@section('title', 'Chi tiết Yêu cầu Trả hàng')

@section('content')
<div class="container-fluid content-padding">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('admin.returns.index') }}" class="btn btn-light btn-sm me-3 border shadow-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
        <h4 class="mb-0 fw-bold text-primary">Duyệt Yêu cầu Trả hàng: {{ $traHang->ma_tra_hang }}</h4>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Thông tin Yêu cầu -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Chi tiết Hàng hóa cần trả</h6></div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-end">Đơn giá trả</th>
                                <th class="text-center">SL Trả</th>
                                <th>Lý do</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traHang->chiTiet as $ct)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $ct->thuoc->ten_thuoc ?? $ct->ma_thuoc }}</div>
                                        <div class="small text-muted">{{ $ct->ma_thuoc }}</div>
                                    </td>
                                    <td class="text-end">{{ number_format($ct->don_gia_tra) }}đ</td>
                                    <td class="text-center fw-bold text-danger">{{ $ct->so_luong_tra }}</td>
                                    <td><span class="fst-italic text-muted">{{ $ct->ly_do_chi_tiet }}</span></td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($ct->thanh_tien) }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Tổng hoàn tiền:</td>
                                <td class="text-end text-danger fw-bold fs-5">{{ number_format($traHang->tong_tien_hoan_tra) }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Ghi chú chung -->
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-light rounded">
                    <h6 class="fw-bold text-dark"><i class="bi bi-chat-quote me-2"></i>Lý do chi tiết khách hàng ghi chú:</h6>
                    <p class="mb-0 fst-italic">"{{ $traHang->ly_do_chung }}"</p>
                </div>
            </div>
        </div>

        <!-- Bảng điều khiển / Phê duyệt -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Thông tin tham chiếu</h6></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Đơn hàng gốc:</strong> <a href="{{ route('admin.orders.show', $traHang->ma_don_hang) }}" target="_blank">{{ $traHang->ma_don_hang }}</a></p>
                    <p class="mb-2"><strong>Khách hàng:</strong> {{ $traHang->khachHang->ten_kh ?? $traHang->ma_kh }}</p>
                    <p class="mb-0"><strong>Trạng thái hiện tại:</strong> 
                        @if($traHang->trang_thai == 'cho_duyet') <span class="badge bg-warning">Chờ duyệt</span>
                        @elseif($traHang->trang_thai == 'da_duyet_nhap_kho') <span class="badge bg-success">Đã hoàn thành</span>
                        @elseif($traHang->trang_thai == 'tu_choi') <span class="badge bg-danger">Bị từ chối</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($traHang->trang_thai == 'cho_duyet')
            <div class="card border-0 shadow-sm border-top border-warning border-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Thao tác Duyệt</h6></div>
                <div class="card-body">
                    <div class="alert alert-warning small mb-3">
                        Duyệt thành công lệnh này, hệ thống sẽ:
                        <ul class="mb-0 ps-3 mt-1">
                            <li>Nhập thẳng hàng vào <b>Khu Vực Chờ Xử Lý (KV04)</b> để nhân viên xuống thẩm định.</li>
                            <li>Tạo đối soát hoàn tiền / giảm công nợ cho khách tự động.</li>
                        </ul>
                    </div>

                    <form action="{{ route('admin.returns.approve', $traHang->ma_tra_hang) }}" method="POST" id="formApprove" class="mb-2">
                        @csrf
                        <input type="hidden" name="ghi_chu" id="approve_note">
                        <button type="button" class="btn btn-success w-100 fw-bold py-2" onclick="submitApprove()">
                            <i class="bi bi-check-circle me-1"></i> Phê Duyệt & Nhập Kho Trả Hàng
                        </button>
                    </form>

                    <form action="{{ route('admin.returns.reject', $traHang->ma_tra_hang) }}" method="POST" id="formReject">
                        @csrf
                        <input type="hidden" name="ly_do" id="reject_reason">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="submitReject()">
                            <i class="bi bi-x-circle me-1"></i> Từ chối
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm border-top border-secondary border-3">
                <div class="card-body">
                    <h6 class="fw-bold">Lịch sử Duyệt</h6>
                    <p class="mb-1 small">Người xử lý: <strong>{{ $traHang->nguoi_duyet }}</strong></p>
                    <p class="mb-1 small">Ghi chú: <em>{{ $traHang->ghi_chu_admin ?? 'Không có' }}</em></p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function submitApprove() {
        let note = prompt('Ghi chú duyệt/chuyển khoản hoàn tiền (Sẽ lưu sổ quỹ):', 'Chuyển khoản hoàn trả khách.');
        if (note !== null) {
            document.getElementById('approve_note').value = note;
            document.getElementById('formApprove').submit();
        }
    }

    function submitReject() {
        let reason = prompt('Lý do từ chối (Khách hàng sẽ thấy nội dung này):', '');
        if (reason !== null && reason.trim() !== '') {
            document.getElementById('reject_reason').value = reason;
            document.getElementById('formReject').submit();
        } else if (reason !== null) {
            alert('Bạn phải nhập lý do từ chối!');
        }
    }
</script>
@endsection
