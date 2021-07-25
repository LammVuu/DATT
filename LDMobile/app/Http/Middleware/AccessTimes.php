<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LUOTTRUYCAP;
use Session;

class AccessTimes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Session::get('visitor')){
            Session::put('visitor', '1');
            LUOTTRUYCAP::create([
                'nentang' => 'web',
                'thoigian' => date('d/m/Y H:i:s')
            ]);
        }
        return $next($request);
    }
}
