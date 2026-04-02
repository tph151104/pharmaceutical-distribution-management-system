<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                BÁO CÁO CÔNG NỢ {{ $type == 'nhap' ? 'PHẢI TRẢ (NHÀ CUNG CẤP)' : 'PHẢI THU (KHÁCH HÀNG)' }}
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu {{ $type == 'nhap' ? 'Nhập' : 'Xuất' }}</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Lập</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">{{ $type == 'nhap' ? 'Tên Nhà Cung Cấp' : 'Tên Khách Hàng' }}</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tổng Tiền (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Đã Thanh Toán (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Còn Nợ (VNĐ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($phieus as $phieu)
        <tr>
            <td style="text-align: center;">{{ $type == 'nhap' ? $phieu->ma_phieu_nhap : $phieu->ma_phieu_xuat }}</td>
            <td style="text-align: center;">{{ \Carbon\Carbon::parse($type == 'nhap' ? $phieu->ngay_nhap : $phieu->ngay_xuat)->format('d/m/Y') }}</td>
            <td>{{ $type == 'nhap' ? ($phieu->nhaCungCap->ten_ncc ?? 'N/A') : ($phieu->khachHang->ten_kh ?? 'N/A') }}</td>
            <td style="text-align: right;">{{ $phieu->tong_tien }}</td>
            <td style="text-align: right;">{{ $phieu->so_tien_da_tra ?? 0 }}</td>
            <td style="text-align: right; color: red;">{{ $phieu->so_tien_con_no }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
            <td style="text-align: right; font-weight: bold;">{{ $phieus->sum('tong_tien') }}</td>
            <td style="text-align: right; font-weight: bold;">{{ $phieus->sum('so_tien_da_tra') }}</td>
            <td style="text-align: right; font-weight: bold; color: red;">{{ $phieus->sum('so_tien_con_no') }}</td>
        </tr>
    </tfoot>
</table>
