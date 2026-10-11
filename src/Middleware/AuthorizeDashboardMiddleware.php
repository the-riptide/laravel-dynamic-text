<?php

namespace TheRiptide\LaravelDynamicText\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TheRiptide\LaravelDynamicText\Security\Authorize;

class AuthorizeDashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (! (new Authorize)->canTakeAction()) {
            return abort(403);
        }

        return $next($request);
    }
}
