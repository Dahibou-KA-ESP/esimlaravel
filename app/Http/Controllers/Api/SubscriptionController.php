<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Validator;
use App\Http\Resources\ProductResource;
   
class SubscriptionController extends Controller {
    public function store(Request $request) {
        // Valider les données d'entrée
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'required|integer',
            // Autres champs requis pour l'abonnement
        ]);

        // Créer un nouvel abonnement
        $subscription = Subscription::create($validatedData);

        // Répondre avec l'abonnement créé
        return response()->json($subscription, 201);
    }
}