<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HANGDOI extends Model
{
    use HasFactory;

    protected $table = 'hangdoi';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_tk',
        'nentang',
        'trangthai'
    ];

    public $timestamps = false;
}
