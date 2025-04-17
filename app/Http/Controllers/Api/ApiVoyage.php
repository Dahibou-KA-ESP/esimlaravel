<?php

namespace App\Http\Controllers\API;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\TalonV;
use App\Models\ProduitV;
use App\Models\Compagnie;
use App\Models\Entreprise;
use App\Models\HistoriquesV;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pays;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ApiVoyage extends Controller
{
    function getImage($imageName){  
        $encodedFilename = urlencode($imageName);

        // Générer l'URL avec le nom de fichier encodé
        return  route('image.display', ['filename' => $encodedFilename]);
    }

    public function CompagnieApi(){
        $compagnies =Compagnie::has('produits')->orderBy('id', 'desc')->get();
        // Convertir le logo en base64 pour chaque compagnie
        foreach ($compagnies as $compagnie) {
            $compagnie->logo_base64 = $this->getImage($compagnie->logo);
        }

        return $compagnies->toJson();
    }

    public function PaysApi(){
        $pays =Pays::all();
        return $pays->toJson();
    }
    
    public function TripApi(Request $request){
       // Conversion des dates en format Carbon
        $date_depart = Carbon::createFromFormat('d/m/Y', $request->date_depart);
        $date_retour = Carbon::createFromFormat('d/m/Y', $request->date_retour);
        $pays_destination=$request->code_pays;
        // Calcul de la différence en jours
        $duree_sejour = $date_depart->diffInDays($date_retour);


        // Convertir la date de naissance en format compatible avec DateTime
        $dateNaissance = DateTime::createFromFormat('d/m/Y', $request->date_naissance);
        
        // Obtenir la date actuelle
        $dateActuelle = new DateTime();

        // Calculer la différence entre les deux dates
        $age = $dateActuelle->diff($dateNaissance)->y;

        $packages = ProduitV::where('age_min', '<=', $age)
        ->where('age_max', '>=', $age)
        ->where('duree_min', '<=', $duree_sejour)
        ->where('duree_max', '>=', $duree_sejour)
        ->where('code_pays', $pays_destination)
        ->with('compagnie', 'pays')  // Charge les relations compagnie et pays
        ->get();  // Utilise get() pour récupérer tous les résultats correspondants

        if ($packages->isNotEmpty()) {
            // Initialiser un tableau pour stocker les résultats
            $result = [];

            // Boucle sur chaque produit trouvé
            foreach ($packages as $package) {
                $encodedFilenamecomp = urlencode($package->compagnie->logo);
                $result[] = [
                    'id_compagnie'=> $package->compagnie->id, 
                    'nom' => $package->compagnie->nom_complet,  // Nom de la compagnie
                    'abr' => $package->compagnie->abr, 
                    'logo' => $package->compagnie->logo,  // Logo de la compagnie
                    'logo_base64' => route('image.display', ['filename' => $encodedFilenamecomp]),
                    'date_depart' => $date_depart->format('d/m/Y'),
                    'date_retour' => $date_retour->format('d/m/Y'),
                    'duree_voyage' => $duree_sejour,
                    'date_naissance' => $dateNaissance->format('d/m/Y'),
                    'age' => $age,
                    'pays_destination' => $package->pays ? $package->pays->Nom : 'N/A',  // Vérifie si le pays existe
                    'code_pays' => $package->code_pays,  // Code du pays
                    'id_produit_voyage'=>$package->id,
                    'contact'=>$request->contact,
                    'prime_ttc' => (int) $package->prime ,
                    
                    
                ];
            }
            $historique= new HistoriquesV;
            $entreprise = Entreprise::find('1');
            $historique ->contact                = $request->contact;
            $historique ->date_depart          = $date_depart->format('d/m/Y');
            $historique ->date_retour                = $date_retour->format('d/m/Y');
            $historique ->date_retour                = $date_retour->format('d/m/Y');
            $historique ->date_naissance            = $dateNaissance->format('d/m/Y') ;
            $historique ->age          = $age ;
            $historique ->code_pays           = $package->code_pays ;
            $historique ->duree                  = $duree_sejour;
            $historique ->id_produit_voyage                  = $package->id;
            $historique->save();
            return response()->json(['voyage' => $result], 200);
        } 
        else {
            return response()->json(['message' => 'Aucun résultat trouvé'], 404);
        }
    }

    function generatePoliceNumberV() {
        $voyage='VO';
        //le code pays
        $code_pays = 'SN'; 
        //Obtenez le prefix
        $prefix = 'PLA';
        // Obtenez la date du jour au format Ymd
        $currentDate = now()->format('dmY');
        
        // Numéro aléatoire (peut être ajusté en fonction de vos besoins)
        $randomNumber = mt_rand(00001, 99999);
    
        // Générez le numéro de police en combinant la date et la numérotation
        $num_police =$voyage. $code_pays . $prefix . $currentDate .  $randomNumber  ;
    
        return $num_police;
    }
    
    public function createCliApi(Request $request) {
        
        $request->validate([
            'contact'                   => 'required',
            'name'                      => 'required',
            // 'ville'             => 'required',
            'email'                   => 'required',
            'passport'                    => 'required',
            'compagnie_id'                     => 'required',
            'date_depart'                       => 'required',
            'date_retour'           => 'required',
            'date_naissance'                 => 'required',
            'prime_ttc'                 => 'required',
            'logo'                       => 'required',
            'code_pays'                => 'required',
            // 'image_recto_filename'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'image_verso_filename'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'age'                   => 'required'
        ]);  

        // Vérifie si le client existe déjà en fonction de l'adresse e-mail
        $existingClient = Client::where('contact', $request->contact)
        ->orWhere('email', $request->email)
        ->first();
        // $id_entreprise = auth()->user()->entreprise_id;
        
        if(!$existingClient) {
            // Le client n'existe pas, créons un nouveau client   
            $entreprise = Entreprise::find('1');
            $user = User::find($request->id_partenaire);     
            $client = new Client; 
            $client->contact     = $request->contact ; 
            $client->civilite    = $request->civilite ;
            $client->name        = $request->name ;   
            $client->profession  = $request->profession  ;
            $client->email       = $request->email ;
            $client->adrss       = $request->adrss ;
            $client->ville       = $request->ville ;

            if ($user && $user->role == 'partenaire') {
                $client->id_partenaire = $user->id; // Assigner l'ID de l'utilisateur
            }
            else if ($user && ($user->role == 'admin'  || $user->role == 'staff') ) {
                $client->id_office = $user->id; // Assigner l'ID de l'utilisateur
            }
                $client->entreprise()->associate( $entreprise);
                $client->save();
        } 
        else{
            // Le client existe déjà, utilisons le client existant
            $client = $existingClient;
        }

        $generatedNumber = $this->generatePoliceNumberV();
        $clients = Client::find($client->id);
        $compagnie = Compagnie::find($request->compagnie_id);

        $package= new TalonV;
        $dateDuJour = new DateTime();

        $dateFormatee = $dateDuJour->format('d/m/Y à H:i:s');
        
        $package ->n_passport             = $request->passport;
        $package ->date_depart            = $request->date_depart;
        $package ->date_naissance         = $request->date_naissance;
        $package ->code_pays              = $request->code_pays;
        $package ->prime_ttc              = $request->prime_ttc;
        $package ->logo                   = $request->logo;
        $package ->age                    = $request->age;
        $package ->duree                  = $request->duree.' '.'jours';
        $package ->date_retour            = $request->date_retour ;
        $package ->pays_destination       = $request->pays_destination ;
        $package ->n_police_v             = $generatedNumber;
        $package ->id_produit_voyage      = $request->id_produit_voyage;
        $package->date_etablissement      = $dateFormatee;
        $package->contact                 = $request->contact_beneficiaire ?? $request->contact ; 
        $package->name                    = $request->name_beneficiaire ?? $request->name;   
        $package->profession              = $request->profession_beneficiaire ?? $request->profession;
        $package->email                   = $request->email_beneficiaire ?? $request->email;
        $package->adrss                   = $request->adrss_beneficiaire ?? $request->adrss;
        $package->ville                   = $request->ville_beneficiaire ?? $request->ville;


        $image_photo_passport = '';   

        if ($request->hasFile('passport_filename')) {
            $logo = $request->file('passport_filename');
            $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier

           
        
            // Générer un nom de fichier unique
            $image_photo_passport = 'photo_passport'.'_'. str_replace(' ', '_',  $generatedNumber) . '.' . $logo_extension;
            // dd($image_recto);

            // dd($image_verso);

            // Définir le chemin du dossier basé sur l'ID du client
            $storage_path = storage_path('app/public/image/passport/' . $client->id);

            // Vérifier si le dossier existe, sinon le créer
            if (!file_exists($storage_path)) {
                mkdir($storage_path, 0755, true); // Créer le dossier avec des permissions appropriées
            }

            try {
                // Déplacer les fichiers vers le dossier de stockage approprié
                $logo->move($storage_path, $image_photo_passport);
            } 
            catch (FileException $e) {
                    // Gérer les erreurs de déplacement de fichier
                    return redirect()->back()->with('error', 'Photo non téléchargée');
            }
        
        }
        $package ->image_verso_v          = $image_photo_passport;
        
        $package ->client()->associate($clients);
        $package ->compagnie()->associate($compagnie);
        $package->save(); 
        
        $client=$package->client()->get();
            // var_dump($package);exit;
        $invoiceUrl =   env('APP_URL'). '/'.'facture-voyage/'.$client[0]->id.'/'. $package->id_talon_v;

        // Retourner l'URL de la facture dans la réponse
        return response()->json(['message' => 'Facture générée avec succès','url_facture_v'=>$invoiceUrl ], 200);
    
    }

    public function getToken(){
        $client_id = env('CLIENT_ID_OM');
        $bearer = "Bearer ";
        $client_secret = env('CLIENT_SECRET_OM');
        $grant_type = "client_credentials";
        
        $response_token = Http::asForm()->post('https://api.orange-sonatel.com/oauth/token',[
            'Content-Type' => 'application/json',
            'grant_type' => $grant_type,
            'client_secret' => $client_secret,
            'client_id' => $client_id,
        ]);
       
        // Stock Token
        $access_token = $bearer.$response_token->json()['access_token'];
        // dd($access_token);
        return  $access_token;

    }

    public function paiementOMV(Request $request) {

        $accessToken = $this->getToken();

        $client_id  = $request->client_id;  
        $id_talon_v  = $request->id_talon_v;
        $talon = TalonV::where('statut', 0)
                        ->where('id_talon_v', $id_talon_v)
                        ->where('client_id', $client_id)
                        ->first();
        $amount = $talon->prime_ttc;
        // $amount = 100;
        // dd($amount);
        // Crypter les paramètres client_id et id_talon
        try {
            // Données à envoyer dans la requête
            $requestData = [
                "amount" => [
                    "unit" => "XOF",
                    "value" => $amount
                ],
                "callbackCancelUrl" => "https://my-cancel-url.com",
                // "callbackSuccessUrl" => "https://comparateurtest.assurpro.sn/success_url_done_V_om/".$client_id."/".$id_talon_v,
                "callbackSuccessUrl" => "https://comparateur.assurpro.sn/success_url_done_v_om/".$client_id."/".$id_talon_v,
                "code" => 523734,
                // "metadata" => [],
                "name" => "PLATINE ASSURANCES",
                "validity" => 600
            ];
            // Utiliser le jeton d'accès dans votre requête
            $response = Http::withHeaders([
                                "Authorization" => $accessToken,
                                'Content-Type' => 'application/json',
                                "X-API-Key" => 'Z229M3D9iG5QgXbdbpPY8cjT86k2Tgb2j3G9b6kPXiDM8pY8QZdc95',
                            ])->post('https://api.orange-sonatel.com/api/eWallet/v4/qrcode', $requestData);
            // Traiter la réponse
            $responseData = $response->json();
            // dd($responseData);
            // Faire quelque chose avec les données de réponse
            return response()->json(['paiement_om' => $responseData], 200);
        }   
        catch (\Exception $e) {
            // Gérer les erreurs de requête
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } 
    }

    public function pWaveVoyage(Request $request) {
        $result = json_decode($request->result);
        // var_dump($result);exit;
        $talon = TalonV::where('statut', 0)
                    ->where('id_talon_v', $result->id_talon_v)
                    ->where('client_id', $result->client_id)
                    ->first();

        if ($talon) {
            $amount = $talon->prime_ttc;
            // dd($amount);
                $client_id=$result->client_id;
                $id_talon_v=$result->id_talon_v;
            // Paramètres de la requête vers l'API Wave
            $checkout_params = [
                "amount" => $amount ,
                // "amount" => 100 ,
                "currency" => "XOF",
                "error_url" => "https://example.com/error",
                // "success_url" => "https://comparateurtest.assurpro.sn/success_url_done_V_wave/".$client_id."/".$id_talon_v
                "success_url" => "https://comparateur.assurpro.sn/success_url_done_v_wave/".$client_id."/".$id_talon_v 
            ];

            $wave_key= env('KEY_API_WAVE');
            try {
                // Effectuer la requête vers l'API Wave
                $response = Http::withHeaders([
                    "Authorization" => "Bearer ".$wave_key,
                    "Content-Type" => "application/json",                   
                ])->post("https://api.wave.com/v1/checkout/sessions", $checkout_params);

                // Vérifier si la requête a réussi
                $response->throw();

                // Récupérer l'URL de redirection depuis la réponse
                $wave_launch_url = $response["wave_launch_url"];

                // Retourner l'URL de redirection dans la réponse JSON
                // return response()->json(['paiement_url' => $wave_launch_url], 200);
                return redirect($wave_launch_url, 302);

            } catch (\Exception $e) {
                // Gérer les erreurs de requête
                return view('errorpaiement');
            }
        } else {
            return view('errorpaiement');
        }
    }

    public function successVWave($client_id,$id_talon_v) {
        // Décrypter les paramètres client_id et id_talon
        $client_partenaire= Client::where('id', $client_id)
        ->first();

        $talon = TalonV::where('statut',0)
                ->where('id_talon_v', $id_talon_v)
                ->where('client_id', $client_id)
                ->first();
                // dd($talon);
        if ($talon) {
            $client = Client::where('id', $client_id)->first();
           
            Mail::send('email.confirm_v', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon_v' => $talon->id_talon_v,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Votre contrat d\'assurance voyage');
                    $message->to($client->email);
            });

            Mail::send('email.contact_v', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'destination' => $talon->pays_destination,
                'age' => $talon->age,
                'dure' => $talon->duree,
                'effet' => $talon->date_depart,
                'id_client' => $talon->client_id,
                'id_talon_v' => $talon->id_talon_v,
                'echeance' => $talon->date_retour,
                'police' => $talon->n_police_v,
                'prime' => $talon->prime_ttc,


                ], function($message){
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('NOUVELLE SOUSCRIPTION VOYAGE');
                    $message->to('contact@platineassurances.sn');
            });
            // Mettre à jour le statut du talon à 1
            $talon->update(['statut' => 1,'mode_paiement' => 1]);
            // Récupération des données de la requête
            $app_url = env('APP_URL');

            $content_sms = "Bonjour {$client->name}," ."\n".
            "Nous vous remercions pour votre souscription auprès de Platine assurances!"."\n".
            "Retrouver votre facture:{$app_url}/facture_client_v/{$talon->client_id}/{$talon->id_talon_v}"."\n".
            "Votre attestation vous sera livrée sous 24h ouvrées";
            $subject_sms='Comparateur Voyage';

            $login = env('login_sms');
            $api_access_key = env('api_access_key_sms');
            $token = env('token_sms');
            $subject = urlencode($subject_sms);
            $signature = urlencode(env('signature_sms'));
            $recipient = $client->contact;
            $content = urlencode($content_sms);

            // Générer le timestamp et le hash
            $timestamp = time();
            $msgToEncrypt = $token . $subject . $signature . $recipient . $content . $timestamp;
            $key = hash_hmac('sha1', $msgToEncrypt, $api_access_key);

            // Construire l'URL de la requête
            $uri = "https://api.orangesmspro.sn:8443/api?token={$token}&subject={$subject}&signature={$signature}&recipient={$recipient}&content={$content}&timestamp={$timestamp}&key={$key}";

            // Envoyer la requête HTTP GET avec les identifiants d'authentification
            $response = Http::withBasicAuth($login, $token)->get($uri);

            if($response->successful()){
                if($client_partenaire->id_partenaire !== null || $client_partenaire->id_office !== null){
                    // Le talon a été trouvé, vous pouvez maintenant accéder à ses propriétés
                return redirect(env('APP_URL').'/detail-talon-v/'.$client_id.'/'.$id_talon_v);  
                }
                else{
                // Le talon a été trouvé, vous pouvez maintenant accéder à ses propriétés
                return redirect(env('APP_URL').'/facture-voyage/'.$client_id.'/'.$id_talon_v);  
                }
            }    
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
            return response()->json(['error' => 'Not found'], 404);
        }

            
    }

    public function successVOm($client_id,$id_talon_v) {
        // Décrypter les paramètres client_id et id_talon
        $client_partenaire= Client::where('id', $client_id)
        ->first();

        $talon = TalonV::where('statut',0)
                ->where('id_talon_v', $id_talon_v)
                ->where('client_id', $client_id)
                ->first();
                // dd($talon);
        if ($talon) {
            $client = Client::where('id', $client_id)->first();
           
            Mail::send('email.confirm_v', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon_v' => $talon->id_talon_v,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Votre contrat d\'assurance voyage');
                    $message->to($client->email);
            });

            Mail::send('email.contact_v', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'destination' => $talon->pays_destination,
                'age' => $talon->age,
                'dure' => $talon->duree,
                'effet' => $talon->date_depart,
                'id_client' => $talon->client_id,
                'id_talon_v' => $talon->id_talon_v,
                'echeance' => $talon->date_retour,
                'police' => $talon->n_police_v,
                'prime' => $talon->prime_ttc,


                ], function($message){
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('NOUVELLE SOUSCRIPTION VOYAGE');
                    $message->to('contact@platineassurances.sn');
            });
           // Mettre à jour le statut du talon à 1
           $talon->update(['statut' => 1,'mode_paiement' => 2]);
            // Récupération des données de la requête
            $app_url = env('APP_URL');

            $content_sms = "Bonjour {$client->name}," .
            "Nous vous remercions pour votre souscription auprès de Platine assurances!" .
            "Retrouver votre facture:{$app_url}/facture_client_v/{$talon->client_id}/{$talon->id_talon_v}" .
            "Votre attestation vous sera livrée sous 24h ouvrées";
            $subject_sms='Comparateur Voyage';

            $login = env('login_sms');
            $api_access_key = env('api_access_key_sms');
            $token = env('token_sms');
            $subject = urlencode($subject_sms);
            $signature = urlencode(env('signature_sms'));
            $recipient = $client->contact;
            $content = urlencode($content_sms);

            // Générer le timestamp et le hash
            $timestamp = time();
            $msgToEncrypt = $token . $subject . $signature . $recipient . $content . $timestamp;
            $key = hash_hmac('sha1', $msgToEncrypt, $api_access_key);

            // Construire l'URL de la requête
            $uri = "https://api.orangesmspro.sn:8443/api?token={$token}&subject={$subject}&signature={$signature}&recipient={$recipient}&content={$content}&timestamp={$timestamp}&key={$key}";

            // Envoyer la requête HTTP GET avec les identifiants d'authentification
            $response = Http::withBasicAuth($login, $token)->get($uri);
            if($response->successful()){
                if($client_partenaire->id_partenaire !== null || $client_partenaire->id_office !== null){
                    // Le talon a été trouvé, vous pouvez maintenant accéder à ses propriétés
                return redirect(env('APP_URL').'/detail-talon-v/'.$client_id.'/'.$id_talon_v);  
                }
                else{
                // Le talon a été trouvé, vous pouvez maintenant accéder à ses propriétés
                return redirect(env('APP_URL').'/facture-voyage/'.$client_id.'/'.$id_talon_v);  
                }
            }    
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
            return response()->json(['error' => 'Not found'], 404);
        }

            
    }
}

