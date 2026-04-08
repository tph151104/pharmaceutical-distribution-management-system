<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                DANH SÁCH ĐƠN HÀNG SỈ
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Đơn Hàng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Đặt</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Khách Hàng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Điện Thoại</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Trạng Thái Đơn Hàng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tổng Tiền (VNĐ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($donHangs as $dh)
        <tr>
            <td style="text-align: center;">{{ $dh->ma_don_hang }}</td>
            <td style="text-align: center;">{{ $dh->ngay_dat ? \Carbon\Carbon::parse($dh->ngay_dat)->format('d/m/Y H:i:s') : 'N/A' }}</td>
            <td>{{ $dh->khachHang->ten_kh ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $dh->khachHang->dien_thoai ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $dh->tenTrangThai }}</td>
            <td style="text-align: right;">{{ $dh->tong_tien }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right; font-weight: bold;">Tổng tiền các đơn hàng:</td>
            <td style="text-align: right; font-weight: bold; color: red;">{{ $donHangs->sum('tong_tien') }}</td>
        </tr>
    </tfoot>
</table>
