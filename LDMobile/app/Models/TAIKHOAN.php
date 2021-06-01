<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TAIKHOAN extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'taikhoan';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'sdt',
        'matkhau',
        'email',
        'hoten',
        'anhdaidien',
        'anhbia',
        'loaitk',
        'htdn',
        'trangthai',
    ];

    public $timestamps = false;
}
