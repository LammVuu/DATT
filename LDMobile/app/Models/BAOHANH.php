<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BAOHANH extends Model
{
    use HasFactory;

    protected $table = 'baohanh';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_sp',
        'imei',
        'ngaymua',
        'ngayketthuc',
        'trangthai',
    ];

    public $timestamps = false;
}
