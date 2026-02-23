<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AdminAuthService;

class AdminAuth
{
    /**
     * Handle an incoming request.
     * 
     * Check if admin is logged in via session (CI compatible) or Laravel auth
     * Redirect to admin login if not authenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if this is a logout request - allow it without authentication
        if ($request->is('admin/*/logout') || $request->is('admin/logout')) {
            return $next($request);
        }
        
        // Check if admin is logged in using CI-compatible session or Laravel auth
        if (!AdminAuthService::check() && !auth()->guard('admin')->check()) {
            return redirect('pcoopadmin')->with('message_error', 'Please login to access admin panel');
        }
        
        return $next($request);
    }
}
