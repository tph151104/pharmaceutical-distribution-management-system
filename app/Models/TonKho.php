<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TonKho extends Model
{
    protected $table = 'ton_kho';
    public $incrementing = false;
    protected $primaryKey = ['ma_thuoc', 'ma_phieu_nhap', 'so_lo'];
    protected $keyType = 'string';

    protected $fillable = [
        'ma_thuoc',
        'ma_phieu_nhap',
        'so_lo',
        'ngay_san_xuat',
        'ngay_nhap_lo',
        'han_su_dung',
        'so_luong_ton',
        'so_luong_da_xuat',
        'trang_thai_lo',
        'image1',
        'image2',
        'image3',
    ];

    protected $casts = [
        'ngay_nhap_lo' => 'date',
        'han_su_dung' => 'date',
        'so_luong_ton' => 'integer',
        'so_luong_da_xuat' => 'integer',
    ];

    // ==========================================
    // Relationships
    // ==========================================

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }

    public function phieuNhap()
    {
        return $this->belongsTo(PhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

    // Custom relation vì khoá chính phức tạp.dùng hasOne nhưng cần query thêm điều kiện ở nơi gọi
    public function chiTietPhieuNhap()
    {
        return $this->hasOne(ChiTietPhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap')
                    ->where('ma_thuoc', $this->ma_thuoc)
                    ->where('so_lo', $this->so_lo);
    }

    public function chiTietKhuVuc()
    {
        return $this->hasMany(TonKhoKhuVuc::class, 'ma_thuoc', 'ma_thuoc');
    }

    /**
     * Chỉ lấy các lô đang được phép bán
     */
    public function scopeDangBan($query)
    {
        return $query->where('trang_thai_lo', 'dang_ban')
                     ->where('so_luong_ton', '>', 0)
                     ->where('han_su_dung', '>=', now()->toDateString());
    }

    /**
     * Lấy các lô đã hết hạn nhưng chưa cập nhật trạng thái
     */
    public function scopeHetHanChuaCapNhat($query)
    {
        return $query->where('han_su_dung', '<', now()->toDateString())
                     ->where('trang_thai_lo', '!=', 'het_han');
    }

    // Kiểm tra ràng buộc nghiệp vụ
    /**
     * Kiểm tra lô có thể xuất bán không
     */
    public function coTheXuatBan(int $soLuongXuat): bool
    {
        return $this->trang_thai_lo === 'dang_ban'
            && $this->so_luong_ton >= $soLuongXuat
            && $this->han_su_dung >= now()->toDateString();
    }

    /**
     * Lấy thông báo lỗi nếu không thể xuất bán
     */
    public function layLoiXuatBan(int $soLuongXuat): ?string
    {
        if ($this->trang_thai_lo !== 'dang_ban') {
            $tenTrangThai = [
                'cho_duyet' => 'chờ duyệt',
                'het_han' => 'hết hạn',
                'thu_hoi' => 'bị thu hồi',
                'ngung_ban' => 'ngừng bán',
            ];
            return "Lô {$this->so_lo} đang ở trạng thái: " . ($tenTrangThai[$this->trang_thai_lo] ?? $this->trang_thai_lo);
        }

        if ($this->han_su_dung < (now()->isImmutable() ? now()->toDateString() : now()->toDateString())) {
            $hsd = $this->han_su_dung instanceof \Carbon\CarbonInterface ? $this->han_su_dung : \Carbon\Carbon::parse($this->han_su_dung);
            return "Lô {$this->so_lo} đã hết hạn sử dụng ({$hsd->format('d/m/Y')})";
        }

        if ($this->so_luong_ton < $soLuongXuat) {
            return "Lô {$this->so_lo} chỉ còn {$this->so_luong_ton}, không đủ xuất {$soLuongXuat}";
        }

        return null;
    }

    /**
     * Override phương thức setKeysForSaveQuery cho composite PK
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ((array) $this->primaryKey as $pk) {
            $query->where($pk, '=', $this->getAttribute($pk));
        }
        return $query;
    }
}
