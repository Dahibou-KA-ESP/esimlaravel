<?php

namespace App\Http\Controllers;
use DateTime;
use Exception;
use DateInterval;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Client;
use App\Models\Talons;
use App\Models\TalonV;
use App\Models\Sponsoring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class SouscriptionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    function generatePoliceNumber() {
        //le code pays
        $code_pays = 'SN'; 
        //Obtenez le prefix
        $prefix = 'PLA';
        // Obtenez la date du jour au format Ymd
        $currentDate = now()->format('dmY');
        
        // Numéro aléatoire (peut être ajusté en fonction de vos besoins)
        $randomNumber = mt_rand(00001, 99999);
    
        // Générez le numéro de police en combinant la date et la numérotation
        $num_police = $code_pays . $prefix . $currentDate .  $randomNumber  ;
    
        return $num_police;
    }

    public function createClient(Request $request) {

        $request->validate ([
            'civilite'           => 'required',
            'name'           => 'required',
            'phone'           => 'required',
            'id_produit'           => 'required',
            'garantie'           => 'required',
            'force_fiscale'           => 'required',
            'energie'           => 'required'
        ]);  

        // Vérifie si le client existe déjà en fonction de l'adresse e-mail
        $existingClient = Client::where('email', $request->email)->first();

        if (!$existingClient) {
            // Le client n'existe pas, créons un nouveau client
            $client = new Client;  
            $client->civilite    = $request->civilite;
            $client->name        = $request->name ;
            $client->phone       = $request->phone ;
            $client->profession  = $request->profession ?: null ;
            $client->email       = $request->email ?: null;
            $client->adrss       = $request->adrss ?: null;
            $client->ville       = $request->ville ?: null;
            $client->save();
        } else {
            // Le client existe déjà, utilisons le client existant
            $client = $existingClient;
        }

        $generatedNumber = $this->generatePoliceNumber();

        $clients = Client::find($client->id);
        $package= new Talons; 
        $package ->id_produit             = $request->id_produit ;
        $package->id_user                 =Auth::id();
        $package ->force_fiscale          = $request->force_fiscale;
        $package ->energie                = $request->energie;
        $package ->marque                 = $request->marque;
        $package ->model                  = $request->model;
        $package ->vin                    = $request->vin;
        $package ->date_first_circ        = $request->date_first_circ  ;
        $package ->valeur_neuf            = $request->valeur_neuf ?: null;
        $package ->valeur_venale          = $request->valeur_venale ?: null ;
        $package ->option_pers_tr         = $request->option_pers_tr ?: null ;
        $package ->nombre_place           = $request->nombre_place ?: null ;
        $package ->capitale_assure        = $request->capitale_assure ?: null ;
        $package ->compagnie              = $request->compagnie ;
        $package ->garantie               = $request->garantie ;
        $package ->prix_garantie          = $request->prix_garantie ;
        $package ->type_carrosserie       = $request->type_carrosserie;
        $package ->prime_ttc              = $request->prime_ttc ;
        $package ->prime_net              = $request->prime_net ;
        $package ->taxe                   = $request->taxe ;
        $package ->coup_police            = $request->coup_police ;
        $package ->logo                   = $request->logo ;
        $package ->fga                    = $request->fga ?: null;

        $nomDuree = $request->duree;

        $package ->duree                  = $nomDuree.' '.'mois';
        $package ->date_echeance          = $request->date_echeance ;
        // $package ->n_police               = $request->n_police;
        $package ->n_attestation_jaune    = $request->n_attestation_jaune;
        $package ->n_attestation_cedeao   = $request->n_attestation_cedeao;
        $package ->n_police               = $generatedNumber;

        // Créer un objet DateTime pour la date du jour
        $dateDuJour = new DateTime();

        // Formater la date au format jour/mois/année
        $dateFormatee = $dateDuJour->format('d/m/Y "à" H:i:s');
        
        $package ->date_etablissement     = $dateFormatee;

        // Supposons que $dateDuJour soit la date du jour obtenue comme mentionné précédemment
        $dateEffet = $request->date_effet;

        // Supposons que $duree soit la durée en mois ou en années que vous souhaitez ajouter à la date du jour
        $dureeM = DB::table('duree')->where('nom', $nomDuree)->value('nom');

        // Créez un nouvel objet DateInterval avec la durée spécifiée
        $interval = new DateInterval('P' . $dureeM . 'M'); // 'P' représente la période, 'M' représente les mois

        // Ajoutez l'intervalle à la date du jour pour obtenir la date d'échéance
        $dateEcheance = clone $dateDuJour; // Cloner la date du jour pour ne pas la modifier
        $dateEcheance->add($interval);
        // Soustrayez un jour à la date d'échéance
        $dateEcheance->sub(new DateInterval('P1D'));

        $dateEcheanceFormatee = $dateEcheance->format('d/m/Y');

        $package ->date_effet             = $dateEffet ;
        $package ->date_echeance             = $dateEcheanceFormatee;


        
        $package ->client()->associate($clients);
   
        $package->save();
   
        return redirect('/');
    }

    public function detailClient(Request $request,$client_id,$id_talon){  
        $client = Talons::where(['client_id'=>$client_id,'id_talon'=>$id_talon ])->get();
        $package = Talons::where(['client_id'=>$client_id,'id_talon'=>$id_talon ])->get();
        $packages = Talons::with(['produits' => function ($query) {
            $query->withPivot(['prix','garantie']);
                }])->where('id_talon', $id_talon)->first();
            $partenaire = Talons::where(['client_id'=>$client_id,'id_talon'=>$id_talon ])
        ->join('clients', 'talons.client_id', '=', 'clients.id')
        ->join('users', 'clients.id_partenaire', '=', 'users.id')
        ->select('users.*')
        ->first(); // Récupérer le premier résultat
            // var_dump($partenaire);exit;
        return view('client.detail',compact('client','packages','package','partenaire')); 
    }

    public function detailClientV(Request $request,$client_id,$id_talon_v){  
        $client = TalonV::where(['client_id'=>$client_id,'id_talon_v'=>$id_talon_v ])->get();
        $package = TalonV::where(['client_id'=>$client_id,'id_talon_v'=>$id_talon_v ])->get();
        
            $partenaire = TalonV::where(['client_id'=>$client_id,'id_talon_v'=>$id_talon_v ])
            ->join('clients', 'talons_voyages.client_id', '=', 'clients.id')
            ->join('users', 'clients.id_partenaire', '=', 'users.id')
            ->select('users.*')
            ->first(); // Récupérer le premier résultat
            // var_dump($partenaire);exit;
        return view('client.detail_v',compact('client','package','partenaire')); 
    }

    public function updateClientSaving(Request $request ,$id) {
        try {
        $request->validate ([
            'contact'           => 'required',
            'adrss'           => 'required',
            'email'           => 'required',
            
        ]);  
        $client=Client::find($id);
        $client->contact=$request->input('contact');
        $client->adrss=$request->input('adrss');
        $client->email=$request->input('email');
        $client->profession=$request->input('profession');

        $client->update();

        return redirect()->back()->with('success', 'Client modifié avec succès !'); 
            }
            
            catch (Exception $e) {

                return redirect()->back()->with('error', "Votre client n'a pas été modifié ! ");
            }
    
       
    }

    public function updateBeneSaving(Request $request ,$id) {
        try {
        $request->validate ([
            'contact_beneficiaire'           => 'required',
            'adrss_beneficiairer'           => 'required',
            'email_beneficiaire'           => 'required',
            
        ]);  
        $client=TalonV::find($id);
        $client->contact=$request->contact_beneficiaire;
        $client->adrss=$request->adrss_beneficiaire;
        $client->email=$request->email_beneficiaire;

        $client->update();

        return redirect()->back()->with('success', 'Bénéficiaire modifié avec succès !'); 
            }
            
            catch (Exception $e) {

                return redirect()->back()->with('error', $e);
            }
    
       
    }

    public function updateVehiculeSaving(Request $request ,$id_talon) {
        try {
        $id_talon = $request->id_talon;

        Talons::where('id_talon', $id_talon)->update([
            'marque' => $request->marque ,
            'model' => $request->model,
            'vin' => $request->vin,
        ]);
        return redirect()->back()->with('success', 'Véhicule modifié avec succès !'); 
        }
    
        catch (Exception $e) {

            return redirect()->back()->with('error', "Votre véhicule n'a pas été modifié ! ");
        }
    
       
    }

    public function updateTalonSaving(Request $request ,$id_talon) {
        try {
        $id_talon = $request->id_talon;

        Talons::where('id_talon', $id_talon)->update([
            'capital_dece' => $request->capital_dece ,
            'capital_invalidite' => $request->capital_invalidite,
            'frais_medicaux' => $request->frais_medicaux,
            'franchise_vol' => $request->franchise_vol,
            'franchise_tr' => $request->franchise_tr,
            'franchise_bdg' => $request->franchise_bdg


        ]);
        $talon = Talons::where('statut',1)
                ->where('id_talon', $id_talon)
                ->first();
        $client = Client::where('id', $talon->client_id)->first();

            Mail::send('email.condition', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'police' => $talon->n_police,
                'effet' => $talon->date_effet,
                'echeance' => $talon->date_echeance,
                'id_talon' => $talon->id_talon,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Conditions particulières');
                    $message->to($client->email);
            });
        return redirect()->back()->with('success', 'Conditions ajouté et envoyé avec succès !'); 
        }
    
        catch (Exception $e) {

            return redirect()->back()->with('error', "Un probeleme est survenu ! ");
        }
    
       
    }

    public function indexContrat(Request  $request,$client_id,$id_talon){  

        $package = Talons::where(['client_id'=>$client_id,'id_talon'=>$id_talon ])->get();

        return view('contrat',compact('package')); 
    }

    public function indexVFact(Request  $request,$client_id,$id_talon){  

        $package = Talons::where(['client_id'=>$client_id,'id_talon'=>$id_talon ])->get();
        $packages = Talons::with(['produits' => function ($query) {
            $query->withPivot(['prix','garantie']);
                }])->where('id_talon', $id_talon)->first();

        return view('v_facture',compact('package','packages')); 
    }

    public function paiementWave (){
        return redirect('/paiement_wave');
    }

    public function lancementLivraison($client_id,$id_talon) {
        try {
        $talon = Talons::where('statut',1)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
                // var_dump($talon);exit;
        
            $client = Client::where('id', $client_id)->first();

            Mail::send('email.livraison', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Livraison contrat d\'assurance automobile');
                    $message->to($client->email);
            });

            Talons::where('id_talon', $id_talon)->update(['livraison' => 1]);

            return redirect()->back()->with('success', 'Mail envoyé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Envoir mail échoué');
        }
    }

    public function annulationLivraison($client_id,$id_talon) {
        try {
        $talon = Talons::where('statut',1)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
                // var_dump($talon);exit;
        
            $client = Client::where('id', $client_id)->first();

            Mail::send('email.cancel_livraison', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Livraison contrat d\'assurance automobile');
                    $message->to($client->email);
            });

            Talons::where('id_talon', $id_talon)->update(['livraison' => 0]);

            return redirect()->back()->with('success', 'Mail envoyé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Envoir mail échoué');
        }
    }

    public function doneLivraison($client_id,$id_talon) {
        try {
        $talon = Talons::where('statut',1)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
                // var_dump($talon);exit;
        
            $client = Client::where('id', $client_id)->first();

            Mail::send('email.livraisonDone', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Livraison contrat d\'assurance automobile');
                    $message->to($client->email);
            });

            Talons::where('id_talon', $id_talon)->update(['livraison' => 2]);

            return redirect()->back()->with('success', 'Mail envoyé avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Envoir mail échoué');
        }
    }
    
    public function FinaliserSouscription($client_id,$id_talon) {        
        $talon = Talons::where('statut',0)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
                // dd($talon);
        if ($talon) {

            

            $client = Client::where('id', $client_id)->first();
           
            Mail::send('email.finaliser', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Votre devis d\'assurance automobile');
                    $message->to($client->email);
            });
            $talon->mail_count += 1;
            $talon->save();
            return redirect()->back()->with('success', 'Relance envoyer avec succès !');
                        //  return redirect(env('APP_URL').'/facture/'.$client_id.'/'.$id_talon);  
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
            return response()->json(['error' => 'Not found'], 404);
        }

            
    }
    
    public function FinaliserSouscriptionV($client_id,$id_talon_v) {        
        $talon = TalonV::where('statut',0)
                ->where('id_talon_v', $id_talon_v)
                ->where('client_id', $client_id)
                ->first();
                // dd($talon);
        if ($talon) {

            $client = Client::where('id', $client_id)->first();
           
            Mail::send('email.finaliser_v', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon_v' => $talon->id_talon_v,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Votre devis d\'assurance voyage');
                    $message->to($client->email);
            });
            $talon->mail_count += 1;
            $talon->save();
            return redirect()->back()->with('success', 'Relance envoyer avec succès !');
                        //  return redirect(env('APP_URL').'/facture/'.$client_id.'/'.$id_talon);  
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
            return response()->json(['error' => 'Not found'], 404);
        }

            
    }

    public function RenvoyerFacture($client_id,$id_talon) {  

        $talon = Talons::where('statut',1)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
        if ($talon) {


            $client = Client::where('id', $client_id)->first();
           
            Mail::send('email.confirm_com', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                
                ], function($message) use ($client) {
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('Votre contrat d\'assurance automobile');
                    $message->to($client->email);
            });

            return redirect()->back()->with('success', 'Facture renvoyer avec succès !');
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            return response()->json(['error' => 'Not found'], 404);
        }

            
    }

    public function indexSuivis(){ 
        $today1 = now();
        $twentyDaysLater = $today1->copy()->addDays(20);
        $today = Carbon::today()->format('Y-m-d'); 
        $client_en_cours = Talons::where('statut',1)->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') > ?", [$today])->with('client')->orderBy('updated_at', 'desc')->get();
        $client_expire = Talons::where('statut',1)->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') < ?", [$today])->with('client')->orderBy('updated_at', 'desc')->get();
        $police_non_livre = Talons::where('statut',1)->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') > ?", [$today])->where('livraison','!=',2)->with('client')->orderBy('updated_at', 'desc')->get();
        $client_echeance = Talons::where('statut',1)->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') BETWEEN ? AND ?", [$today1->format('Y-m-d'), $twentyDaysLater->format('Y-m-d')])->with('client')->orderBy('updated_at', 'desc')->get();

        $client_en_cours_part = Talons::where('statut',1)->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') > ?", [$today])->with('client')->orderBy('talons.updated_at', 'desc')->get();

        $client_expire_part = Talons::where('statut',1)->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') < ?", [$today])->with('client')->orderBy('talons.updated_at', 'desc')->get();

        $client_echeance_part = Talons::where('statut',1)->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') BETWEEN ? AND ?", [$today1->format('Y-m-d'), $twentyDaysLater->format('Y-m-d')])->orderBy('talons.updated_at', 'desc')->get();

        $police_non_livre_part = Talons::where('statut',1)->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())->whereRaw("STR_TO_DATE(date_echeance, '%d/%m/%Y') > ?", [$today])->where('livraison','!=',2)->with('client')->orderBy('talons.updated_at', 'desc')->get();
        return view('suivis.index',compact('client_echeance_part','client_echeance','client_en_cours','client_expire','police_non_livre','client_en_cours_part','client_expire_part','police_non_livre_part')); 
    }

    public function sponsoring(){
        $sponsoring = Sponsoring::all();
        $sponsoring2 = Sponsoring::all()
        ->groupBy(function ($item) {
            return $item->source;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('W');
            });
        });
        // var_dump($sponsoring2);exit;
        return view('suivis.sponsoring',compact('sponsoring','sponsoring2'));
    }
}