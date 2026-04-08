<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="12" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                LỊCH SỬ XUẤT NHẬP KHO
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Thời Gian</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Log</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Người Thao Tác</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Chứng Từ</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Nguồn Giao Dịch</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Thuốc</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tên Thuốc</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Lô</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Loại Giao Dịch</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Lượng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tồn Trước</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tồn Sau</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td style="text-align: center;">{{ $log->thoi_gian->format('d/m/Y H:i:s') }}</td>
            <td style="text-align: center;">{{ $log->ma_log }}</td>
            <td>{{ $log->nguoiDung->ho_ten ?? $log->nguoi_thuc_hien }}</td>
            <td style="text-align: center;">{{ $log->ma_chung_tu }}</td>
            <td style="text-align: center;">{{ $log->nguon_giao_dich }}</td>
            <td style="text-align: center;">{{ $log->ma_thuoc }}</td>
            <td>{{ $log->thuoc->ten_thuoc ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $log->so_lo }}</td>
            <td style="text-align: center;">{{ $log->loai_giao_dich }}</td>
            <td style="text-align: right; {{ $log->loai_giao_dich == 'nhap' ? 'color: green;' : ($log->loai_giao_dich == 'xuat' ? 'color: red;' : 'color: orange;') }}">
                {{ $log->loai_giao_dich == 'nhap' ? '+' : ($log->loai_giao_dich == 'xuat' ? '-' : '') }}{{ $log->so_luong }}
            </td>
            <td style="text-align: right;">{{ $log->ton_truoc }}</td>
            <td style="text-align: right; font-weight: bold;">{{ $log->ton_sau }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
