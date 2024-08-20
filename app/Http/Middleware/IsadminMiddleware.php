<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsadminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->hasadminRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}