<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (Gate::any(['is-superadmin', 'is-staff'])) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}