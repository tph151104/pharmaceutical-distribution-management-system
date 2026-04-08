<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="11" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                LỊCH SỬ DỊCH CHUYỂN KHO
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu Chuyển</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Thời Gian</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Người Thực Hiện</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Từ Khu Vực</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Đến Khu Vực</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Thuốc</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tên Thuốc</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Lô</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Phiếu Nhập</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Lượng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ghi Chú</th>
        </tr>
    </thead>
    <tbody>
        @foreach($histories as $log)
        <tr>
            <td style="text-align: center;">{{ $log->ma_phieu_chuyen }}</td>
            <td style="text-align: center;">{{ $log->ngay_chuyen ? \Carbon\Carbon::parse($log->ngay_chuyen)->format('d/m/Y H:i:s') : 'N/A' }}</td>
            <td>{{ $log->nguoiThucHien->ho_ten ?? $log->nguoi_thuc_hien }}</td>
            <td style="text-align: center;">{{ $log->tuKhuVucKho->ten_khu_vuc ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $log->denKhuVucKho->ten_khu_vuc ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $log->ma_thuoc }}</td>
            <td>{{ $log->thuoc->ten_thuoc ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $log->so_lo }}</td>
            <td style="text-align: center;">{{ $log->ma_phieu_nhap }}</td>
            <td style="text-align: right;">{{ $log->so_luong_chuyen }}</td>
            <td>{{ $log->ly_do_chuyen }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
