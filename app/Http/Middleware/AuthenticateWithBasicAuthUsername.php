<?php

namespace Morpheus\Http\Middleware;
use Closure;

class AuthenticateWithBasicAuthUsername extends \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth {
	
	public function handle($request, Closure $next)
    {
        return $this->auth->basic('steamUsername') ?: $next($request);
    }
}
