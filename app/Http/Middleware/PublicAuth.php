<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicAuth
{
    /**
     * Handle an incoming request.
     * 
     * Replicate CI Public_Controller __construct check (lines 11-13)
     * Redirect to MyOrders if already logged in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in (session key: loginId)
        if (session('loginId')) {
            return redirect('MyOrders');
        }
        
        return $next($request);
    }
}
