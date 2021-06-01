<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SANPHAM extends Model
{
    use HasFactory;

    protected $table = 'sanpham';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'tensp',
        'id_msp',
        'hinhanh',
        'mausac',
        'dungluong',
        'gia',
        'id_km',
        'cauhinh',
        'trangthai',
    ];

    public $timestamps = false;
}
