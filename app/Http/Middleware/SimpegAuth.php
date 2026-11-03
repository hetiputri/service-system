<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SimpegAuth
{

    public function handle(Request $request, Closure $next)
    {

        if (!session('is_login')) {

            return redirect('/login');

        }

        return $next($request);

    }

}
