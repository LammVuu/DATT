<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LUOTTHICH extends Model
{
    use HasFactory;

    protected $table = 'luotthich';

    protected $fillable = [
        'id_tk',
        'id_dg',
    ];

    public $timestamps = false;
}
