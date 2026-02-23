<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * SetLanguage Middleware
 * Replicate CI language detection logic from MY_Controller
 */
class SetLanguage
{
    /**
     * Handle an incoming request.
     * CI: MY_Controller line 40, 116
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from store configuration (set by DetectStore middleware)
        $language_name = Config::get('store.language_name', 'english');
        
        // Set application locale
        $locale = $this->mapLanguageToLocale($language_name);
        App::setLocale($locale);
        
        // Share with views
        view()->share('language_name', $language_name);
        
        return $next($request);
    }
    
    /**
     * Map language name to Laravel locale
     */
    private function mapLanguageToLocale($language_name)
    {
        $localeMap = [
            'English' => 'en',
            'French' => 'fr',
        ];
        
        return $localeMap[$language_name] ?? 'en';
    }
}
