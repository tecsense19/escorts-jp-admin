<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(isset(auth()->user()->user_role)){
            if(auth()->user())
            {
                if(auth()->user()->user_role == 'admin' || auth()->user()->user_role == 'subadmin' || auth()->user()->user_role == 'escorts')
                {
                    return $next($request);
                }
            }
            else
            {
                Auth::logout();
                return redirect()->route('admin.login')->with('error','You have not admin access. Please login as admin.');
            }
        }
        Auth::logout();
        return redirect()->route('admin.login')->with('error','You have not admin access. Please login as admin.');
    }
}
