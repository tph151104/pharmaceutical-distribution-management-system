<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                @if($type == 'nhap')
                    BÁO CÁO CÔNG NỢ PHẢI TRẢ (NHÀ CUNG CẤP)
                @elseif($type == 'xuat')
                    BÁO CÁO CÔNG NỢ PHẢI THU (KHÁCH HÀNG)
                @else
                    BÁO CÁO HOÀN TRẢ ĐƠN HÀNG (KHÁCH HÀNG)
                @endif
            </th>
        </tr>
        <tr>
            @if($type == 'tra_hang')
                <th style="font-weight: bold; background-color: #fff3cd; text-align: center;">Mã Đơn Trả</th>
                <th style="font-weight: bold; background-color: #fff3cd; text-align: center;">Ngày Duyệt</th>
                <th style="font-weight: bold; background-color: #fff3cd; text-align: center;">Khách Hàng</th>
                <th style="font-weight: bold; background-color: #fff3cd; text-align: center;">Tổng Cần Hoàn (VNĐ)</th>
                <th style="font-weight: bold; background-color: #fff3cd; text-align: center;">Đã Hoàn (VNĐ)</th>
                <th style="font-weight: bold; background-color: #fff3cd; text-align: center;">Còn Phải Hoàn (VNĐ)</th>
            @else
                <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu {{ $type == 'nhap' ? 'Nhập' : 'Xuất' }}</th>
                <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Lập</th>
                <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">{{ $type == 'nhap' ? 'Tên Nhà Cung Cấp' : 'Tên Khách Hàng' }}</th>
                <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tổng Tiền (VNĐ)</th>
                <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Đã Thanh Toán (VNĐ)</th>
                <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Còn Nợ (VNĐ)</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($phieus as $phieu)
        <tr>
            @if($type == 'tra_hang')
                <td style="text-align: center;">{{ $phieu->ma_tra_hang }}</td>
                <td style="text-align: center;">{{ $phieu->ngay_duyet ? \Carbon\Carbon::parse($phieu->ngay_duyet)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $phieu->khachHang->ten_kh ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ $phieu->tong_tien_hoan_tra }}</td>
                <td style="text-align: right;">{{ $phieu->so_tien_da_hoan ?? 0 }}</td>
                <td style="text-align: right; color: red;">{{ $phieu->so_tien_con_hoan }}</td>
            @else
                <td style="text-align: center;">{{ $type == 'nhap' ? $phieu->ma_phieu_nhap : $phieu->ma_phieu_xuat }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($type == 'nhap' ? $phieu->ngay_nhap : $phieu->ngay_xuat)->format('d/m/Y') }}</td>
                <td>{{ $type == 'nhap' ? ($phieu->nhaCungCap->ten_ncc ?? 'N/A') : ($phieu->khachHang->ten_kh ?? 'N/A') }}</td>
                <td style="text-align: right;">{{ $phieu->tong_tien }}</td>
                <td style="text-align: right;">{{ $phieu->so_tien_da_tra ?? 0 }}</td>
                <td style="text-align: right; color: red;">{{ $phieu->so_tien_con_no }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">Tổng cộng:</td>
            @if($type == 'tra_hang')
                <td style="text-align: right; font-weight: bold;">{{ $phieus->sum('tong_tien_hoan_tra') }}</td>
                <td style="text-align: right; font-weight: bold;">{{ $phieus->sum('so_tien_da_hoan') }}</td>
                <td style="text-align: right; font-weight: bold; color: red;">{{ $phieus->sum('so_tien_con_hoan') }}</td>
            @else
                <td style="text-align: right; font-weight: bold;">{{ $phieus->sum('tong_tien') }}</td>
                <td style="text-align: right; font-weight: bold;">{{ $phieus->sum('so_tien_da_tra') }}</td>
                <td style="text-align: right; font-weight: bold; color: red;">{{ $phieus->sum('so_tien_con_no') }}</td>
            @endif
        </tr>
    </tfoot>
</table>
