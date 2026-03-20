<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu xuất kho - {{ $phieuXuat->ma_phieu_xuat }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .company-info {
            width: 60%;
        }
        .company-name {
            font-weight: bold;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .document-info {
            width: 35%;
            text-align: right;
        }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        .title h1 {
            margin: 0;
            font-size: 20pt;
            text-transform: uppercase;
        }
        .title p {
            margin: 5px 0 0;
            font-style: italic;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .customer-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            text-align: center;
            font-weight: bold;
            background-color: #f8f9fa; /* In màu mờ khi in trắng đen */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 25%;
            text-align: center;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-note {
            font-style: italic;
            font-size: 11pt;
            margin-bottom: 80px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Nút in (sẽ bị ẩn khi in) -->
    <div class="no-print" style="text-align: center; margin: 20px 0; padding: 10px; background: #f0f0f0;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">🖨️ In Phiếu Xuất</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">❌ Đóng</button>
    </div>

    <!-- Đầu trang -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">CÔNG TY DƯỢC PHẨM ABC</div>
            <div>Địa chỉ: 123 Đường Dược, Quận 1, TP.HCM</div>
            <div>Điện thoại: 028 3812 3456 - MST: 0312345678</div>
        </div>
        <div class="document-info">
            <div><strong>Mã phiếu:</strong> {{ $phieuXuat->ma_phieu_xuat }}</div>
            <div><strong>Đơn hàng gốc:</strong> {{ $phieuXuat->ma_don_hang ?? 'N/A' }}</div>
            <div><strong>Ngày xuất:</strong> {{ \Carbon\Carbon::parse($phieuXuat->ngay_xuat)->format('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Tiêu đề -->
    <div class="title">
        <h1>PHIẾU XUẤT KHO</h1>
        <p>(Kiêm hóa đơn bán sỉ)</p>
    </div>

    <!-- Thông tin khách hàng -->
    <div class="customer-info">
        <p><strong>Khách hàng:</strong> {{ $phieuXuat->khachHang->ten_kh ?? '________________________' }} (Mã KH: {{ $phieuXuat->khachHang->ma_kh ?? '___' }})</p>
        <p><strong>Địa chỉ:</strong> {{ $phieuXuat->khachHang->dia_chi ?? '________________________________________________' }}</p>
        <p><strong>Điện thoại:</strong> {{ $phieuXuat->khachHang->sdt ?? '________________________' }}</p>
        <p><strong>Lý do xuất:</strong> Xuất bán hàng theo đơn</p>
    </div>

    <!-- Bảng chi tiết thuốc -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th style="width: 10%;">Mã thuốc</th>
                <th style="width: 25%;">Tên sản phẩm</th>
                <th style="width: 8%;">ĐVT</th>
                <th style="width: 12%;">Số lô</th>
                <th style="width: 12%;">Hạn dùng</th>
                <th style="width: 8%;">SL</th>
                <th style="width: 10%;">Đơn giá</th>
                <th style="width: 10%;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @forelse($phieuXuat->chiTiet as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->ma_thuoc }}</td>
                    <td>{{ $item->thuoc->ten_thuoc ?? $item->ma_thuoc }}</td>
                    <td class="text-center">{{ $item->thuoc->don_vi_tinh ?? 'Hộp' }}</td>
                    <td class="text-center">{{ $item->so_lo }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->han_su_dung)->format('d/m/Y') }}</td>
                    <td class="text-center text-bold">{{ number_format($item->so_luong_xuat) }}</td>
                    <td class="text-right">{{ number_format($item->don_gia_ban) }}</td>
                    <td class="text-right">{{ number_format($item->thanh_tien) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center"><em>Không có dữ liệu chi tiết</em></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="8" class="text-right text-bold">Cộng tiền hàng:</td>
                <td class="text-right text-bold">{{ number_format($phieuXuat->tong_tien) }}</td>
            </tr>
            <tr>
                <td colspan="8" class="text-right text-bold">TỔNG CỘNG THANH TOÁN (VNĐ):</td>
                <td class="text-right text-bold" style="font-size: 1.2em;">{{ number_format($phieuXuat->tong_tien) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Số tiền viết bằng chữ -->
    <div style="margin-bottom: 20px;">
        <p><strong>Số tiền viết bằng chữ:</strong> <em>(Tính tại thời điểm in phiếu)</em></p>
    </div>

    <!-- Chữ ký -->
    <div class="footer">
        <div class="signature-box">
            <div class="signature-title">Người lập phiếu</div>
            <div class="signature-note">(Ký, họ tên)</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">Người giao hàng</div>
            <div class="signature-note">(Ký, họ tên)</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">Thủ kho</div>
            <div class="signature-note">(Ký, họ tên)</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">Khách hàng nhận</div>
            <div class="signature-note">(Ký, họ tên)</div>
        </div>
    </div>

</body>
</html>
