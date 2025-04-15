<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'description' => 'jetsimo pour une connexion partout',
        'version' => '1.0.0',
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [UserController::class, 'login'])->name('login');  // Route pour se connecter

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);  // Afficher les informations de l'utilisateur
    Route::put('/user', [UserController::class, 'update']); // Mettre à jour le profil de l'utilisateur
    Route::post('/user/logout', [UserController::class, 'logout']); // Déconnexion de l'utilisateur
});

Route::prefix('/clients')->name('client')->group(function () {
    Route::get('', [ClientController::class, 'index'])->name('.index');
    Route::post('', [ClientController::class, 'store'])->name('.store');
    Route::get('/{client}', [ClientController::class, 'show'])->name('.show');
    Route::put('/{client}', [ClientController::class, 'update'])->name('.update');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->name('.destroy');
});

// Route::middleware(['auth:sanctum', 'can:is-reseller'])->group(function () {
//     // Pour les revendeurs
//     Route::post('/esims/bulk', [ESIMController::class, 'bulkBuy']); // Acheter des eSIMs en gros
//     Route::get('/users/{userId}/esims', [ESIMController::class, 'getUserEsims']); // Voir les eSIMs d'un utilisateur spécifique
// });
