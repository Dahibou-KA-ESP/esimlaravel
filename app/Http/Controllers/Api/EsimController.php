<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EsimPack;
use App\Models\EsimSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EsimController extends Controller
{
    // Afficher tous les packs d'eSIM disponibles
    public function index()
    {
        $packs = EsimPack::all();
        return response()->json($packs);
    }

    // Acheter un pack d'eSIM
    public function buyPack(Request $request, $packId)
    {
        $user = auth()->user(); // L'utilisateur authentifié
        $pack = EsimPack::findOrFail($packId);

        // Calculer la date d'expiration
        $expiryDate = Carbon::now()->addDays($pack->duration_days);

        // Créer l'abonnement à ce pack
        $subscription = EsimSubscription::create([
            'user_id' => $user->id,
            'esim_pack_id' => $pack->id,
            'remaining_data' => $pack->data_volume,  // Initialiser avec le volume total
            'expiry_date' => $expiryDate,
        ]);

        return response()->json([
            'message' => 'Pack acheté avec succès.',
            'subscription' => $subscription
        ]);
    }

    // Consommer des données d'un pack d'eSIM
    public function consumeData(Request $request, $subscriptionId)
    {
        $subscription = EsimSubscription::findOrFail($subscriptionId);

        // Vérifier si le pack est expiré
        if ($subscription->isExpired()) {
            return response()->json(['error' => 'Le pack est expiré.'], 400);
        }

        $dataToConsume = $request->input('data'); // Quantité de données à consommer

        if ($dataToConsume > $subscription->remaining_data) {
            return response()->json(['error' => 'Pas assez de données disponibles.'], 400);
        }

        // Réduire le volume de données restantes
        $subscription->remaining_data -= $dataToConsume;
        $subscription->save();

        return response()->json([
            'message' => 'Données consommées avec succès.',
            'remaining_data' => $subscription->remaining_data
        ]);
    }
}
