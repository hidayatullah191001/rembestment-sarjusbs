<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->role_id != null && Auth::user()->role->name == 'Administrator') {
                return $next($request);
            }
            return redirect()->route('login')->with('error', 'You don\'t have permission to access this.');
        }
        return redirect()->route('login')->with('error', 'You need to login first.');
    }
}
