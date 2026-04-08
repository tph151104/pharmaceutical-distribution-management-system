<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                BÁO CÁO CÔNG NỢ TỔNG HỢP
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Loại Công Nợ</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Chứng Từ</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Phát Sinh</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Đối Tượng (KH/NCC)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tổng Tiền (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Đã Thanh Toán (VNĐ)</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Còn Nợ (VNĐ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($debts as $d)
        <tr>
            <td style="text-align: center; font-weight: bold; {{ $d->loai_thanh_toan == 'nhap' ? 'color: red;' : 'color: green;' }}">
                {{ $d->loai_thanh_toan == 'nhap' ? 'Phải Trả (NCC)' : 'Phải Thu (KH)' }}
            </td>
            <td style="text-align: center;">{{ $d->ma_chung_tu }}</td>
            <td style="text-align: center;">{{ $d->ngay_gd ? \Carbon\Carbon::parse($d->ngay_gd)->format('d/m/Y') : 'N/A' }}</td>
            <td>{{ $d->doi_tuong }}</td>
            <td style="text-align: right;">{{ $d->tong_tien }}</td>
            <td style="text-align: right;">{{ $d->so_tien_da_tra ?? 0 }}</td>
            <td style="text-align: right; font-weight: bold; color: red;">{{ $d->so_tien_con_no }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Tổng Phải Thu (KH):</td>
            <td colspan="3" style="text-align: right; font-weight: bold; color: green;">{{ $debts->where('loai_thanh_toan', 'xuat')->sum('so_tien_con_no') }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Tổng Phải Trả (NCC):</td>
            <td colspan="3" style="text-align: right; font-weight: bold; color: red;">{{ $debts->where('loai_thanh_toan', 'nhap')->sum('so_tien_con_no') }}</td>
        </tr>
    </tfoot>
</table>
