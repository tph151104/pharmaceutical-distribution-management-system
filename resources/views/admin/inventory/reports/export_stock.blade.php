<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 16px; font-weight: bold; background-color: #f8f9fa; text-align: center; padding: 10px;">
                BÁO CÁO TỒN KHO THEO LÔ
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Mã Thuốc</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Tên Thuốc</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Lô</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Hạn Sử Dụng</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Số Lượng Tồn</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Ngày Nhập</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Khu vực</th>
            <th style="font-weight: bold; background-color: #d9edf7; text-align: center;">Trạng thái lô</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tonKho as $item)
        <tr>
            <td style="text-align: center;">{{ $item->ma_thuoc }}</td>
            <td>{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</td>
            <td style="text-align: center;">{{ $item->so_lo }}</td>
            <td style="text-align: center; {{ $item->han_su_dung && \Carbon\Carbon::parse($item->han_su_dung)->lt(now()) ? 'color: red;' : '' }}">
                {{ $item->han_su_dung ? \Carbon\Carbon::parse($item->han_su_dung)->format('d/m/Y') : '' }}
            </td>
            <td style="text-align: right; font-weight: bold;">{{ $item->so_luong_ton }}</td>
            <td style="text-align: center;">{{ $item->ngay_nhap_lo ? \Carbon\Carbon::parse($item->ngay_nhap_lo)->format('d/m/Y') : '' }}</td>
            <td style="text-align: center;">
                @php
                    $khuVucs = $item->chiTietKhuVuc
                        ->where('ma_phieu_nhap', $item->ma_phieu_nhap)
                        ->where('so_lo', $item->so_lo);
                @endphp
                @if($khuVucs->count() > 0)
                    @foreach($khuVucs as $kv)
                        {{ $kv->khuVuc->ten_khu_vuc ?? $kv->ma_khu_vuc }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                @else
                    N/A
                @endif
            </td>
            <td style="text-align: center;">
                @if($item->trang_thai_lo === 'dang_ban') Đang bán
                @elseif($item->trang_thai_lo === 'ngung_ban') Ngừng bán
                @elseif($item->trang_thai_lo === 'cho_duyet') Chờ duyệt
                @elseif($item->trang_thai_lo === 'het_han') Hết hạn
                @else {{ $item->trang_thai_lo }} @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
