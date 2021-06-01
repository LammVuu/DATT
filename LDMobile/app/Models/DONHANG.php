<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DONHANG extends Model
{
    use HasFactory;

    protected $table = 'donhang';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'thoigian',
        'diachigiaohang',
        'id_tk',
        'pttt',
        'id_vc',
        'hinhthuc',
        'tongtien',
        'trangthaidonhang',
        'trangthai',
    ];

    public $timestamps = false;
}
