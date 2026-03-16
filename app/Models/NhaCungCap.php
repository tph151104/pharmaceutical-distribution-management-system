<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $table = 'nha_cung_cap';
    public $incrementing = false;
    protected $primaryKey = 'ma_ncc';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_ncc',
        'ten_ncc',
        'dia_chi',
        'dien_thoai',
        'email',
        'ma_so_thue',
        'ghi_chu',
    ];

    public function cacPhieuNhap()
    {
        return $this->hasMany(PhieuNhap::class, 'ma_ncc', 'ma_ncc');
    }
}
