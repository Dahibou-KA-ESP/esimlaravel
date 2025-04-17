<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Pays;
use App\Models\Bareme;
use App\Models\TalonV;
use PHPUnit\Util\Json;
use App\Models\Produit;
use App\Models\Garanties;
use App\Models\Historiques;
use App\Models\HistoriquesV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SimulationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexSimulation(){
        $bareme= Bareme::all();
        $garanties=Garanties::all();
        return view('simulation.index',compact('bareme','garanties')); 
    }

    public function indexDuree(){
        $bareme= Bareme::all();
        return view('bareme',compact('bareme')); 
    }

    public function historiques(){
        $simulation = Historiques::select('*')->orderBy('created_at', 'desc')->get();
        $simulationV = HistoriquesV::select('*')->orderBy('created_at', 'desc')->get();
        return view('historique.index',compact('simulationV','simulation')); 
    }

    public function createBareme(Request $request) {

        $request->validate ([
            'nom'           => 'required',
            'taux'           => 'required',
            
        ]);  
        
        $bareme = new Bareme($request->only([ 'nom', 'taux' ]));  
        $bareme ->nom                   = $request->nom ?: null;
        $bareme ->taux                   = $request->taux ?: null;

        $bareme->save();
    

        return redirect()->back()->with('success', 'Produit ajoutée avec succès !');
    }

    public function updateBareme(Request $request ,$id) {
        
        $bareme = DB::table('duree')->where('id','=',$id)->get();
        return view('/edit_bareme',compact('bareme'));
       
    }
    
    public function updateBaremeSaving(Request $request ,$id) {
       
        $bareme=Bareme::find($id);
        $bareme->taux        =$request->input('taux');
        
        $bareme->update();



        return redirect()->back()->with('success', 'bareme modifier avec succès !');
    
       
    }
   
    public function indexSimulationVoyage(){
        $codepays= Pays::all();
        return view('simulation.index_voyage',compact('codepays')); 
    }

    public function SimulerVoyage(Request $request) {
        $url = env('APP_URL') . '/api/sim-voyage';
        $keyurl = env('API_KEY');

        $client = new \GuzzleHttp\Client();
        
        try {        

            // Valider les données du formulaire, y compris la correspondance des mots de passe
            $request->validate([
                'date_naissance' => 'required',
                'date_retour' => 'required',
                'date_depart' => 'required',
                'code_pays' => 'required',
                'contact' => 'required',
            ]);
        

            $donnee= [
                'date_naissance'=>(new DateTime($request->date_naissance))->format('d/m/Y'),
                'date_retour'=> (new DateTime($request->date_retour))->format('d/m/Y'),
                'date_depart'=>(new DateTime($request->date_depart))->format('d/m/Y'),
                'code_pays'=> $request->code_pays,
                'contact'=> $request->contact,
            ];

            // Example headers, including the CSRF token
            $headers = [
                'Content-Type' => 'application/json',
                'X-API-Key' => $keyurl
            ];
            // Make the POST request with the specified data and headers
            $response = $client->request('POST', $url, [
                'json' => $donnee,
                'headers' => $headers
            ]); 
            // Récupérer le contenu de la réponse
            $responseData = $response->getBody()->getContents();
            $responseData = json_decode($responseData, true); // Convertir en tableau associatif
                
                // $response = $postData;
                // var_dump($responseData);exit;
                return view('simulation.result_voyage', compact('responseData'));

        } 
        catch (\Exception $e) {
                // Handle exceptions
                // echo "An error occurred: " . $e->getMessage();
                $response=  $e->getMessage();
        }

    } 

    public function SouscrirVoyage(Request $request) { 
        $url = env('APP_URL') . '/api/add-client_voyage';
        $keyurl = env('API_KEY');
    
        $client = new \GuzzleHttp\Client();
        $url_facture_v = null; // Initialiser la variable ici
    
        try {     
            // Valider les données du formulaire, y compris la correspondance des mots de passe
            $request->validate([
                'contact'                   => 'required',
                'name'                      => 'required',
                'ville'                     => 'required',
                'email'                     => 'required',
                // 'contact_beneficiaire'      => 'required',
                // 'name_beneficiaire'         => 'required',
                // 'ville_beneficiaire'        => 'required',
                // 'email_beneficiaire'        => 'required',
                'passport'                  => 'required',
                'compagnie_id'              => 'required',
                'date_depart'               => 'required',
                'date_retour'               => 'required',
                'date_naissance'            => 'required',
                'prime_ttc'                 => 'required',
                'logo'                      => 'required',
                'code_pays'                 => 'required',
                // 'image_recto_filename'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
                // 'image_verso_filename'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'age'                       => 'required'
            ]);  
                
            $partenaireId = (string)auth()->user()->id; // Supposons que l'ID du partenaire est l'ID de l'utilisateur connecté
    
            $donnee= [
                'civilite' => $request->civilite,
                'contact' => $request->contact,
                'name' => $request->name,
                'email' => $request->email,
                'ville' => $request->ville,
                'profession' => $request->profession,
                'contact_beneficiaire' => $request->contact_beneficiaire ?? $request->contact,
                'name_beneficiaire' => $request->name_beneficiaire ?? $request->name  ,
                'email_beneficiaire' => $request->email_beneficiaire ?? $request->email,
                'ville_beneficiaire' => $request->ville_beneficiaire ?? $request->ville,
                'profession_beneficiaire' => $request->profession_beneficiaire ?? $request->profession,
                'date_depart' => $request->date_depart,
                'date_retour' => $request->date_retour,
                'date_naissance' => $request->date_naissance,
                'adrss' => $request->adrss,
                'prime_ttc' => $request->prime_ttc,
                'logo' => $request->logo,
                'code_pays' => $request->code_pays,
                'pays_destination' => $request->pays_destination,
                'passport' => $request->passport,
                'age' => $request->age,
                'duree' => $request->duree,
                'compagnie_id' => $request->compagnie_id,
                'id_produit_voyage' => $request->id_produit_voyage,
                'id_partenaire' => $partenaireId  // Ajout de l'ID du partenaire connecté
            ];                
    
            // Example headers, including the CSRF token
            $headers = [
                'Content-Type' => 'application/json',
                'X-API-Key' => $keyurl
            ];            
            // var_dump($donnee);exit;
    
            // Make the POST request with the specified data and headers
            $response = $client->request('POST', $url, [
                'json' => $donnee,
                'headers' => $headers
            ]); 
            // Récupérer le contenu de la réponse
            $responseData = json_decode($response->getBody()->getContents(), true);
            // var_dump($responseData);exit;
            // Récupérer l'URL de la facture
            if (isset($responseData['url_facture_v'])) {
                $url_facture_v = $responseData['url_facture_v'];
            } else {
                throw new \Exception('URL de facture non trouvée dans la réponse');
            }
        } 
        catch (\Exception $e) {
            // Handle exceptions
            return redirect()->back()->with('error', 'Une erreur s\'est produite: ' . $e->getMessage());
        }
        // Rediriger vers l'URL de la facture si elle est définie
        if ($url_facture_v) {
            return redirect($url_facture_v);
        } else {
            return redirect()->back()->with('error', 'URL de facture non disponible.');
        }
    }
} 
