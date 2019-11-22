<?php

namespace Bedard\Saas\Http\Middleware;

use Closure;
use Event;

class ApiMiddleware
{
    public function handle($request, Closure $next)
    {
        Event::fire('bedard.saas.beforeApiRequest', [&$request]);

        $response = $next($request);

        Event::fire('bedard.saas.afterApiRequest', [&$response]);

        return $response;
    }
}
