<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTDG extends Model
{
    use HasFactory;

    protected $table = 'ctdg';

    protected $fillable = [
        'id_dg',
        'hinhanh',
    ];

    public $timestamps = false;
}
