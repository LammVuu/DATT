<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAIKHOAN extends Models
{
    use HasFactory;

    protected $table = 'taikhoan';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'sdt',
        'password',
        'email',
        'hoten',
        'anhdaidien',
        'anhbia',
        'loaitk',
        'htdn',
        'trangthai',
    ];

    public $timestamps = false;
}
