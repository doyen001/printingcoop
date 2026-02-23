<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * SetCurrency Middleware
 * Replicate CI currency handling logic from MY_Controller
 */
class SetCurrency
{
    /**
     * Handle an incoming request.
     * CI: MY_Controller lines 57-94
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Currency is already set by DetectStore middleware
        // This middleware can be used for additional currency-specific logic
        
        return $next($request);
    }
}
