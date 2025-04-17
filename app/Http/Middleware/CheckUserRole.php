<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifiez si l'utilisateur est authentifié
        if (Auth::check()) {
            // Vérifiez si l'utilisateur a les rôles appropriés
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'super' ) {
                return $next($request);
            }
        }

        // Redirigez l'utilisateur non autorisé
        return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé!');
    } 
}
