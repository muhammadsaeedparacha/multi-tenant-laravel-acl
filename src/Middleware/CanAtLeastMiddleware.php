<?php

namespace Yajra\Acl\Middleware;

use Closure;

class CanAtLeastMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  array|string $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        $abilities = is_array($permissions) ? $permissions : explode(',', $permissions);

        if (! auth()->check() || ! auth()->user()->canAtLeast($abilities)) {
            if ($request->ajax()) {
                $error = ['error' => ["You are not authorized to view this content!"]];
                return response($error, 401); 
            }

            return abort(401, 'You are not authorized to view this content!');
        }

        return $next($request);
    }
}
