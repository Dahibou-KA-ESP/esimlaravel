<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
})->name('/');

// Route::view('index', 'index')->name('index');
Auth::routes();

Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');

Route::middleware(['role'])->group(function () {

    //route produit
        Route::post('/AddProduit', [App\Http\Controllers\CompagnieController::class, 'CreateProduit']);
        Route::get('/edit-produit/{id}/', [App\Http\Controllers\CompagnieController::class, 'UpdateProduit']);
        Route::put('/update-produit-saving/{id_produit}', [App\Http\Controllers\CompagnieController::class, 'updateProduitSaving']);
        Route::get('/delete-produit/{id}/', [App\Http\Controllers\CompagnieController::class, 'passiveDeleteProduit']);

    //route compagnie
        Route::post('/AddCompagnie', [App\Http\Controllers\CompagnieController::class, 'CreateCompagnie']);
        Route::get('/edit-compagnie/{id}/', [App\Http\Controllers\CompagnieController::class, 'UpdateCompagnie']);
        Route::get('/update-compagnie-saving/{id}', [App\Http\Controllers\CompagnieController::class, 'updateCompagnieSaving']);
        Route::get('/delete-compagnie/{id}/', [App\Http\Controllers\CompagnieController::class, 'passiveDelete']);

    //route users
        Route::post('/AddUser', [App\Http\Controllers\UsersController::class, 'CreateUser']);
            Route::get('/delete-user/{id}', [App\Http\Controllers\UsersController::class, 'passiveDelete'])
        ->middleware(['auth', CheckRole::class . ':admin,super', 'check.current.user']);

        Route::get('/restaurer-user/{id}', [App\Http\Controllers\UsersController::class, 'restaurer'])
        ->middleware(['auth', CheckRole::class . ':super', 'check.current.user']);

        
        Route::post('/update-user-status/{id}', [App\Http\Controllers\UsersController::class, 'updateStatus'])
        ->middleware(['auth', CheckRole::class . ':admin,super', 'check.current.user']);;

        Route::post('/update-user_partenaire-status/{id}', [App\Http\Controllers\PartenaireController::class, 'updateStatus']);


    //route garantie
        Route::post('/AddGarantie', [App\Http\Controllers\GarantieController::class, 'CreateGarantie']);
        Route::get('/edit-garantie/{id}/', [App\Http\Controllers\GarantieController::class, 'UpdateGarantie']);
        Route::put('/update-garantie-saving/{id_garantie}', [App\Http\Controllers\GarantieController::class, 'updateGarantieSaving']);
        Route::get('/delete-garantie/{id}/', [App\Http\Controllers\GarantieController::class, 'passiveDelete']);

    //route package
        Route::post('/addPackage', [App\Http\Controllers\PackageController::class, 'CreatePackage'])->name('addPackage');
        Route::post('/update-package-status/{id}', [App\Http\Controllers\PackageController::class,  'updateStatus']);
        Route::get('/edit-package/{id_pack}/', [App\Http\Controllers\PackageController::class, 'updatePackage'])->name('edit-package');
        Route::get('/update-package-saving/{id}', [App\Http\Controllers\PackageController::class, 'updatePackageSaving']);
        Route::get('/delete-package/{id_pack}/', [App\Http\Controllers\PackageController::class, 'passiveDelete']);

        Route::post('/AddBareme', [App\Http\Controllers\SimulationController::class, 'CreateBareme']);
Route::get('/edit-bareme/{id}/', [App\Http\Controllers\SimulationController::class, 'UpdateBareme']);   
});


// Route::get('/subscribe', [App\Http\Controllers\SubscribtionController::class, 'subscribe'])->name('subscribe');
Route::get('/inscription', [App\Http\Controllers\SubscribtionController::class, 'get'])->name('inscription');
Route::get('/subscribe','SubscribtionController@subscribe')->name('subscribe');

//route users 
Route::get('/admin/user', [App\Http\Controllers\UsersController::class, 'index']);
Route::get('/admin/user_delete', [App\Http\Controllers\UsersController::class, 'userDelete']);
Route::get('/edit-user/{id}/', [App\Http\Controllers\UsersController::class, 'UpdateUser'])->middleware('auth', CheckRole::class . ':admin,super');
Route::get('/update-user-saving/{id}', [App\Http\Controllers\UsersController::class, 'updateUserSaving']);
Route::put('/update-user-profile-saving/{id}', [App\Http\Controllers\UsersController::class, 'updateUserProfileSaving']);
Route::put('/update-profile-compagnie-saving/{id}', [App\Http\Controllers\CompagnieController::class, 'updateCompagnieProfileSaving']);
Route::get('/password_reset_user/{id}', [App\Http\Controllers\UsersController::class, 'resetPassword']);

Route::get('/password_reset_user/{id}', [App\Http\Controllers\UsersController::class, 'resetPassword']);
Route::get('/activate/{id}', [App\Http\Controllers\UsersController::class, 'indexActiveUser'])->name('activate');
Route::post('/activate_user/{id}', [App\Http\Controllers\UsersController::class, 'AcitvateUser']);

