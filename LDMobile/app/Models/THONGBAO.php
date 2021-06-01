<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class THONGBAO extends Model
{
    use HasFactory;

    protected $table = 'thongbao';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_tk',
        'noidung',
        'thoigian',
        'trangthai',
    ];

    public $timestamps = false;
}
