<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfTokenExcept extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/Products/searchProduct',
        'admin/*/searchProduct',
        '*/searchProduct',
        'admin/Products/AttributesMap',
        'admin/Products/AttributeItemsMap/*',
        'admin/Products/attributeUpdate',
        'admin/Products/attributeDeletePost',
        'admin/Products/attributeCreateMap',
        'admin/Products/attributeItemCreateMap',
        'admin/Products/attributeItemUpdateMap',
        'admin/Products/attributeItemDeleteMap',
    ];
    
    /**
     * Handle the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Check if the request should be excluded from CSRF verification
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request);
            }
        }
        
        return parent::handle($request, $next);
    }
}
