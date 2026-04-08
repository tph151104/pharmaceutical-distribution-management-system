<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                DANH SÁCH PHIẾU NHẬP KHO
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu Nhập</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Nhập</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Nhà Cung Cấp</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Trạng Thái Nhập</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Trạng Thái Thanh Toán</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tổng Tiền Nhập (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ghi Chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach($phieuNhaps as $pn)
        <tr>
            <td style="text-align: center;">{{ $pn->ma_phieu_nhap }}</td>
            <td style="text-align: center;">{{ $pn->ngay_nhap ? \Carbon\Carbon::parse($pn->ngay_nhap)->format('d/m/Y H:i:s') : 'N/A' }}</td>
            <td>{{ $pn->nhaCungCap->ten_ncc ?? 'N/A' }}</td>
            <td style="text-align: center;">
                @if($pn->trang_thai_phieu_nhap == 'hoan_thanh') Hoàn thành 
                @elseif($pn->trang_thai_phieu_nhap == 'cho_duyet') Chờ duyệt 
                @else Đang xử lý @endif
            </td>
            <td style="text-align: center;">
                @if($pn->trang_thai_tt == 'da_tt') Đã thanh toán 
                @elseif($pn->trang_thai_tt == 'mot_phan') Thanh toán một phần 
                @else Chưa thanh toán @endif
            </td>
            <td style="text-align: right;">{{ $pn->tong_tien }}</td>
            <td>{{ $pn->ghi_chu }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right; font-weight: bold;">Tổng tiền các phiếu nhập:</td>
            <td style="text-align: right; font-weight: bold; color: red;">{{ $phieuNhaps->sum('tong_tien') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
