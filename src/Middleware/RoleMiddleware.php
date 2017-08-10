<?php

namespace Yajra\Acl\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user() || ! $request->user()->isRole($role)) {
            if ($request->ajax()) {
                $error = ['error' => ["You are not authorized to view this content!"]];
                return response($error, 401); 
            }

            return abort(401, 'You are not authorized to view this content!');
        }

        return $next($request);
    }
}