//route partenaire
Route::get('/admin/partenaire', [App\Http\Controllers\PartenaireController::class, 'index']);
Route::get('/admin/user_partenaire', [App\Http\Controllers\PartenaireController::class, 'userPartenaire']);      
Route::get('/partenaire-client/{id}/', [App\Http\Controllers\PartenaireController::class, 'clientPartenaire']);



//route compagnie
Route::get('/admin/compagnie', [App\Http\Controllers\CompagnieController::class, 'indexCompagnie']);
Route::get('/profil-compagnie/{id}/', [App\Http\Controllers\CompagnieController::class, 'profilCompagnie']);


//route garantie
Route::get('/admin/compagnie/garantie', [App\Http\Controllers\GarantieController::class, 'indexGarantie']);

//route produit
Route::get('/admin/compagnie/produit', [App\Http\Controllers\CompagnieController::class, 'produitCompagnie']);



//route simulation auto
Route::get('/admin/simulation', [App\Http\Controllers\SimulationController::class, 'indexSimulation']);
Route::post('/AddClient', [App\Http\Controllers\SimulationController::class, 'CreateClient'])->name('addclient');
Route::post('/simulate', [App\Http\Controllers\SimulationController::class, 'Simulation']);
Route::post('/traiter-donnees', [App\Http\Controllers\SimulationController::class, 'traiterDonnees'])->name('simulate');
Route::get('/admin/bareme', [App\Http\Controllers\SimulationController::class, 'indexDuree']);

Route::put('/update-bareme-saving/{id}', [App\Http\Controllers\SimulationController::class, 'updateBaremeSaving']);

//route simulation voyage
Route::get('/admin/simulation_voyage', [App\Http\Controllers\SimulationController::class, 'indexSimulationVoyage']);

Route::get('/images/{filename}', function ($filename) {
    // Décoder le nom de fichier encodé dans l'URL
    $decodedFilename = urldecode($filename);
    $filePath = 'image/logo_pack/' . $decodedFilename;
    $filePath2 = 'image/logo/' . $decodedFilename;
    if (Storage::disk('public')->exists($filePath) ){
        return Storage::disk('public')->response($filePath);
    }
    if(Storage::disk('public')->exists($filePath2))
    {
        return Storage::disk('public')->response($filePath2);
    }
    else{
        abort(404); // Fichier non trouvé 
    }   
})->name('image.display');



// route souscription 
Route::post('/soumettre-donnees', [App\Http\Controllers\SouscriptionController::class, 'createClient'])->name('souscrir');
Route::get('/detail-talon/{id}/{id_talon}/', [App\Http\Controllers\SouscriptionController::class, 'detailClient']);
Route::get('/edit-client/{id}/', [App\Http\Controllers\SouscriptionController::class, 'UpdateClient']);
Route::get('/update-client-saving/{id}', [App\Http\Controllers\SouscriptionController::class, 'updateClientSaving']);
Route::get('/update-bene-saving/{id}', [App\Http\Controllers\SouscriptionController::class, 'updateBeneSaving']);
Route::get('/update-vehicule-saving/{id_talon}', [App\Http\Controllers\SouscriptionController::class, 'updateVehiculeSaving']);
Route::get('/contrat/{id}/{id_talon}/', [App\Http\Controllers\SouscriptionController::class, 'indexContrat']);
Route::get('/v_fact/{id}/{id_talon}/', [App\Http\Controllers\SouscriptionController::class, 'indexVFact']);
Route::get('/facture/{id}/{id_talon}/', [App\Http\Controllers\FactureController::class, 'index']);
Route::get('/condition/{id}/{id_talon}/', [App\Http\Controllers\FactureController::class, 'conditionPaticuliere']);
Route::get('/facture_client/{id}/{id_talon}/', [App\Http\Controllers\FactureController::class, 'FacClient']);
Route::get('/facture_client_v/{id}/{id_talon}/', [App\Http\Controllers\FactureController::class, 'FacClient_V']);
Route::get('/lancer/{client_id}/{id_talon}/', [App\Http\Controllers\SouscriptionController::class, 'lancementLivraison']);
Route::get('/annuler/{client_id}/{id_talon}/', [App\Http\Controllers\SouscriptionController::class, 'annulationLivraison']);
Route::get('/terminer/{client_id}/{id_talon}/', [App\Http\Controllers\SouscriptionController::class, 'doneLivraison']);

Route::get('/update-talon-saving/{id_talon}', [App\Http\Controllers\SouscriptionController::class, 'updateTalonSaving']);



Route::get('/paiement_wave', function () { return view('paiement'); });
Route::put('payer_wave/',[App\Http\Controllers\SouscriptionController::class, 'paiementWave']);


