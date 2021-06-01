<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CHINHANH extends Model
{
    use HasFactory;

    protected $table = 'chinhanh';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'diachi',
        'sdt',
        'id_tt',
        'trangthai',
    ];

    public $timestamps = false;
}
