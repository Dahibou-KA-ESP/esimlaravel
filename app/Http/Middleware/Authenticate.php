<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        // Check if authenticated user is deleted
        if (! $request->expectsJson() && Auth::check() && Auth::user()->deleted_at !== null) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte a été supprimé. Veuillez contacter l\'administrateur.');
        }
        else if (! $request->expectsJson() && Auth::check() && !Auth::user()->isActive()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte n\'est pas actif. Veuillez contacter l\'administrateur.');
        }

        return $next($request);
    }
}

