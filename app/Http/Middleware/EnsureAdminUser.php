<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminEmail = config('admin.email');
        $userEmail = $request->user()?->email;

        abort_unless(
            is_string($adminEmail)
            && is_string($userEmail)
            && hash_equals(mb_strtolower($adminEmail), mb_strtolower($userEmail)),
            403,
        );

        return $next($request);
    }
}
