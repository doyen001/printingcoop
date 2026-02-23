<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * IpBlocker Middleware
 * Replicate CI IP blocking logic
 * CI: BLOCKED_IPS_ACCESS_TIME_IN_MINUTES constant (constants.php line 9)
 */
class IpBlocker
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
        $ipAddress = $request->ip();
        
        // Check if IP is blocked
        $blockedIp = DB::table('blocked_ips')
            ->where('ip', $ipAddress)
            ->first();
        
        if ($blockedIp) {
            // Check if block time has expired
            $blockTimeMinutes = config('store.blocked_ips_access_time_minutes', 240);
            $blockedAt = strtotime($blockedIp->created);
            $expiresAt = $blockedAt + ($blockTimeMinutes * 60);
            
            if (time() < $expiresAt) {
                // Still blocked
                Log::warning('Blocked IP attempted access', [
                    'ip' => $ipAddress,
                    'url' => $request->fullUrl(),
                    'blocked_until' => date('Y-m-d H:i:s', $expiresAt),
                ]);
                
                abort(403, 'Access denied. Your IP address has been temporarily blocked.');
            } else {
                // Block expired, unblock the IP
                DB::table('blocked_ips')
                    ->where('id', $blockedIp->id)
                    ->update(['status' => 0]);
            }
        }
        
        // Track failed login attempts (optional - can be used by login controllers)
        // This is just tracking, actual blocking is done by login controllers
        
        return $next($request);
    }
    
    /**
     * Block an IP address
     * Called by controllers when needed
     */
    public static function blockIp($ipAddress, $reason = 'Multiple failed login attempts')
    {
        DB::table('blocked_ips')->insert([
            'ip' => $ipAddress,
            'reason' => $reason,
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);
        
        Log::warning('IP address blocked', [
            'ip' => $ipAddress,
            'reason' => $reason,
        ]);
    }
    
    /**
     * Unblock an IP address
     */
    public static function unblockIp($ipAddress)
    {
        DB::table('blocked_ips')
            ->where('ip', $ipAddress)
            ->update(['status' => 0]);
        
        Log::info('IP address unblocked', ['ip' => $ipAddress]);
    }
}
