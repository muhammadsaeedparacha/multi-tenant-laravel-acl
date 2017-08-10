<?php

namespace Yajra\Acl\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (! $request->user() || ! $request->user()->can($permission)) {
            if ($request->ajax()) {
                $error = ['error' => ["You are not authorized to view this content!"]];
                return response($error, 401); 
            }

            return abort(401, 'You are not authorized to view this content!');
        }

        return $next($request);
    }
}
