<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAUSP extends Model
{
    use HasFactory;

    protected $table = 'mausp';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'tenmau',
        'mota',
        'id_ncc',
        'baohanh',
        'diachibaohanh',
        'trangthai',
    ];

    public $timestamps = false;
}