//route package
Route::get('/admin/package', [App\Http\Controllers\PackageController::class, 'indexPackage']);
Route::get('admin/simulateur/pack', [App\Http\Controllers\PackageController::class, 'indexSimulateurPackage']);

// Route::post('/simulate', [App\Http\Controllers\SimulationController::class, 'Simulation']);
// Route::post('/traiter-pack', [App\Http\Controllers\PackageController::class, 'traiterDonneesPackApi'])->name('simulateP');
Route::post('/simuler-pack', [App\Http\Controllers\PackageController::class, 'SimulerPack'])->name('simulatePack');
Route::post('/souscrir-pack', [App\Http\Controllers\PackageController::class, 'SouscrirPack'])->name('souscrirPack'); 

Route::post('achat-wave', [App\Http\Controllers\API\ApiController::class, 'pWave'])->name('achat-wave');
// Route::post('achat-om', [App\Http\Controllers\API\ApiController::class, 'pOM'])->name('achat-om');

Route::get('success_url_done_wave/{client_id}/{id_talon}', [App\Http\Controllers\API\ApiController::class, 'successWave'])->name('success_wave');
Route::get('success_url_done_om/{client_id}/{id_talon}', [App\Http\Controllers\API\ApiController::class, 'successOm'])->name('success_om');

Route::get('success_url_done_V_wave/{client_id}/{id_talon_v}', [App\Http\Controllers\API\ApiVoyage::class, 'successVWave'])->name('successV_wave');
Route::get('success_url_done_V_om/{client_id}/{id_talon_v}', [App\Http\Controllers\API\ApiVoyage::class, 'successVOm'])->name('successV_om');


Route::get('mail_finalistion/{client_id}/{id_talon}', [App\Http\Controllers\SouscriptionController::class, 'FinaliserSouscription'])->name('mail_finalistion');
Route::get('mail_finalistion_v/{client_id}/{id_talon_v}', [App\Http\Controllers\SouscriptionController::class, 'FinaliserSouscriptionV']);
Route::get('renvoi_facture/{client_id}/{id_talon}', [App\Http\Controllers\SouscriptionController::class, 'RenvoyerFacture'])->name('renvoi_facture');

Route::get('/test', [App\Http\Controllers\TestOcrController::class, 'index']);
Route::post('upload', [App\Http\Controllers\TestOcrController::class, 'upload'])->name('upload');

Route::get('admin/suivis/', [App\Http\Controllers\SouscriptionController::class, 'indexSuivis']);

// Route::post('/souscrir-pack', [App\Http\Controllers\PackageController::class, 'SouscrirPack'])->name('souscrirPack'); 
Route::get('/getAnnee', [HomeController::class, 'index'])->name('getAnnee');
Route::post('/simuler-voyage', [App\Http\Controllers\SimulationController::class, 'SimulerVoyage'])->name('SimulerVoyage');
Route::get('/facture-voyage/{id}/{id_talon_v}/', [App\Http\Controllers\FactureController::class, 'indexV']);
Route::post('/souscrir-voyage', [App\Http\Controllers\SimulationController::class, 'souscrirVoyage'])->name('souscrirVoyage'); 
Route::post('achat-wave-v', [App\Http\Controllers\API\ApiVoyage::class, 'pWaveVoyage'])->name('achat-wave-v');

Route::get('/detail-talon-v/{id}/{id_talon_v}/', [App\Http\Controllers\SouscriptionController::class, 'detailClientV']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/getSimulationsAndSubscriptionsData', [App\Http\Controllers\HomeController::class, 'getDataGraphe']);
Route::get('/getClient', [App\Http\Controllers\HomeController::class, 'getClient']);
Route::get('/getSimulationsAndSubscriptionsDataPart', [App\Http\Controllers\HomeController::class, 'getDataGraphePart']);
Route::get('/getData', [App\Http\Controllers\HomeController::class, 'getData']);
Route::get('/getDataPay', [App\Http\Controllers\HomeController::class, 'getDataPay']);


Route::get('/admin/mode_paiement', [App\Http\Controllers\ModePaiement::class, 'index']);
Route::post('/AddModePaiement', [App\Http\Controllers\ModePaiement::class, 'createMpaiement']);
Route::get('/edit-paiement/{id}/', [App\Http\Controllers\ModePaiement::class, 'updateMpaiement']);
Route::put('/update-paiement-saving/{id_talon}', [App\Http\Controllers\ModePaiement::class, 'updateMPaiementSaving']);

Route::get('/historique', [App\Http\Controllers\SimulationController::class, 'historiques']);
// Route::get('admin/sponsoring/', [App\Http\Controllers\SouscriptionController::class, 'sponsoring']);
Route::get('admin/sponsoring/', [App\Http\Controllers\SouscriptionController::class, 'sponsoring'])->name('sponsoring')->middleware(['auth', CheckRole::class . ':admin,super,social', 'check.current.user']);


Route::get('/video-chat', function () {
    return view('video-chat');
})->name('video-chat');