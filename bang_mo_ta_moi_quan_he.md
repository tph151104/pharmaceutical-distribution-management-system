# Bảng Mô Tả Các Mối Quan Hệ Giữa Các Bảng
## Hệ Thống Quản Lý Phân Phối Thuốc Tây

> [!NOTE]
> Bảng này được tổng hợp trực tiếp từ code Eloquent Model trong hệ thống, đảm bảo chính xác 100% với thực tế.

---

## Giải thích ký hiệu

| Ký hiệu | Ý nghĩa | Ví dụ thực tế |
| :---: | :--- | :--- |
| `1 : 1` | Một - Một (bắt buộc cả hai phía) | Một công dân có đúng 1 CCCD |
| `1 : 0..1` | Một - Không hoặc Một | Một đơn hàng có thể chưa có phiếu xuất (0) hoặc đã có (1) |
| `1 : 0..*` | Một - Không hoặc Nhiều | Một khách có thể chưa mua gì (0) hoặc mua nhiều đơn (*) |
| `1 : 1..*` | Một - Một hoặc Nhiều (bắt buộc tối thiểu 1) | Một phiếu nhập phải có ít nhất 1 dòng chi tiết |

---

## 1. Nhóm Danh Mục (Sản phẩm & Đối tác)

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 1 | NhomThuoc | `1 : 0..*` | Thuoc | Một nhóm thuốc (VD: Kháng sinh) chứa nhiều loại thuốc. Một nhóm có thể chưa có thuốc nào. |
| 2 | DonViTinh | `1 : 0..*` | Thuoc | Một đơn vị tính (VD: Hộp, Vỉ) được dùng cho nhiều loại thuốc khác nhau. |
| 3 | Thuoc | `1 : 0..*` | TonKho | Một loại thuốc có thể có nhiều lô hàng (khác hạn sử dụng, khác ngày nhập) trong kho. |

---

## 2. Nhóm Nhập Hàng (Mua hàng từ NCC)

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 4 | NhaCungCap | `1 : 0..*` | PhieuNhap | Một NCC cung cấp nhiều lần nhập hàng. NCC mới chưa có phiếu nhập nào. |
| 5 | NguoiDung | `1 : 0..*` | PhieuNhap | Một nhân viên có thể lập nhiều phiếu nhập. |
| 6 | PhieuNhap | `1 : 1..*` | ChiTietPhieuNhap | Một phiếu nhập phải có ít nhất 1 mặt hàng. Mỗi dòng ghi rõ thuốc gì, bao nhiêu, giá bao nhiêu. |
| 7 | PhieuNhap | `1 : 0..*` | TonKho | Khi nhập kho xong, mỗi dòng chi tiết sẽ sinh ra 1 lô tồn kho tương ứng. |
| 8 | PhieuNhap | `1 : 0..*` | ThanhToan | Một phiếu nhập có thể chưa thanh toán hoặc trả nhiều đợt cho NCC. |
| 9 | ChiTietPhieuNhap | `1 : 1` | TonKho | Một dòng chi tiết phiếu nhập tương ứng chính xác 1 lô tồn kho (cùng mã phiếu + mã thuốc + số lô). |

---

## 3. Nhóm Quản Lý Kho (Lõi hệ thống GSP)

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 10 | TonKho (Lô hàng) | `1 : 1..*` | TonKhoKhuVuc | Một lô hàng nhập về được chia nhỏ để ở nhiều phân khu (VD: 50 hộp ở KV01, 30 hộp ở KV03). Ít nhất phải nằm ở 1 khu vực. |
| 11 | KhuVucKho | `1 : 0..*` | TonKhoKhuVuc | Một phân khu (VD: KV03 Thành phẩm) chứa nhiều loại thuốc từ nhiều lô khác nhau. |
| 12 | TonKhoKhuVuc | `0..* : 1` | Thuoc | Nhiều dòng tồn kho khu vực cùng trỏ về 1 loại thuốc. |
| 13 | TonKhoKhuVuc | `0..* : 1` | PhieuNhap | Nhiều dòng tồn kho khu vực cùng liên kết về phiếu nhập gốc. |
| 14 | TonKho | `1 : 0..*` | LichSuDichChuyenKho | Một lô thuốc có thể được luân chuyển vị trí nhiều lần hoặc chưa bao giờ di chuyển. |
| 15 | KhuVucKho | `1 : 0..*` | LichSuDichChuyenKho (từ) | Một phân khu có thể là điểm xuất phát của nhiều lần luân chuyển. |
| 16 | KhuVucKho | `1 : 0..*` | LichSuDichChuyenKho (đến) | Một phân khu có thể là điểm đến của nhiều lần luân chuyển. |

---

