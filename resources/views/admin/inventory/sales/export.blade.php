<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                DANH SÁCH PHIẾU XUẤT KHO
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu Xuất</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Xuất</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Khách Hàng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Trạng Thái Xuất Kho</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Trạng Thái Thanh Toán</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tổng Tiền Xuất (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ghi Chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach($phieuXuats as $px)
        <tr>
            <td style="text-align: center;">{{ $px->ma_phieu_xuat }}</td>
            <td style="text-align: center;">{{ $px->ngay_xuat ? \Carbon\Carbon::parse($px->ngay_xuat)->format('d/m/Y H:i:s') : 'N/A' }}</td>
            <td>{{ $px->khachHang->ten_kh ?? 'Khách Lẻ' }}</td>
            <td style="text-align: center;">
                @if($px->trang_thai_phieu_xuat == 'hoan_thanh') Hoàn thành 
                @elseif($px->trang_thai_phieu_xuat == 'dang_giao_hang') Đang giao hàng
                @elseif($px->trang_thai_phieu_xuat == 'huy_bo') Hủy bỏ 
                @else Đang chuẩn bị @endif
            </td>
            <td style="text-align: center;">
                @if($px->trang_thai_tt == 'da_tt') Đã thanh toán 
                @elseif($px->trang_thai_tt == 'mot_phan') Thanh toán một phần 
                @else Chưa thanh toán @endif
            </td>
            <td style="text-align: right;">{{ $px->tong_tien }}</td>
            <td>{{ $px->ghi_chu }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right; font-weight: bold;">Tổng tiền các phiếu xuất:</td>
            <td style="text-align: right; font-weight: bold; color: red;">{{ $phieuXuats->sum('tong_tien') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
