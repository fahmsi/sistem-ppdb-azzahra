<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Supports multiple roles: role:admin,super_admin
     * Also, super_admin can always access admin routes.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (! auth()->check()) {
            abort(403, 'Akses ditolak');
        }

        $userRole = auth()->user()->role;

        // super_admin always has access to admin routes
        if ($userRole === 'super_admin' && in_array('admin', $roles)) {
            return $next($request);
        }

        if (! in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
