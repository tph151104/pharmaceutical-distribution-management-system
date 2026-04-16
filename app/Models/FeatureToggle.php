<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $table = 'feature_toggles';
    protected $primaryKey = 'ma_chuc_nang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_chuc_nang',
        'ten_chuc_nang',
        'mo_ta',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];
}
