<?php

namespace App\Http\Middleware;

use App\Services\OAuth\AuthService;
use Closure;

class OAuthUserMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authHelper                     = new AuthService();
        $authHelper->setUserFromToken();
        return $next($request);
    }
}
