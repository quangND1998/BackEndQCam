<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && (auth()->user()->isActive == 0)){
            $user= Auth::user();
            $user->tokens()->delete();
          
            return response()->json('Tài khoản này không được đăng nhập nữa.', 403);

        }
        else{
            return $next($request);
        }
    }
}
