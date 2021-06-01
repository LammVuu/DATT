<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SP_YEUTHICH extends Model
{
    use HasFactory;

    protected $table = 'sp_yeuthich';

    protected $fillable = [
        'id_tk',
        'id_sp',
    ];

    public $timestamps = false;
}
