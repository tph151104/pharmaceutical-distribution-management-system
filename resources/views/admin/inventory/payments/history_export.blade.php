<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                BÁO CÁO LỊCH SỬ THANH TOÁN ({{ $tab == 'nhap' ? 'CÔNG NỢ PHẢI TRẢ NCC' : 'CÔNG NỢ PHẢI THU KHÁCH HÀNG' }})
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Giao Dịch</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Giao Dịch</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">{{ $tab == 'nhap' ? 'Tên Nhà Cung Cấp' : 'Tên Khách Hàng' }}</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Phương Thức</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Loại Giao Dịch</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Tiền Giao Dịch (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ghi Chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $tx)
        <tr>
            <td style="text-align: center;">{{ $tx->ma_thanh_toan }}</td>
            <td style="text-align: center;">{{ \Carbon\Carbon::parse($tx->ngay_thanh_toan)->format('d/m/Y H:i:s') }}</td>
            <td style="text-align: center;">{{ $tab == 'nhap' ? $tx->ma_phieu_nhap : $tx->ma_phieu_xuat }}</td>
            <td>{{ $tab == 'nhap' ? ($tx->phieuNhap->nhaCungCap->ten_ncc ?? 'N/A') : ($tx->phieuXuat->khachHang->ten_kh ?? 'N/A') }}</td>
            <td style="text-align: center;">{{ $tx->phuong_thuc_tt }}</td>
            <td style="text-align: center;">{{ $tab == 'nhap' ? 'Chi trả nợ NCC' : 'Thu nợ từ KH' }}</td>
            <td style="text-align: right;">{{ $tx->so_tien_tt }}</td>
            <td>{{ $tx->ghi_chu }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align: right; font-weight: bold;">Tổng cộng đã thanh toán kỳ này:</td>
            <td style="text-align: right; font-weight: bold; color: red;">{{ $transactions->sum('so_tien_tt') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
