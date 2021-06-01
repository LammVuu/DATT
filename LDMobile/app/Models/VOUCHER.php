<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VOUCHER extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'noidung',
        'chietkhau',
        'dieukien',
        'ngaybatdau',
        'ngayketthuc',
        'sl',
        'trangthai',
    ];

    public $timestamps = false;
}
