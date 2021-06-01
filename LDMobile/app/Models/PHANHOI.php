<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PHANHOI extends Model
{
    use HasFactory;

    protected $table = 'phanhoi';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_tk',
        'id_dg',
        'noidung',
        'thoigian',
        'trangthai',
    ];

    public $timestamps = false;
}
