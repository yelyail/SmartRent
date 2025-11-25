<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        foreach ($roles as $role) {
            if ($user->role instanceof UserRole) {
                // Compare enum value
                if ($user->role->value === $role) {
                    return $next($request);
                }
            } else {
                // Fallback: compare as string
                if ($user->role === $role) {
                    return $next($request);
                }
            }
        }

        // If user doesn't have required role, abort with 403
        abort(403, 'Unauthorized access.');
    }
}