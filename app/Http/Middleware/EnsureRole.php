<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        // Treat missing role as 'user' so legacy accounts without a role
        // can still access user-protected routes after login.
        $userRole = strtolower($request->user()->role ?? 'user');
        foreach ($roles as $role) {
            if ($userRole === strtolower($role)) {
                return $next($request);
            }
        }
        return redirect()->route('login');
    }
}
