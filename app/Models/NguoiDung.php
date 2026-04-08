<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class NguoiDung extends Authenticatable
{
    // ─── Role Constants ────────────────────────────────────
    const ROLE_ADMIN        = 1;
    const ROLE_NV_KHO       = 2;
    const ROLE_NV_BAN_HANG  = 3;
    const ROLE_KE_TOAN      = 4;
    const ROLE_TRUONG_KHO   = 5;

    const ROLE_NAMES = [
        self::ROLE_ADMIN       => 'Admin',
        self::ROLE_NV_KHO      => 'Nhân viên kho',
        self::ROLE_NV_BAN_HANG => 'Nhân viên bán hàng',
        self::ROLE_KE_TOAN     => 'Kế toán',
        self::ROLE_TRUONG_KHO  => 'Trưởng kho',
    ];

    // ─── Model Config ──────────────────────────────────────
    protected $table = 'nguoi_dung';
    public $incrementing = false;
    protected $primaryKey = 'ma_nguoi_dung';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_nguoi_dung',
        'ten_dang_nhap',
        'mat_khau',
        'role',
        'email',
        'sdt',
        'trang_thai',
        'ho_ten_nd'
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    // ─── Auth Overrides ────────────────────────────────────

    /**
     * Trả về cột username cho Auth
     */
    public function getAuthIdentifierName()
    {
        return 'ma_nguoi_dung';
    }

    /**
     * Trả về mật khẩu cho Auth
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    // ─── Role Helpers ──────────────────────────────────────

    /**
     * Accessor: lấy tên role dạng text
     */
    public function getRoleNameAttribute()
    {
        return self::ROLE_NAMES[$this->role] ?? 'Không xác định';
    }

    public function isAdmin(): bool
    {
        return (int) $this->role === self::ROLE_ADMIN;
    }

    public function isWarehouseStaff(): bool
    {
        return (int) $this->role === self::ROLE_NV_KHO;
    }

    public function isSales(): bool
    {
        return (int) $this->role === self::ROLE_NV_BAN_HANG;
    }

    public function isAccountant(): bool
    {
        return (int) $this->role === self::ROLE_KE_TOAN;
    }

    public function isWarehouseSupervisor(): bool
    {
        return (int) $this->role === self::ROLE_TRUONG_KHO;
    }

    /**
     * Kiểm tra user có thuộc 1 trong các roles cho trước không
     */
    public function hasRole(...$roles): bool
    {
        return in_array((int) $this->role, $roles);
    }
}
