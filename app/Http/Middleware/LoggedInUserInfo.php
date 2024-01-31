<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoggedInUserInfo
{
    public function handle(Request $request, Closure $next)
    {
        $loggedInUser = Auth::user();

        // Meneruskan informasi pelanggan yang sedang login ke setiap tampilan
        view()->share('loggedInUser', $loggedInUser);

        return $next($request);
    }
}
