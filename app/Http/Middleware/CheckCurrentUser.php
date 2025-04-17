<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCurrentUser
{
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->route('id');

        if (Auth::id() == $userId) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas effectuer cette action sur votre propre compte.');
        }

        return $next($request);
    }
}
