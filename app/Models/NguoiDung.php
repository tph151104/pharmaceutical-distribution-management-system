<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
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
}
