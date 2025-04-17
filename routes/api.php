<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
        
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
   
     
}); 
Route::middleware('api.key')->group(function () {
    // Définissez vos routes API ici
    
    Route::get('compagnie', [App\Http\Controllers\API\ApiController::class, 'compagnieApi']);
    Route::post('donnees-api', [App\Http\Controllers\API\ApiController::class, 'traiterDonneesApi']);
    Route::post('traiter-pack', [App\Http\Controllers\API\ApiController::class, 'traiterDonneesPackApi']);
    Route::post('sponsoringApi', [App\Http\Controllers\API\ApiController::class, 'sponsoringApi']);
    Route::post('sponsoringapi2', [App\Http\Controllers\API\ApiController::class, 'sponsoring']);
    // Route::post('achat-wavee', [App\Http\Controllers\API\ApiController::class, 'paiementWave']);
    Route::post('add-client', [App\Http\Controllers\API\ApiController::class, 'createCliApi']);
    Route::post('ocr', [App\Http\Controllers\API\ApiController::class, 'OCR']);
    Route::post('sante-om', [App\Http\Controllers\API\ApiController::class, 'OmSante']);
    Route::post('sante-wave', [App\Http\Controllers\API\ApiController::class, 'WaveSante']);
    Route::post('achat-om', [App\Http\Controllers\API\ApiController::class, 'paiementOM']);
    Route::post('send-sms', [App\Http\Controllers\API\ApiController::class, 'sendSms']);


    Route::post('sim-voyage', [App\Http\Controllers\API\ApiVoyage::class, 'TripApi']);
    Route::get('compagnieVoyage', [App\Http\Controllers\API\ApiVoyage::class, 'compagnieApi']);
    Route::get('pays', [App\Http\Controllers\API\ApiVoyage::class, 'PaysApi']);
    Route::post('add-client_voyage', [App\Http\Controllers\API\ApiVoyage::class, 'createCliApi']);
    Route::post('gettoken', [App\Http\Controllers\API\ApiVoyage::class, 'getToken']);
    Route::post('achat-om-v', [App\Http\Controllers\API\ApiVoyage::class, 'paiementOMV']);


});

// Route::post('sim-voyage', [App\Http\Controllers\API\ApiVoyage::class, 'TripApi']);




