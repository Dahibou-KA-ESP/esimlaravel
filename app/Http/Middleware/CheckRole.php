<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole{
    
    public function handle(Request $request, Closure $next, ...$roles){
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Vérifie si l'utilisateur a le rôle "super"
        if (in_array('super', $roles) && Auth::user()->role === 'super') {
            return $next($request);
        }

        // Vérifie si l'utilisateur a le rôle "admin"
        if (in_array('admin', $roles) && Auth::user()->role === 'admin') {
            // Vérifie si l'ID dans la route correspond à l'ID de l'utilisateur super
            $superId = $this->getSuperId(); // Obtenez l'ID de l'utilisateur super
            $userId = $request->route('id');
            if ($userId != $superId && !$this->isAnotherAdmin($userId)) {
                return $next($request);
            }
        }

        if (in_array('social', $roles) && Auth::user()->role === 'social') {
            // Vérifie si l'ID dans la route correspond à l'ID de l'utilisateur super
            $superId = $this->getSuperId(); // Obtenez l'ID de l'utilisateur super
            $userId = $request->route('id');
            if ($userId != $superId && !$this->isAnotherAdmin($userId)) {
                return $next($request);
            }
        }

        // Vérifie si l'utilisateur a accès à sa propre ressource
        $userId = $request->route('id');
        if ($userId == Auth::id()) {
            return $next($request);
        }

        // Redirige l'utilisateur vers une page interdite
        return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé a faire cette action!');
    }
    
    private function isAnotherAdmin($userId){
        $user = User::find($userId);
        return $user && $user->role === 'admin';
    }

    private function getSuperId(){
        $userSuper = User::where('role', 'super')->first(); // Sélectionnez le premier utilisateur ayant le rôle super
        if ($userSuper) {
            return $userSuper->id; // Retournez l'ID de l'utilisateur super
        } else {
            
        // Redirige l'utilisateur vers une page interdite
        return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé a faire cette action!');  
          }
    }

}
