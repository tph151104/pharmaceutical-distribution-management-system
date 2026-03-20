<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Phiếu Đơn Hàng #{{ $donHang->ma_don_hang }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box {
            width: 30%;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px dashed #333;
        }
        @media print {
            body { padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <!-- Print Button (Hidden in print mode) -->
        <div style="text-align: right; margin-bottom: 20px;">
            <button onclick="window.print()" style="padding: 8px 15px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                🖨️ In Hóa Đơn
            </button>
            <button onclick="window.close()" style="padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-left: 10px;">
                Đóng
            </button>
        </div>

        <div class="header">
            <h1>HÓA ĐƠN BÁN HÀNG SỈ</h1>
            <p>Mã Hóa Đơn: <strong>{{ $donHang->ma_don_hang }}</strong></p>
            <p>Ngày in: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Thông Tin Khách Hàng</h3>
                <div class="info-row"><span class="info-label">Khách hàng:</span> {{ $donHang->khachHang->ten_kh ?? 'Khách VIP' }}</div>
                <div class="info-row"><span class="info-label">Điện thoại:</span> {{ $donHang->khachHang->dien_thoai ?? '—' }}</div>
                <div class="info-row"><span class="info-label">Địa chỉ GN:</span> {{ $donHang->dia_chi_giao ?? ($donHang->khachHang->dia_chi ?? '—') }}</div>
            </div>
            <div class="info-box">
                <h3>Thông Tin Đơn Hàng</h3>
                <div class="info-row"><span class="info-label">Ngày đặt:</span> {{ $donHang->ngay_dat ? $donHang->ngay_dat->format('d/m/Y H:i') : '—' }}</div>
                <div class="info-row"><span class="info-label">Trạng thái:</span> {{ $donHang->tenTrangThai }}</div>
                <div class="info-row"><span class="info-label">Ghi chú:</span> {{ $donHang->ghi_chu ?? '—' }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">STT</th>
                    <th width="15%">Mã Thuốc</th>
                    <th width="40%">Tên Sản Phẩm</th>
                    <th width="15%" class="text-right">Đơn Giá</th>
                    <th width="10%" class="text-center">Số Lượng</th>
                    <th width="15%" class="text-right">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donHang->chiTiet as $index => $ct)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $ct->ma_thuoc }}</td>
                    <td>{{ $ct->thuoc->ten_thuoc ?? 'Sản phẩm không xác định' }}</td>
                    <td class="text-right">{{ number_format($ct->don_gia) }} ₫</td>
                    <td class="text-center">{{ $ct->so_luong }}</td>
                    <td class="text-right">{{ number_format($ct->thanhTien) }} ₫</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-right">TỔNG CỘNG:</td>
                    <td class="text-right">{{ number_format($donHang->tong_tien) }} ₫</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <div class="signature-box">
                <strong>Người Giao Hàng</strong><br>
                <small>(Ký, ghi rõ họ tên)</small>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <strong>Người Nhận Hàng</strong><br>
                <small>(Ký, ghi rõ họ tên)</small>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <strong>Người Lập Phiếu</strong><br>
                <small>(Ký, ghi rõ họ tên)</small>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>
</body>
</html>
