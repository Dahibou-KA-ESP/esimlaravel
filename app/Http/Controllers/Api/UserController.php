<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Méthode de connexion
    public function login(Request $request)
    {
        // Valider les informations d'identification
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Tentative d'authentification
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // Créer et retourner un token pour l'utilisateur
            $token = $user->createToken('JetsimoAPI')->plainTextToken;
            return response()->json([
                'message' => 'Authentification réussie.',
                'token' => $token
            ]);
        }

        return response()->json(['message' => 'Identifiants incorrects.'], 401);
    }

    // Afficher les informations de l'utilisateur authentifié
    public function show(Request $request)
    {
        $user = $request->user();  // Récupérer l'utilisateur authentifié
        return response()->json([
            'user' => $user,
            'credit' => $user->credit  // Afficher le crédit de l'utilisateur
        ]);
    }

    // Mettre à jour le profil de l'utilisateur
    public function update(Request $request)
    {
        $user = $request->user();  // Utilisateur authentifié

        // Validation des nouvelles informations
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Mise à jour des informations
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Profil mis à jour avec succès.']);
    }

    // Déconnexion de l'utilisateur (révocation du token)
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens->delete(); // Supprimer tous les tokens de l'utilisateur

        return response()->json(['message' => 'Déconnexion réussie.']);
    }


}
