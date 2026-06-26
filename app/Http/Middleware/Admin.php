<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // User not logged in
        if (! $user) {
            abort(401, 'Unauthorized');
        }

        // Account inactive
        if (! $user->is_active) {
            Auth::logout();
            abort(403, 'Please Contract Our Support');
        }
        if ($user->role == 'admin') {
            return $next($request);
        }

        // Not admin
        Auth::logout();
        abort(403, 'Access denied.');
    }
}
