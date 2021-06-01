<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DANHGIASP extends Model
{
    use HasFactory;

    protected $table = 'danhgiasp';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_tk',
        'id_sp',
        'noidung',
        'thoigian',
        'soluotthich',
        'danhgia',
        'trangthai',
    ];

    public $timestamps = false;
}
