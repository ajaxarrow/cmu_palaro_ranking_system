<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user has required roles and needs to reset password
        if ($user && in_array($user->role, ['assistant_tournament_director', 'tournament_director', 'user', 'encoder']) &&
            !$user->is_changed && $user->is_first_time) {
            return redirect()->route('password.reset.form');
        }

        return $next($request);
    }
}