## 4. Nhóm Bán Hàng & Xuất Kho

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 17 | KhachHang | `1 : 0..*` | DonHang | Một khách hàng có thể đặt nhiều đơn hoặc chưa đặt đơn nào. |
| 18 | DonHang | `1 : 1..*` | ChiTietDonHang | Một đơn hàng phải có ít nhất 1 mặt hàng. |
| 19 | DonHang | `1 : 0..1` | PhieuXuat | Một đơn hàng sau khi duyệt sẽ sinh ra tối đa 1 phiếu xuất. Nếu chưa duyệt thì chưa có. |
| 20 | NguoiDung | `1 : 0..*` | DonHang (duyệt) | Một nhân viên có thể duyệt nhiều đơn hàng. |
| 21 | NguoiDung | `1 : 0..*` | DonHang (hủy) | Một nhân viên có thể hủy nhiều đơn hàng. |
| 22 | PhieuXuat | `1 : 1..*` | ChiTietPhieuXuat | Một phiếu xuất phải ghi rõ xuất những thuốc nào, lô nào, số lượng bao nhiêu. |
| 23 | NguoiDung | `1 : 0..*` | PhieuXuat | Một nhân viên kho có thể tạo nhiều phiếu xuất. |
| 24 | KhachHang | `0..* : 1`z | PhieuXuat | Nhiều phiếu xuất cùng giao cho 1 khách hàng. |
| 25 | ChiTietDonHang | `0..* : 1` | Thuoc | Nhiều dòng chi tiết đơn hàng cùng trỏ về 1 loại thuốc. |
| 26 | ChiTietPhieuXuat | `0..* : 1` | Thuoc | Nhiều dòng chi tiết phiếu xuất cùng trỏ về 1 loại thuốc. |

---

## 5. Nhóm Đổi Trả Hàng

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 27 | KhachHang | `1 : 0..*` | KhachTraHang | Một khách có thể gửi nhiều yêu cầu trả hàng hoặc chưa bao giờ trả. |
| 28 | DonHang | `1 : 0..*` | KhachTraHang | Một đơn hàng có thể bị trả nhiều lần (trả từng phần). |
| 29 | KhachTraHang | `1 : 1..*` | ChiTietTraHang | Một phiếu trả hàng phải liệt kê ít nhất 1 mặt hàng bị trả. |
| 30 | NguoiDung | `1 : 0..*` | KhachTraHang (duyệt) | Một nhân viên có thể duyệt nhiều phiếu trả hàng. |
| 31 | NguoiDung | `1 : 0..*` | KhachTraHang (tạo) | Một nhân viên có thể tạo nhiều phiếu trả hàng. |
| 32 | ChiTietTraHang | `0..* : 1` | Thuoc | Nhiều dòng chi tiết trả hàng cùng trỏ về 1 loại thuốc. |
| 33 | NhaCungCap | `1 : 0..*` | PhieuTraNcc | Một NCC có thể nhận trả hàng nhiều lần. |
| 34 | NguoiDung | `1 : 0..*` | PhieuTraNcc | Một nhân viên có thể lập nhiều phiếu trả NCC. |
| 35 | PhieuTraNcc | `1 : 1..*` | ChiTietPhieuTraNcc | Một phiếu trả NCC phải liệt kê ít nhất 1 mặt hàng bị trả. |
| 36 | ChiTietPhieuTraNcc | `0..* : 1` | Thuoc | Nhiều dòng chi tiết trả NCC cùng trỏ về 1 loại thuốc. |

---

## 6. Nhóm Tài Chính (Thanh toán)

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 37 | PhieuXuat | `1 : 0..*` | ThanhToan | Một phiếu xuất có thể thanh toán 1 lần hoặc trả nhiều đợt. |
| 38 | KhachTraHang | `1 : 0..*` | ThanhToan | Khi khách trả hàng, hệ thống tạo giao dịch hoàn tiền (1 hoặc nhiều đợt). |
| 39 | PhieuTraNcc | `1 : 0..*` | ThanhToan | Khi trả hàng cho NCC, hệ thống ghi nhận giao dịch thu lại tiền. |

---

## 7. Nhóm Lịch Sử & Hệ Thống

| STT | Bảng A (Gốc) | Quan hệ | Bảng B (Đích) | Diễn giải |
| :---: | :--- | :---: | :--- | :--- |
| 40 | Thuoc | `1 : 0..*` | LichSuKho | Một loại thuốc có nhiều bản ghi biến động (nhập, xuất, điều chỉnh). |
| 41 | NguoiDung | `1 : 0..*` | LichSuKho | Một nhân viên thực hiện nhiều thao tác ghi nhận biến động kho. |
| 42 | Thuoc | `1 : 0..*` | LichSuDichChuyenKho | Một loại thuốc có nhiều bản ghi luân chuyển vị trí. |
| 43 | NguoiDung | `1 : 0..*` | LichSuDichChuyenKho | Một nhân viên thực hiện nhiều thao tác luân chuyển kho. |

---

## Tổng kết hệ thống

| Phân loại | Số lượng bảng |
| :--- | :---: |
| Danh mục (NhomThuoc, DonViTinh, Thuoc, NhaCungCap, KhachHang, KhuVucKho) | 6 |
| Nhập hàng (PhieuNhap, ChiTietPhieuNhap) | 2 |
| Quản lý kho (TonKho, TonKhoKhuVuc) | 2 |
| Bán hàng (DonHang, ChiTietDonHang, PhieuXuat, ChiTietPhieuXuat) | 4 |
| Đổi trả (KhachTraHang, ChiTietTraHang, PhieuTraNcc, ChiTietPhieuTraNcc) | 4 |
| Tài chính (ThanhToan) | 1 |
| Lịch sử (LichSuKho, LichSuDichChuyenKho) | 2 |
| Hệ thống (NguoiDung, FeatureToggle) | 2 |
| **Tổng cộng** | **23** |
