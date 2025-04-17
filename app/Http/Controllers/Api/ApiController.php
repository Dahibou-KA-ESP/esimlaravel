<?php

namespace App\Http\Controllers\API;
use DateTime;
use DateInterval;
use App\Models\User;
use App\Models\Client;
use App\Models\Talons;
use App\Models\TalonV;
use App\Models\Package;
use Barryvdh\DomPDF\PDF;
use App\Models\Compagnie;
use App\Models\Sponsoring;
use App\Models\Entreprise;
use App\Models\Historiques;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client as ClientGuzzle;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ApiController extends Controller{
   
    public function CompagnieApi(){
        $compagnies =Compagnie::has('produit')->orderBy('id', 'desc')->get();
        // Convertir le logo en base64 pour chaque compagnie
        foreach ($compagnies as $compagnie) {
            // $logoContent = Storage::get('/public/image/logo/' . $compagnie->logo);   
            // $logoBase64 = base64_encode($logoContent);
            // $dataURL = 'data:image/png;base64,' . $logoBase64;
            $compagnie->logo_base64 = $this->getImage($compagnie->logo);
        }

        return $compagnies->toJson();
    }
   
    public function sponsoringApi(Request $request){
        
        $request->validate([
            'source'               => 'required',
            'ipadress'             => 'required',
            'country'             => 'required',
        ]); 

        $sponsoring = new sponsoring; 
        $sponsoring->country     = $request->country ; 
        $sponsoring->ipadress    = $request->ipadress  ;
        $sponsoring->source      = $request->source ;

        $sponsoring->save();

        return response()->json(['message' => 'sponsoring enregistré', 'sponsort' => $sponsoring ], 200);
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
        // var_dump( $sponsoring2);exit;
        return response()->json(['sponsort' => $sponsoring2 ], 200);
    }

    public function traiterDonneesPackApi(Request $request){      
        $packages = Package::with(['produits.garantie'])->where('statut',1)->get();
        $duree = $request->duree;
        $formattedPackages = [];
        $Duree = DB::table('duree')->where('nom', $duree)->value('taux');
            if($request->date_first_circ === null){
                foreach ($packages as $package) {
                    $produits = $package->produits;
                    // Filtrer les produits pour garder seulement les garanties spécifiques ("rc", "dr", "sr")
                    // $garantiesFiltrees = $produits->filter(function ($produit) {
                    //     $garantieNomCourt = $produit->garantie->nom_court;
                    //     // Garder les garanties spécifiques
                    //     return in_array($garantieNomCourt, ['rc', 'dr', 'sr']);
                    // });
               
                    $encodedFilename = urlencode($package->logo_pack);
                    $formattedPackage = [
                        // 'id_pack' => $package->id_pack,
                        // 'id_entreprise' => $package->id_entreprise,
                        'nom' => $package->nom,
                        'nom_court' => $package->nom_court,
                        'description' => $package->description,
                        // "logo_pack" => $package->logo_pack,
                        "logo_pack_base64" => route('image.display', ['filename' => $encodedFilename]),
                        // 'created_at' => $package->created_at,
                        // 'updated_at' => $package->updated_at,
                        // 'deleted_at' => $package->deleted_at,
                        'compagnies' => [],
                        'validpack' => TRUE,
                        'garanties' => [] // Ajoutez un tableau pour les garanties
                    ];          
                    if($duree < 3 ){
                        $garantiesFiltrees = $produits->filter(function ($produit) {
                            $garantieNomCourt = $produit->garantie->nom_court;
                            // Garder les garanties spécifiques
                            return in_array($garantieNomCourt, ['rc', 'dr', 'sr']);
                        });
                        // Regrouper les produits par compagnie
                        $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                        // Calcul du FGA pour chaque produit de la compagnie
                        if (
                            // Au moins une des garanties spécifiques et aucune autre garantie
                            ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                            // Ou au moins les trois garanties spécifiques
                            ($garantiesFiltrees->count() === 3)
                        ) {
                            foreach ($produitsParCompagnie as $compagnieId => $produits) {                        
                                $compagnie = $produits->first()->compagnie;
                                $fgaTotal = 0;
                                $primeTotal = 0;
                                $garantiespack = [];
                                foreach ($produits as $produit) {
                                    // Vérification de la garantie 'rc'
                                    switch ($produit->garantie->nom_court) {
                                        case 'rc':
                                            if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                $fgaPercent = 2.5;
                                                $produit->prime -= 0.2 * $produit->prime;
                                                $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                $produit->fga = round($fgaAmount * $Duree);
                                                $produit->prime = round($produit->prime* $Duree);
                                                $fgaTotal = $produit->fga;
                                                $primeTotal = $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'dr':
                                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                                $produit->prime = round($produit->prime * $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'sr':
                                            if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                    }
   
                                }
                       
                           
                                $formattedProduits = $produits->map(function ($produit) {        
                                    return [
                                        'id_produit' => $produit->id_produit,
                                        'id_garantie' => $produit->id_garantie,
                                        'force_fiscale' => $produit->force_fiscale,
                                        'energie' => $produit->energie,
                                        'cat_vehicule' => $produit->cat_vehicule,
                                        'type_carrosserie' => $produit->type_carrosserie,
                                        'prime' => $produit->prime,                      
                                        // 'Duree' => $Duree,
                                        'compagnie_id' => $produit->compagnie_id,
                                        // 'remorque' => $produit->remorque,
                                        // 'created_at' => $produit->created_at,
                                        // 'updated_at' => $produit->updated_at,
                                    ];
                                });
   
                                $Coup_police=3000;
                                $taxe_value=0.14;
                                $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                $encodedFilenamecomp2 = urlencode($compagnie->logo);
                                $formattedPackage['compagnies'][] = [
                                    'id_compagnie' => $compagnie->id,
                                    'nom_complet' => $compagnie->nom_complet,
                                    'abr' => $compagnie->abr,
                                    'logo' => $compagnie->logo,
                                    'force_fiscale' => $request->force_fiscale,
                                    'energie' => $produit->energie,
                                    'cat_vehicule' => $produit->cat_vehicule,
                                    'type_carrosserie' => $request->type_carrosserie,
                                    'prime_net'=>$primeTotal,
                                    'coup_police' => $Coup_police,
                                    'taxe'=> $taxe,
                                    'fga' => $fgaTotal,
                                    'prime_ttc' =>  $prime_ttc,
                                    'duree' => $duree,
                                    'contact' => $request->contact,
                                    'nombre_place' =>  $request->nombre_place,
                                    'valeur_venale' =>  $request->valeur_venale,
                                    'date_first_circ' =>  $request->date_first_circ,
                                    'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                    'logo_base64' => route('image.display', ['filename' => $encodedFilenamecomp2]),
                                    'garanties' => $garantiespack,
                                   
                                    // 'produits' => $formattedProduits->toArray(),
                                ];
                            }
                        }
                        else{
                            if($duree < 3){
                                $formattedPackage['compagnies'] = NULL;
                                $formattedPackage['validpack'] = FALSE;
                                $formattedPackage['errormessage'] = 'Veuillez selectionner une durée supérieure à 3 mois pour voir ce package';
                            }else if($request->valeur_venale === NULL || $request->valeur_venale == 'NaN' || $request->valeur_venale ==0){
                            $formattedPackage['compagnies'] = NULL;
                            $formattedPackage['validpack'] = FALSE;
                            $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur actuelle de votre véhicule  pour  voir ce package' ;
                        }
                        else if($request->valeur_venale < 1000000){
                                $formattedPackage['compagnies'] = NULL;
                                $formattedPackage['validpack'] = FALSE;
                                $formattedPackage['errormessage'] = 'La valeur actuelle de votre véhicule doit être supérieure à 1 000 000 FCFA' ;
                            }
                        }
                    }
                    else if($duree  >= 3  ){
                        if(($request->valeur_venale !==NULL &&   $request->valeur_venale !=0  && $request->valeur_venale !== 'NaN' && $request->valeur_venale >=1000000  )){
                            $garantiesFiltrees = $produits->filter(function ($produit) {
                                $garantieNomCourt = $produit->garantie->nom_court;
                                // Garder les garanties spécifiques
                                return in_array($garantieNomCourt, ['rc', 'dr', 'sr','bdg', 'ar','icd','vol']);
                            });
                           
                            // Regrouper les produits par compagnie
                            $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                            // Calcul du FGA pour chaque produit de la compagnie
                            if (
                                // Au moins une des garanties spécifiques et aucune autre garantie
                                ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                                // Ou au moins les trois garanties spécifiques
                                ($garantiesFiltrees->count() === 7) ||
                                // Nouvelle condition pour le nombre de garanties acceptables par package
                                $produitsParCompagnie->filter(function ($produitsCompagnie) {
                                    // Compter le nombre de garanties uniques dans cette compagnie
                                    $garantiesUniques = $produitsCompagnie->pluck('garantie.nom_court')->unique();
                                    $nombreGaranties = $garantiesUniques->count();
                           
                                    // Vérifier si le nombre de garanties est entre 3 et 5 inclusivement
                                    return $nombreGaranties >= 3 && $nombreGaranties <= 7 ;
                                })->isNotEmpty() // Au moins un package avec le bon nombre de garanties
                            ){
                                foreach ($produitsParCompagnie as $compagnieId => $produits) {
                               
                                    $compagnie = $produits->first()->compagnie;
                                    $fgaTotal = 0;
                                    $primeTotal = 0;
                                    $garantiespack = [];
                                   
                                    foreach ($produits as $produit) {
                                        // Vérification de la garantie 'rc'
                                       
                                        switch ($produit->garantie->nom_court) {
                                            case 'rc':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                    $fgaPercent = 2.5;
                                                    $produit->prime -= 0.2 * $produit->prime;
                                                    $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                    $produit->fga = round($fgaAmount * $Duree);
                                                    $produit->prime = round($produit->prime* $Duree);
                                                    $fgaTotal = $produit->fga;
                                                    $primeTotal = $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'dr':
                                                if($produit->cat_vehicule == $request->cat_vehicule){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'sr':
                                                if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                    $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'ar':
                                                if($produit->cat_vehicule == $request->cat_vehicule ){
                                                    $capitale_assure=500000;    
                                                    $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'bdg':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;  
                                            case 'icd':
                                                if($produit->cat_vehicule == $request->cat_vehicule ){    
                                                    $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'vol':
                                                if($produit->cat_vehicule == $request->cat_vehicule){    
                                                    $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                        }
       
                                    }
                           
                               
                                    $formattedProduits = $produits->map(function ($produit) {        
                                        return [
                                            'id_produit' => $produit->id_produit,
                                            'id_garantie' => $produit->id_garantie,
                                            'force_fiscale' => $produit->force_fiscale,
                                            'energie' => $produit->energie,
                                            'cat_vehicule' => $produit->cat_vehicule,
                                            'type_carrosserie' => $produit->type_carrosserie,
                                            'prime' => $produit->prime,                      
                                            // 'Duree' => $Duree,
                                            'compagnie_id' => $produit->compagnie_id,
                                            // 'remorque' => $produit->remorque,
                                            // 'created_at' => $produit->created_at,
                                            // 'updated_at' => $produit->updated_at,
                                        ];
                                    });
       
                                    $Coup_police=3000;
                                    $taxe_value=0.14;
                                    $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                    $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                    $encodedFilename = urlencode($compagnie->logo);
                                    $formattedPackage['compagnies'][] = [
                                        'id_compagnie' => $compagnie->id,
                                        'nom_complet' => $compagnie->nom_complet,
                                        'abr' => $compagnie->abr,
                                        'logo' => $compagnie->logo,
                                        'force_fiscale' => $request->force_fiscale,
                                        'energie' => $produit->energie,
                                        'cat_vehicule' => $produit->cat_vehicule,
                                        'type_carrosserie' => $request->type_carrosserie,
                                        'prime_net'=>$primeTotal,
                                        'coup_police' => $Coup_police,
                                        'taxe'=> $taxe,
                                        'fga' => $fgaTotal,
                                        'prime_ttc' =>  $prime_ttc,
                                        'duree' => $duree,
                                        'contact' => $request->contact,
                                        'nombre_place' =>  $request->nombre_place,
                                        'valeur_venale' =>  $request->valeur_venale,
                                        'date_first_circ' =>  $request->date_first_circ,
                                        'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                        'logo_base64' => route('image.display', ['filename' => $encodedFilename]),
                                        'garanties' => $garantiespack,
                                       
                                        // 'produits' => $formattedProduits->toArray(),
                                    ];
                                }
                            }
                            else{
                                if($duree < 3){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez selectionner une durée supérieure à 3 mois pour voir ce package';
                                }else if($request->valeur_neuf ==NULL   ||  $request->valeur_neuf === 0  || $request->valeur_neuf === 'NaN'){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur  neuve de votre véhicule  pour  voir ce package' ;
                                }else if($request->date_first_circ !== NULL ){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur neuve de votre véhicule  pour  voir ce package' ;
                                }else if($request->valeur_venale < 1000000){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'La valeur actuelle de votre véhicule doit être supérieure à 1 000 000 FCFA' ;
                                }else if($request->date_first_circ === NULL){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez renseigner la date de 1ere mise en circulation de votre véhicule  pour  voir ce package' ;
                                }
                            }
                        }
                        else if($request->valeur_venale === NULL   ||  $request->valeur_venale == 0  || $request->valeur_venale === 'NaN' || $request->valeur_venale < 1000000  ){
                            $garantiesFiltrees = $produits->filter(function ($produit) {
                                $garantieNomCourt = $produit->garantie->nom_court;
                                // Garder les garanties spécifiques
                                return in_array($garantieNomCourt, ['rc', 'dr', 'sr','bdg', 'ar',]);
                            });
                           
                            // Regrouper les produits par compagnie
                            $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                            // Calcul du FGA pour chaque produit de la compagnie
                            if (
                                // Au moins une des garanties spécifiques et aucune autre garantie
                                ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                                // Ou au moins les trois garanties spécifiques
                                ($garantiesFiltrees->count() === 5) ||
                                // Nouvelle condition pour le nombre de garanties acceptables par package
                                $produitsParCompagnie->filter(function ($produitsCompagnie) {
                                    // Compter le nombre de garanties uniques dans cette compagnie
                                    $garantiesUniques = $produitsCompagnie->pluck('garantie.nom_court')->unique();
                                    $nombreGaranties = $garantiesUniques->count();
                           
                                    // Vérifier si le nombre de garanties est entre 3 et 5 inclusivement
                                    return $nombreGaranties >= 3 && $nombreGaranties <= 5 ;
                                })->isNotEmpty() // Au moins un package avec le bon nombre de garanties
                            ){
                                foreach ($produitsParCompagnie as $compagnieId => $produits) {
                               
                                    $compagnie = $produits->first()->compagnie;
                                    $fgaTotal = 0;
                                    $primeTotal = 0;
                                    $garantiespack = [];
                                   
                                    foreach ($produits as $produit) {
                                        // Vérification de la garantie 'rc'
                                       
                                        switch ($produit->garantie->nom_court) {
                                            case 'rc':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                    $fgaPercent = 2.5;
                                                    $produit->prime -= 0.2 * $produit->prime;
                                                    $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                    $produit->fga = round($fgaAmount * $Duree);
                                                    $produit->prime = round($produit->prime* $Duree);
                                                    $fgaTotal = $produit->fga;
                                                    $primeTotal = $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'dr':
                                                if($produit->cat_vehicule == $request->cat_vehicule){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'sr':
                                                if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                    $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'ar':
                                                if($produit->cat_vehicule == $request->cat_vehicule ){
                                                    $capitale_assure=500000;    
                                                    $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'bdg':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;  
                                       
                                        }
       
                                    }
                           
                               
                                    $formattedProduits = $produits->map(function ($produit) {        
                                        return [
                                            'id_produit' => $produit->id_produit,
                                            'id_garantie' => $produit->id_garantie,
                                            'force_fiscale' => $produit->force_fiscale,
                                            'energie' => $produit->energie,
                                            'cat_vehicule' => $produit->cat_vehicule,
                                            'type_carrosserie' => $produit->type_carrosserie,
                                            'prime' => $produit->prime,                      
                                            // 'Duree' => $Duree,
                                            'compagnie_id' => $produit->compagnie_id,
                                            // 'remorque' => $produit->remorque,
                                            // 'created_at' => $produit->created_at,
                                            // 'updated_at' => $produit->updated_at,
                                        ];
                                    });
       
                                    $Coup_police=3000;
                                    $taxe_value=0.14;
                                    $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                    $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                    $encodedFilename = urlencode($compagnie->logo);
                                    $formattedPackage['compagnies'][] = [
                                        'id_compagnie' => $compagnie->id,
                                        'nom_complet' => $compagnie->nom_complet,
                                        'abr' => $compagnie->abr,
                                        'logo' => $compagnie->logo,
                                        'force_fiscale' => $request->force_fiscale,
                                        'energie' => $produit->energie,
                                        'cat_vehicule' => $produit->cat_vehicule,
                                        'type_carrosserie' => $request->type_carrosserie,
                                        'prime_net'=>$primeTotal,
                                        'coup_police' => $Coup_police,
                                        'taxe'=> $taxe,
                                        'fga' => $fgaTotal,
                                        'prime_ttc' =>  $prime_ttc,
                                        'duree' => $duree,
                                        'contact' => $request->contact,
                                        'nombre_place' =>  $request->nombre_place,
                                        'valeur_venale' =>  $request->valeur_venale,
                                        'date_first_circ' =>  $request->date_first_circ,
                                        'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                        'logo_base64' => route('image.display', ['filename' => $encodedFilename]),
                                        'garanties' => $garantiespack,
                                       
                                        // 'produits' => $formattedProduits->toArray(),
                                    ];
                                }
                            }
                            else{
                                if($duree < 3){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez selectionner une durée supérieure à 3 mois pour voir ce package';
                                }else if($request->valeur_venale === NULL   ||  $request->valeur_venale == 0  || $request->valeur_venale === 'NaN' || $request->valeur_venale < 1000000 ){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur actuelle et superieur a 1 000 0000 de votre véhicule  pour  voir ce package' ;
                                }else if($request->date_first_circ !== NULL ){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur neuve de votre véhicule  pour  voir ce package' ;
                                }else if($request->date_first_circ === NULL){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez renseigner la date de 1ere mise en circulation de votre véhicule  pour  voir ce package' ;
                                }
                            }
                        }
                    }
                    foreach ($package->produits->groupBy('garantie.id_garantie') as $garantieId => $produits) {
                        $garantie = $produits->first()->garantie;
               
                        $formattedGarantie = [
                            'id_garantie' => $garantie->id_garantie,
                            // 'id_entreprise' => $garantie->id_entreprise,
                            'nom_garantie' => $garantie->nom_garantie,
                            // 'description' => $garantie->desccription ,                                
                            // 'nom_court' => $garantie->nom_court,
                            // 'created_at' => $garantie->created_at,
                            // 'updated_at' => $garantie->updated_at,
                            // 'deleted_at' => $garantie->deleted_at,
                        ];
               
                        // Ajoutez les données de garantie au tableau garanties
                        $formattedPackage['garanties'][] = $formattedGarantie;
                    }
                    $formattedPackages[] = $formattedPackage;
   
                }
            }
            else{
                if($request->valeur_venale >= 1000000 && $request->valeur_venale !==NULL  &&  $request->valeur_venale !=0  && $request->valeur_venale !== 'NaN' && $duree>=3 && ($request->valeur_neuf !==NULL  &&  $request->valeur_neuf !=0  && $request->valeur_neuf !== 'NaN') ){
                    // echo'ok';
                    $dateFirstCirc = $request->date_first_circ;
                    $dateParts = explode('/', $dateFirstCirc);
                    $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
                    $dateService = new DateTime($formattedDate);
                    // dd($dateService);
                    // Créer un objet DateTime pour la date actuelle
                    $aujourdHui = new DateTime();
                    // dd($aujourdHui);
                    // Calculer la différence en années
                    $ageVehicule = $aujourdHui->diff($dateService)->y;
                    if($ageVehicule <= 3){
                        foreach ($packages as $package) {  
                            // $encodedFilename = urlencode($package->logo_pack);
                            // Générer l'URL avec le nom de fichier encodé
                            $encodedFilename = urlencode($package->logo_pack);
                            $formattedPackage = [
                                // 'id_pack' => $package->id_pack,
                                // 'id_entreprise' => $package->id_entreprise,
                                'nom' => $package->nom,
                                'nom_court' => $package->nom_court,
                                'description' => $package->description,
                                // "logo_pack" => $package->logo_pack,
                                "logo_pack_base64" => route('image.display', ['filename' => $encodedFilename]),
                                // 'created_at' => $package->created_at,
                                // 'updated_at' => $package->updated_at,
                                // 'deleted_at' => $package->deleted_at,
                                'validpack' => TRUE,
                                'compagnies' => [],
                                'garanties' => [], // Ajoutez un tableau pour les garanties
                            ];          
                            // Regrouper les produits par compagnie
                            $produitsParCompagnie = $package->produits->groupBy('compagnie_id');        
                            foreach ($produitsParCompagnie as $compagnieId => $produits) {
                                $compagnie = $produits->first()->compagnie;
                                $fgaTotal = 0;
                                $primeTotal = 0;
                                $garantiespack = [];
                                // Calcul du FGA pour chaque produit de la compagnie
                                foreach ($produits as $produit) {
                                    // Vérification de la garantie 'rc'
                                    switch ($produit->garantie->nom_court) {
                                        case 'rc':
                                            if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                $fgaPercent = 2.5;
                                                $produit->prime -= 0.2 * $produit->prime;
                                                $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                $produit->fga = round($fgaAmount * $Duree);
                                                $produit->prime = round($produit->prime* $Duree);
                                                $fgaTotal = $produit->fga;
                                                $primeTotal = $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'dr':
                                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                                $produit->prime = round($produit->prime * $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'ar':
                                            if($produit->cat_vehicule == $request->cat_vehicule ){
                                                $capitale_assure=500000;    
                                                $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'bdg':
                                            if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                                $produit->prime = round($produit->prime * $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;                        
                                        case 'sr':
                                            if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'icd':
                                            if($produit->cat_vehicule == $request->cat_vehicule ){    
                                                $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        case 'vol':
                                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                                $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                        // case 'tcol':
                                        //     if($produit->cat_vehicule == $request->cat_vehicule && $Duree > 3){    
                                        //         $produit->prime = round((($produit->prime)/100 *$request->valeur_neuf)  * $Duree);
                                        //         $primeTotal += $produit->prime;
                                        //         $nom_garantie=$produit->garantie->nom_garantie;
                                        //         $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                        //     }
                                        // break;
                                        case 'tcmp':
                                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                                $produit->prime = round((($produit->prime)/100 *$request->valeur_neuf) * $Duree);
                                                $primeTotal += $produit->prime;
                                                $nom_garantie=$produit->garantie->nom_garantie;
                                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                            }
                                        break;
                                    }
           
                                }
                       
                                $formattedProduits = $produits->map(function ($produit) {        
                                    return [
                                        'id_produit' => $produit->id_produit,
                                        'id_garantie' => $produit->id_garantie,
                                        'force_fiscale' => $produit->force_fiscale,
                                        'energie' => $produit->energie,
                                        'cat_vehicule' => $produit->cat_vehicule,
                                        'type_carrosserie' => $produit->type_carrosserie,
                                        'prime' => $produit->prime,                      
                                        // 'Duree' => $Duree,
                                        'compagnie_id' => $produit->compagnie_id,
                                        // 'remorque' => $produit->remorque,
                                        // 'created_at' => $produit->created_at,
                                        // 'updated_at' => $produit->updated_at,
                                    ];
                                });
                                    if($package->nom_court == 'PACK GOLD') {
                                        $Coup_police = 10000;
                                    }else{
                                        $Coup_police = 3000;
                                    }
                                //  $Coup_police=3000;
                                $taxe_value=0.14;
                                $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                $encodedFilenamecomp1 = urlencode($compagnie->logo);
           
                                $formattedPackage['compagnies'][] = [
                                    'id_compagnie' => $compagnie->id,
                                    'nom_complet' => $compagnie->nom_complet,
                                    'abr' => $compagnie->abr,
                                    'logo' => $compagnie->logo,
                                    'force_fiscale' => $request->force_fiscale,
                                    'energie' => $produit->energie,
                                    'cat_vehicule' => $produit->cat_vehicule,
                                    'type_carrosserie' => $request->type_carrosserie,
                                    'prime_net'=>$primeTotal,
                                    'coup_police' => $Coup_police,
                                    'taxe'=> $taxe,
                                    'fga' => $fgaTotal,
                                    'prime_ttc' =>  $prime_ttc,
                                    'duree' => $duree,
                                    'contact' => $request->contact,
                                    'nombre_place' =>  $request->nombre_place,
                                    'valeur_venale' =>  $request->valeur_venale ,
                                    'date_first_circ' =>  $request->date_first_circ ,
                                    'valeur_neuf' =>  $request->valeur_neuf?: null,
                                    'logo_base64' => route('image.display', ['filename' => $encodedFilenamecomp1]),
                                    'garanties' => $garantiespack,
                                    // 'produits' => $formattedProduits->toArray(),
                                ];
                            }
           
                            foreach ($package->produits->groupBy('garantie.id_garantie') as $garantieId => $produits) {
                                $garantie = $produits->first()->garantie;
                       
                                $formattedGarantie = [
                                    'id_garantie' => $garantie->id_garantie,
                                    // 'id_entreprise' => $garantie->id_entreprise,
                                    'nom_garantie' => $garantie->nom_garantie,
                                    // 'description' => $garantie->desccription ,                                
                                    // 'nom_court' => $garantie->nom_court,
                                    // 'created_at' => $garantie->created_at,
                                    // 'updated_at' => $garantie->updated_at,
                                    // 'deleted_at' => $garantie->deleted_at,
                                ];
                       
                                // Ajoutez les données de garantie au tableau garanties
                                $formattedPackage['garanties'][] = $formattedGarantie;
                            }
                            $formattedPackages[] = $formattedPackage;
                        }
                    }
                    else{
                        foreach ($packages as $package) {
                            $produits = $package->produits;
                            // Filtrer les produits pour garder seulement les garanties spécifiques ("rc", "dr", "sr")
                            // $garantiesFiltrees = $produits->filter(function ($produit) {
                            //     $garantieNomCourt = $produit->garantie->nom_court;
                            //     // Garder les garanties spécifiques
                            //     return in_array($garantieNomCourt, ['rc', 'dr', 'sr']);
                            // });
                       
                            $encodedFilename = urlencode($package->logo_pack);
                            $formattedPackage = [
                                // 'id_pack' => $package->id_pack,
                                // 'id_entreprise' => $package->id_entreprise,
                                'nom' => $package->nom,
                                'nom_court' => $package->nom_court,
                                'description' => $package->description,
                                // "logo_pack" => $package->logo_pack,
                                "logo_pack_base64" => route('image.display', ['filename' => $encodedFilename]),
                                // 'created_at' => $package->created_at,
                                // 'updated_at' => $package->updated_at,
                                // 'deleted_at' => $package->deleted_at,
                                'compagnies' => [],
                                'validpack' => TRUE,
                                'garanties' => [] // Ajoutez un tableau pour les garanties
                            ];          
                            if($ageVehicule > 3){
                                $garantiesFiltrees = $produits->filter(function ($produit) {
                                    $garantieNomCourt = $produit->garantie->nom_court;
                                    // Garder les garanties spécifiques
                                    return in_array($garantieNomCourt, ['rc', 'dr', 'sr','bdg', 'ar','vol','icd']);
                                });
                                // Regrouper les produits par compagnie
                                $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                                // Calcul du FGA pour chaque produit de la compagnie
                                if (
                                    // Au moins une des garanties spécifiques et aucune autre garantie
                                    ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                                    // Ou au moins les trois garanties spécifiques
                                    ($garantiesFiltrees->count() === 7)
                                ) {
                                    foreach ($produitsParCompagnie as $compagnieId => $produits) {                        
                                        $compagnie = $produits->first()->compagnie;
                                        $fgaTotal = 0;
                                        $primeTotal = 0;
                                        $garantiespack = [];
                                        foreach ($produits as $produit) {
                                            // Vérification de la garantie 'rc'
                                            switch ($produit->garantie->nom_court) {
                                            case 'rc':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                    $fgaPercent = 2.5;
                                                    $produit->prime -= 0.2 * $produit->prime;
                                                    $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                    $produit->fga = round($fgaAmount * $Duree);
                                                    $produit->prime = round($produit->prime* $Duree);
                                                    $fgaTotal = $produit->fga;
                                                    $primeTotal = $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'dr':
                                                if($produit->cat_vehicule == $request->cat_vehicule){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'ar':
                                                if($produit->cat_vehicule == $request->cat_vehicule ){
                                                    $capitale_assure=500000;    
                                                    $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'bdg':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;                        
                                            case 'sr':
                                                if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                    $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'icd':
                                                if($produit->cat_vehicule == $request->cat_vehicule ){    
                                                    $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'vol':
                                                if($produit->cat_vehicule == $request->cat_vehicule){    
                                                    $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            }
           
                                        }
                               
                                   
                                        $formattedProduits = $produits->map(function ($produit) {        
                                            return [
                                                'id_produit' => $produit->id_produit,
                                                'id_garantie' => $produit->id_garantie,
                                                'force_fiscale' => $produit->force_fiscale,
                                                'energie' => $produit->energie,
                                                'cat_vehicule' => $produit->cat_vehicule,
                                                'type_carrosserie' => $produit->type_carrosserie,
                                                'prime' => $produit->prime,                      
                                                // 'Duree' => $Duree,
                                                'compagnie_id' => $produit->compagnie_id,
                                                // 'remorque' => $produit->remorque,
                                                // 'created_at' => $produit->created_at,
                                                // 'updated_at' => $produit->updated_at,
                                            ];
                                        });
           
                                        $Coup_police=3000;
                                        $taxe_value=0.14;
                                        $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                        $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                        $encodedFilenamecomp2 = urlencode($compagnie->logo);
                                        $formattedPackage['compagnies'][] = [
                                            'id_compagnie' => $compagnie->id,
                                            'nom_complet' => $compagnie->nom_complet,
                                            'abr' => $compagnie->abr,
                                            'logo' => $compagnie->logo,
                                            'force_fiscale' => $request->force_fiscale,
                                            'energie' => $produit->energie,
                                            'cat_vehicule' => $produit->cat_vehicule,
                                            'type_carrosserie' => $request->type_carrosserie,
                                            'prime_net'=>$primeTotal,
                                            'coup_police' => 3000,
                                            'taxe'=> $taxe,
                                            'fga' => $fgaTotal,
                                            'prime_ttc' =>  $prime_ttc,
                                            'duree' => $duree,
                                            'contact' => $request->contact,
                                            'nombre_place' =>  $request->nombre_place,
                                            'valeur_venale' =>  $request->valeur_venale,
                                            'date_first_circ' =>  $request->date_first_circ,
                                            'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                            'logo_base64' => route('image.display', ['filename' => $encodedFilenamecomp2]),
                                            'garanties' => $garantiespack,
                                           
                                            // 'produits' => $formattedProduits->toArray(),
                                        ];
                                    }
                                }
                                else{
                                    if($ageVehicule > 3){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = "L'âge de votre véhicule doit être de 3 ans ou moin" ;
                                    }
                                    else if($request->valeur_venale !== NULL || $request->valeur_venale !== NAN){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur actuelle de votre véhicule  pour  voir ce package' ;
                                    }else if($request->valeur_neuf !== NULL || $request->valeur_valeur_neuf !== ""){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur neuve de votre véhicule  pour  voir ce package' ;
                                    }
                                    else if($request->valeur_venale < 1000000){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'La valeur actuelle de votre véhicule doit être supérieure à 1 000 000 FCFA' ;
                                    }
                                }
                            }
                           
                            foreach ($package->produits->groupBy('garantie.id_garantie') as $garantieId => $produits) {
                                $garantie = $produits->first()->garantie;
                       
                                $formattedGarantie = [
                                    'id_garantie' => $garantie->id_garantie,
                                    // 'id_entreprise' => $garantie->id_entreprise,
                                    'nom_garantie' => $garantie->nom_garantie,
                                    // 'description' => $garantie->desccription ,                                
                                    // 'nom_court' => $garantie->nom_court,
                                    // 'created_at' => $garantie->created_at,
                                    // 'updated_at' => $garantie->updated_at,
                                    // 'deleted_at' => $garantie->deleted_at,
                                ];
                       
                                // Ajoutez les données de garantie au tableau garanties
                                $formattedPackage['garanties'][] = $formattedGarantie;
                            }
                            $formattedPackages[] = $formattedPackage;
           
                        }
                    }
                }
                else{
                    foreach ($packages as $package) {
                        $produits = $package->produits;
                        // Filtrer les produits pour garder seulement les garanties spécifiques ("rc", "dr", "sr")
                        // $garantiesFiltrees = $produits->filter(function ($produit) {
                        //     $garantieNomCourt = $produit->garantie->nom_court;
                        //     // Garder les garanties spécifiques
                        //     return in_array($garantieNomCourt, ['rc', 'dr', 'sr']);
                        // });
                   
                        $encodedFilename = urlencode($package->logo_pack);
                        $formattedPackage = [
                            // 'id_pack' => $package->id_pack,
                            // 'id_entreprise' => $package->id_entreprise,
                            'nom' => $package->nom,
                            'nom_court' => $package->nom_court,
                            'description' => $package->description,
                            // "logo_pack" => $package->logo_pack,
                            "logo_pack_base64" => route('image.display', ['filename' => $encodedFilename]),
                            // 'created_at' => $package->created_at,
                            // 'updated_at' => $package->updated_at,
                            // 'deleted_at' => $package->deleted_at,
                            'compagnies' => [],
                            'validpack' => TRUE,
                            'garanties' => [] // Ajoutez un tableau pour les garanties
                        ];          
                        if($duree < 3 ){
                            $garantiesFiltrees = $produits->filter(function ($produit) {
                                $garantieNomCourt = $produit->garantie->nom_court;
                                // Garder les garanties spécifiques
                                return in_array($garantieNomCourt, ['rc', 'dr', 'sr']);
                            });
                            // Regrouper les produits par compagnie
                            $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                            // Calcul du FGA pour chaque produit de la compagnie
                            if (
                                // Au moins une des garanties spécifiques et aucune autre garantie
                                ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                                // Ou au moins les trois garanties spécifiques
                                ($garantiesFiltrees->count() === 3)
                            ) {
                                foreach ($produitsParCompagnie as $compagnieId => $produits) {                        
                                    $compagnie = $produits->first()->compagnie;
                                    $fgaTotal = 0;
                                    $primeTotal = 0;
                                    $garantiespack = [];
                                    foreach ($produits as $produit) {
                                        // Vérification de la garantie 'rc'
                                        switch ($produit->garantie->nom_court) {
                                            case 'rc':
                                                if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                    $fgaPercent = 2.5;
                                                    $produit->prime -= 0.2 * $produit->prime;
                                                    $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                    $produit->fga = round($fgaAmount * $Duree);
                                                    $produit->prime = round($produit->prime* $Duree);
                                                    $fgaTotal = $produit->fga;
                                                    $primeTotal = $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'dr':
                                                if($produit->cat_vehicule == $request->cat_vehicule){    
                                                    $produit->prime = round($produit->prime * $Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                            case 'sr':
                                                if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                    $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                    $primeTotal += $produit->prime;
                                                    $nom_garantie=$produit->garantie->nom_garantie;
                                                    $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                }
                                            break;
                                        }
       
                                    }
                           
                               
                                    $formattedProduits = $produits->map(function ($produit) {        
                                        return [
                                            'id_produit' => $produit->id_produit,
                                            'id_garantie' => $produit->id_garantie,
                                            'force_fiscale' => $produit->force_fiscale,
                                            'energie' => $produit->energie,
                                            'cat_vehicule' => $produit->cat_vehicule,
                                            'type_carrosserie' => $produit->type_carrosserie,
                                            'prime' => $produit->prime,                      
                                            // 'Duree' => $Duree,
                                            'compagnie_id' => $produit->compagnie_id,
                                            // 'remorque' => $produit->remorque,
                                            // 'created_at' => $produit->created_at,
                                            // 'updated_at' => $produit->updated_at,
                                        ];
                                    });
       
                                    $Coup_police=3000;
                                    $taxe_value=0.14;
                                    $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                    $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                    $encodedFilenamecomp2 = urlencode($compagnie->logo);
                                    $formattedPackage['compagnies'][] = [
                                        'id_compagnie' => $compagnie->id,
                                        'nom_complet' => $compagnie->nom_complet,
                                        'abr' => $compagnie->abr,
                                        'logo' => $compagnie->logo,
                                        'force_fiscale' => $request->force_fiscale,
                                        'energie' => $produit->energie,
                                        'cat_vehicule' => $produit->cat_vehicule,
                                        'type_carrosserie' => $request->type_carrosserie,
                                        'prime_net'=>$primeTotal,
                                        'coup_police' => $Coup_police,
                                        'taxe'=> $taxe,
                                        'fga' => $fgaTotal,
                                        'prime_ttc' =>  $prime_ttc,
                                        'duree' => $duree,
                                        'contact' => $request->contact,
                                        'nombre_place' =>  $request->nombre_place,
                                        'valeur_venale' =>  $request->valeur_venale,
                                        'date_first_circ' =>  $request->date_first_circ,
                                        'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                        'logo_base64' => route('image.display', ['filename' => $encodedFilenamecomp2]),
                                        'garanties' => $garantiespack,
                                       
                                        // 'produits' => $formattedProduits->toArray(),
                                    ];
                                }
                            }
                            else{
                                if($duree < 3){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'Veuillez selectionner une durée supérieure à 3 mois pour voir ce package';
                                }else if($request->valeur_venale === NULL || $request->valeur_venale == 'NaN' || $request->valeur_venale ==0){
                                $formattedPackage['compagnies'] = NULL;
                                $formattedPackage['validpack'] = FALSE;
                                $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur actuelle de votre véhicule  pour  voir ce package' ;
                            }
                            else if($request->valeur_venale < 1000000){
                                    $formattedPackage['compagnies'] = NULL;
                                    $formattedPackage['validpack'] = FALSE;
                                    $formattedPackage['errormessage'] = 'La valeur actuelle de votre véhicule doit être supérieure à 1 000 000 FCFA' ;
                                }
                            }
                        }
                        else if($duree  >= 3  ){
                            if(($request->valeur_venale !==NULL &&   $request->valeur_venale !=0  && $request->valeur_venale !== 'NaN' && $request->valeur_venale >=1000000  )){
                                $garantiesFiltrees = $produits->filter(function ($produit) {
                                    $garantieNomCourt = $produit->garantie->nom_court;
                                    // Garder les garanties spécifiques
                                    return in_array($garantieNomCourt, ['rc', 'dr', 'sr','bdg', 'ar','icd','vol']);
                                });
                               
                                // Regrouper les produits par compagnie
                                $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                                // Calcul du FGA pour chaque produit de la compagnie
                                if (
                                    // Au moins une des garanties spécifiques et aucune autre garantie
                                    ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                                    // Ou au moins les trois garanties spécifiques
                                    ($garantiesFiltrees->count() === 7) ||
                                    // Nouvelle condition pour le nombre de garanties acceptables par package
                                    $produitsParCompagnie->filter(function ($produitsCompagnie) {
                                        // Compter le nombre de garanties uniques dans cette compagnie
                                        $garantiesUniques = $produitsCompagnie->pluck('garantie.nom_court')->unique();
                                        $nombreGaranties = $garantiesUniques->count();
                               
                                        // Vérifier si le nombre de garanties est entre 3 et 5 inclusivement
                                        return $nombreGaranties >= 3 && $nombreGaranties <= 7 ;
                                    })->isNotEmpty() // Au moins un package avec le bon nombre de garanties
                                ){
                                    foreach ($produitsParCompagnie as $compagnieId => $produits) {
                                   
                                        $compagnie = $produits->first()->compagnie;
                                        $fgaTotal = 0;
                                        $primeTotal = 0;
                                        $garantiespack = [];
                                       
                                        foreach ($produits as $produit) {
                                            // Vérification de la garantie 'rc'
                                           
                                            switch ($produit->garantie->nom_court) {
                                                case 'rc':
                                                    if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                        $fgaPercent = 2.5;
                                                        $produit->prime -= 0.2 * $produit->prime;
                                                        $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                        $produit->fga = round($fgaAmount * $Duree);
                                                        $produit->prime = round($produit->prime* $Duree);
                                                        $fgaTotal = $produit->fga;
                                                        $primeTotal = $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'dr':
                                                    if($produit->cat_vehicule == $request->cat_vehicule){    
                                                        $produit->prime = round($produit->prime * $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'sr':
                                                    if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                        $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'ar':
                                                    if($produit->cat_vehicule == $request->cat_vehicule ){
                                                        $capitale_assure=500000;    
                                                        $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'bdg':
                                                    if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                                        $produit->prime = round($produit->prime * $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;  
                                                case 'icd':
                                                    if($produit->cat_vehicule == $request->cat_vehicule ){    
                                                        $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'vol':
                                                    if($produit->cat_vehicule == $request->cat_vehicule){    
                                                        $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                            }
           
                                        }
                               
                                   
                                        $formattedProduits = $produits->map(function ($produit) {        
                                            return [
                                                'id_produit' => $produit->id_produit,
                                                'id_garantie' => $produit->id_garantie,
                                                'force_fiscale' => $produit->force_fiscale,
                                                'energie' => $produit->energie,
                                                'cat_vehicule' => $produit->cat_vehicule,
                                                'type_carrosserie' => $produit->type_carrosserie,
                                                'prime' => $produit->prime,                      
                                                // 'Duree' => $Duree,
                                                'compagnie_id' => $produit->compagnie_id,
                                                // 'remorque' => $produit->remorque,
                                                // 'created_at' => $produit->created_at,
                                                // 'updated_at' => $produit->updated_at,
                                            ];
                                        });
           
                                        $Coup_police=3000;
                                        $taxe_value=0.14;
                                        $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                        $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                        $encodedFilename = urlencode($compagnie->logo);
                                        $formattedPackage['compagnies'][] = [
                                            'id_compagnie' => $compagnie->id,
                                            'nom_complet' => $compagnie->nom_complet,
                                            'abr' => $compagnie->abr,
                                            'logo' => $compagnie->logo,
                                            'force_fiscale' => $request->force_fiscale,
                                            'energie' => $produit->energie,
                                            'cat_vehicule' => $produit->cat_vehicule,
                                            'type_carrosserie' => $request->type_carrosserie,
                                            'prime_net'=>$primeTotal,
                                            'coup_police' => 3000,
                                            'taxe'=> $taxe,
                                            'fga' => $fgaTotal,
                                            'prime_ttc' =>  $prime_ttc,
                                            'duree' => $duree,
                                            'contact' => $request->contact,
                                            'nombre_place' =>  $request->nombre_place,
                                            'valeur_venale' =>  $request->valeur_venale,
                                            'date_first_circ' =>  $request->date_first_circ,
                                            'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                            'logo_base64' => route('image.display', ['filename' => $encodedFilename]),
                                            'garanties' => $garantiespack,
                                           
                                            // 'produits' => $formattedProduits->toArray(),
                                        ];
                                    }
                                }
                                else{
                                    if($duree < 3){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez selectionner une durée supérieure à 3 mois pour voir ce package';
                                    }else if($request->valeur_neuf ==NULL   ||  $request->valeur_neuf ==0  || $request->valeur_neuf === 'NaN'){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur  neuve de votre véhicule  pour  voir ce package' ;
                                    }else if($request->date_first_circ !== NULL ){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur neuve de votre véhicule  pour  voir ce package' ;
                                    }
                                    else if($request->valeur_venale < 1000000){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'La valeur actuelle de votre véhicule doit être supérieure à 1 000 000 FCFA' ;
                                    }
                                }
                            }
                            else if($request->valeur_venale === NULL   ||  $request->valeur_venale ==0  || $request->valeur_venale === 'NaN' || $request->valeur_venale < 1000000  ){
                                $garantiesFiltrees = $produits->filter(function ($produit) {
                                    $garantieNomCourt = $produit->garantie->nom_court;
                                    // Garder les garanties spécifiques
                                    return in_array($garantieNomCourt, ['rc', 'dr', 'sr','bdg', 'ar',]);
                                });
                               
                                // Regrouper les produits par compagnie
                                $produitsParCompagnie = $package->produits->groupBy('compagnie_id');
                                // Calcul du FGA pour chaque produit de la compagnie
                                if (
                                    // Au moins une des garanties spécifiques et aucune autre garantie
                                    ($garantiesFiltrees->isNotEmpty() && $garantiesFiltrees->count() === $produits->count()) ||
                                    // Ou au moins les trois garanties spécifiques
                                    ($garantiesFiltrees->count() === 5) ||
                                    // Nouvelle condition pour le nombre de garanties acceptables par package
                                    $produitsParCompagnie->filter(function ($produitsCompagnie) {
                                        // Compter le nombre de garanties uniques dans cette compagnie
                                        $garantiesUniques = $produitsCompagnie->pluck('garantie.nom_court')->unique();
                                        $nombreGaranties = $garantiesUniques->count();
                               
                                        // Vérifier si le nombre de garanties est entre 3 et 5 inclusivement
                                        return $nombreGaranties >= 3 && $nombreGaranties <= 5 ;
                                    })->isNotEmpty() // Au moins un package avec le bon nombre de garanties
                                ){
                                    foreach ($produitsParCompagnie as $compagnieId => $produits) {
                                   
                                        $compagnie = $produits->first()->compagnie;
                                        $fgaTotal = 0;
                                        $primeTotal = 0;
                                        $garantiespack = [];
                                       
                                        foreach ($produits as $produit) {
                                            // Vérification de la garantie 'rc'
                                           
                                            switch ($produit->garantie->nom_court) {
                                                case 'rc':
                                                    if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == 'essence' ){          
                                                        $fgaPercent = 2.5;
                                                        $produit->prime -= 0.2 * $produit->prime;
                                                        $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                                        $produit->fga = round($fgaAmount * $Duree);
                                                        $produit->prime = round($produit->prime* $Duree);
                                                        $fgaTotal = $produit->fga;
                                                        $primeTotal = $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'dr':
                                                    if($produit->cat_vehicule == $request->cat_vehicule){    
                                                        $produit->prime = round($produit->prime * $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'sr':
                                                    if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                                        $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'ar':
                                                    if($produit->cat_vehicule == $request->cat_vehicule ){
                                                        $capitale_assure=500000;    
                                                        $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;
                                                case 'bdg':
                                                    if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                                        $produit->prime = round($produit->prime * $Duree);
                                                        $primeTotal += $produit->prime;
                                                        $nom_garantie=$produit->garantie->nom_garantie;
                                                        $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime)];
                                                    }
                                                break;  
                                           
                                            }
           
                                        }
                               
                                   
                                        $formattedProduits = $produits->map(function ($produit) {        
                                            return [
                                                'id_produit' => $produit->id_produit,
                                                'id_garantie' => $produit->id_garantie,
                                                'force_fiscale' => $produit->force_fiscale,
                                                'energie' => $produit->energie,
                                                'cat_vehicule' => $produit->cat_vehicule,
                                                'type_carrosserie' => $produit->type_carrosserie,
                                                'prime' => $produit->prime,                      
                                                // 'Duree' => $Duree,
                                                'compagnie_id' => $produit->compagnie_id,
                                                // 'remorque' => $produit->remorque,
                                                // 'created_at' => $produit->created_at,
                                                // 'updated_at' => $produit->updated_at,
                                            ];
                                        });
           
                                        $Coup_police=3000;
                                        $taxe_value=0.14;
                                        $taxe = round(($taxe_value*($primeTotal + $Coup_police)));
                                        $prime_ttc=round((($primeTotal)+$Coup_police)+($taxe_value*((($primeTotal)+$Coup_police))) + $fgaTotal);
                                        $encodedFilename = urlencode($compagnie->logo);
                                        $formattedPackage['compagnies'][] = [
                                            'id_compagnie' => $compagnie->id,
                                            'nom_complet' => $compagnie->nom_complet,
                                            'abr' => $compagnie->abr,
                                            'logo' => $compagnie->logo,
                                            'force_fiscale' => $request->force_fiscale,
                                            'energie' => $produit->energie,
                                            'cat_vehicule' => $produit->cat_vehicule,
                                            'type_carrosserie' => $request->type_carrosserie,
                                            'prime_net'=>$primeTotal,
                                            'coup_police' => 3000,
                                            'taxe'=> $taxe,
                                            'fga' => $fgaTotal,
                                            'prime_ttc' =>  $prime_ttc,
                                            'duree' => $duree,
                                            'contact' => $request->contact,
                                            'nombre_place' =>  $request->nombre_place,
                                            'valeur_venale' =>  $request->valeur_venale,
                                            'date_first_circ' =>  $request->date_first_circ,
                                            'valeur_neuf' =>  $request->valeur_neuf ?: null,
                                            'logo_base64' => route('image.display', ['filename' => $encodedFilename]),
                                            'garanties' => $garantiespack,
                                           
                                            // 'produits' => $formattedProduits->toArray(),
                                        ];
                                    }
                                }
                                else{
                                    if($duree < 3){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez selectionner une durée supérieure à 3 mois pour voir ce package';
                                    }
                                    // else if($request->valeur_venale < 1000000){
                                    //     $formattedPackage['compagnies'] = NULL;
                                    //     $formattedPackage['validpack'] = FALSE;
                                    //     $formattedPackage['errormessage'] = 'La valeur actuelle de votre véhicule doit être supérieure à 1 000 000 FCFA' ;
                                    // }
                                    else if($request->valeur_venale ===NULL   ||  $request->valeur_venale ==0  || $request->valeur_venale === 'NaN' || $request->valeur_venale < 1000000 ){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur actuelle et superieur a 1 000 0000 de votre véhicule  pour  voir ce package' ;
                                    }
                                    else if($request->date_first_circ !== NULL ){
                                        $formattedPackage['compagnies'] = NULL;
                                        $formattedPackage['validpack'] = FALSE;
                                        $formattedPackage['errormessage'] = 'Veuillez renseigner la valeur neuve de votre véhicule  pour  voir ce package' ;
                                    }
                                   
                                }
                            }
                           
                        }
                        foreach ($package->produits->groupBy('garantie.id_garantie') as $garantieId => $produits) {
                            $garantie = $produits->first()->garantie;
                   
                            $formattedGarantie = [
                                'id_garantie' => $garantie->id_garantie,
                                // 'id_entreprise' => $garantie->id_entreprise,
                                'nom_garantie' => $garantie->nom_garantie,
                                // 'description' => $garantie->desccription ,                                
                                // 'nom_court' => $garantie->nom_court,
                                // 'created_at' => $garantie->created_at,
                                // 'updated_at' => $garantie->updated_at,
                                // 'deleted_at' => $garantie->deleted_at,
                            ];
                   
                            // Ajoutez les données de garantie au tableau garanties
                            $formattedPackage['garanties'][] = $formattedGarantie;
                        }
                        $formattedPackages[] = $formattedPackage;
       
                    }
                }
            }
            $historique= new Historiques;
            $entreprise = Entreprise::find('1');
            $historique ->contact                = $request->contact ?: null;
            // $historique ->marque          = $request->marque ?: null;  
            $historique ->force_fiscale          = $request->force_fiscale ?: null;
            $historique ->energie                = $request->energie ?: null;
            $historique ->valeur_neuf            = $request->valeur_neuf ?: null ;
            $historique ->valeur_venale          = $request->valeur_venale ?: null ;
            $historique ->nombre_place           = $request->nombre_place ?: null ;
            $historique ->type_carrosserie       = $request->type_carrosserie ?: null;
            $historique ->duree                  = $request->duree?: null ;
            $historique->entreprise()->associate($entreprise);
            $historique->save();

        return response()->json(['packages' => $formattedPackages], 200);
    }

    function getImage($imageName){  
        $encodedFilename = urlencode($imageName);

        // Générer l'URL avec le nom de fichier encodé
        return  route('image.display', ['filename' => $encodedFilename]);
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

    public function createCliApi(Request $request) {
        
            $request->validate([
                'contact'                   => 'required',
                'name'                      => 'required',
                'garantie'                  => 'required',
                'force_fiscale'             => 'required',
                'energie'                   => 'required',
                'marque'                    => 'required',
                'model'                     => 'required',
                'vin'                       => 'required',
                // 'date_first_circ'           => 'required',
                'compagnie'                 => 'required',
                'prime_ttc'                 => 'required',
                'prime_net'                 => 'required',
                'taxe'                      => 'required',
                'fga'                       => 'required',
                'date_effet'                => 'required',
                // 'image_recto_filename'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
                // 'image_verso_filename'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'energie'                   => 'required'
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
            else if ($user && ($user->role == 'admin' || $user->role == 'super' || $user->role == 'staff') ) {
                $client->id_office = $user->id; // Assigner l'ID de l'utilisateur
            }
                $client->entreprise()->associate( $entreprise);
                $client->save();
            } 
            else{
                // Le client existe déjà, utilisons le client existant
                $client = $existingClient;
            }

            $generatedNumber = $this->generatePoliceNumber();
            $clients = Client::find($client->id);
            $compagnie = Compagnie::find($request->compagnie);

            $package= new Talons;    
            // Votre chaîne de données JSON
            $garanties_primes = $request->garantie;

            // Convertir la chaîne JSON en tableau PHP
            $dataArray = json_decode($garanties_primes, true);

            $package= new Talons;
            $duree = $request->duree;
            // Créer un objet DateTime pour la date du jour
            $dateDuJour = new DateTime();

            // Formater la date au format jour/mois/année
            $dateFormatee = $dateDuJour->format('d/m/Y à H:i:s');
            
            $package -> date_etablissement = $dateFormatee;

            // Supposons que $dateDuJour soit la date du jour obtenue comme mentionné précédemment
            $dateEffet = $request->date_effet;

            $DateEffet = DateTime::createFromFormat('d/m/Y', $request->date_effet); // Créer un objet DateTime à partir de la date d'effet
            // Supposons que $duree soit la durée en mois ou en années que vous souhaitez ajouter à la date du jour
            $dureeM = DB::table('duree')->where('nom', $duree)->value('nom');
        
            // Créez un nouvel objet DateInterval avec la durée spécifiée
            $interval = new DateInterval('P' . $dureeM . 'M'); // 'P' représente la période, 'M' représente les mois
        
            // Ajoutez l'intervalle à la date du jour pour obtenir la date d'échéance
            $dateEcheance = clone $DateEffet;
            // $dateEcheance = clone $dateDuJour; // Cloner la date du jour pour ne pas la modifier
            $dateEcheance->add($interval);
            // Soustrayez un jour à la date d'échéance
            $dateEcheance->sub(new DateInterval('P1D'));
            
            $dateEcheanceFormatee = $dateEcheance->format('d/m/Y');

            $package ->force_fiscale          = $request->force_fiscale ?: null;
            $package ->energie                = $request->energie ?: null;
            $package ->marque                 = $request->marque ?: null;
            $package ->model                  = $request->model ?: null;
            $package ->vin                    = $request->vin ?: null;
            $package ->date_first_circ        = $request->date_first_circ ?: null ;
            $package ->valeur_neuf            = $request->valeur_neuf ;
            $package ->valeur_venale          = $request->valeur_venale ;
            $package ->option_pers_tr         = $request->option_pers_tr ;
            $package ->nombre_place           = $request->nombre_place ?: null ;
            $package ->capitale_assure        = $request->capitale_assure ;
            $package ->type_carrosserie       = $request->type_carrosserie ?: null;
            $package ->prime_ttc              = $request->prime_ttc ?: null;
            $package ->prime_net              = $request->prime_net ?: null;
            $package ->taxe                   = $request->taxe ?: null;
            $package ->bonus_commercial       = $request->bonus_commercial;
            $package ->coup_police            = $request->coup_police ?: null;
            $package ->logo                   = $request->logo ?: null;
            $package ->fga                    = $request->fga ?: null;
            $package ->duree                  = $duree.' '.'mois';
            $package ->date_echeance          = $request->date_echeance ;
            $package ->n_attestation_jaune    = $request->n_attestation_jaune;
            $package ->n_attestation_cedeao   = $request->n_attestation_cedeao;
            $package ->n_police               = $generatedNumber;
            $package ->date_effet             = $dateEffet ;
            $package ->date_echeance          = $dateEcheanceFormatee;

            $image_recto = '';   
            $image_verso = '';   

            if ($request->hasFile('image_recto_filename') && $request->hasFile('image_verso_filename')) {
                $logo = $request->file('image_recto_filename');
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier

                $logo1 = $request->file('image_verso_filename');
                $logo_extension1 = $logo1->getClientOriginalExtension(); // Obtenir l'extension du fichier
            
                // Générer un nom de fichier unique
                $image_recto = 'carte_grise_recto'.'_'. str_replace(' ', '_',  $generatedNumber) . '.' . $logo_extension;
                // dd($image_recto);

                $image_verso = 'carte_grise_verso'.'_'. str_replace(' ', '_', $generatedNumber) .'.' . $logo_extension1;
                // dd($image_verso);

                // Définir le chemin du dossier basé sur l'ID du client
                $storage_path = storage_path('app/public/image/carte_grise/' . $client->id);

                // Vérifier si le dossier existe, sinon le créer
                if (!file_exists($storage_path)) {
                    mkdir($storage_path, 0755, true); // Créer le dossier avec des permissions appropriées
                }

                try {
                    // Déplacer les fichiers vers le dossier de stockage approprié
                    $logo->move($storage_path, $image_recto);
                    $logo1->move($storage_path, $image_verso);
                } 
                catch (FileException $e) {
                        // Gérer les erreurs de déplacement de fichier
                        return redirect()->back()->with('error', 'Photo non téléchargée');
                }
            
            }
            $package ->image_recto          = $image_recto;
            $package ->image_verso          = $image_verso;
            
            $package ->client()->associate($clients);
            $package ->compagnie()->associate($compagnie);
            $package->save(); 
            
            foreach($dataArray as $data){
                // dd($dataArray);
                $package->produits()->attach($data['id_garantie'],['prix' => $data['prix_garantie'],'garantie'=>$data['nom_garantie']]);
            }
            $client=$package->client()->get();
                // var_dump($package->id);exit;
            $invoiceUrl =   env('APP_URL'). '/'.'facture/'.$client[0]->id.'/'. $package->id_talon;

            // Retourner l'URL de la facture dans la réponse
            return response()->json(['message' => 'Facture générée avec succès', 'url_facture' =>$invoiceUrl ], 200);
        
    }

    public function pWave(Request $request) {
        $result = json_decode($request->result);
        $talon = Talons::where('statut', 0)
                    ->where('id_talon', $result->id_talon)
                    ->where('client_id', $result->client_id)
                    ->first();

        if ($talon) {
            $amount = $talon->prime_ttc;
            // dd($amount);
                $client_id=$result->client_id;
                $id_talon=$result->id_talon;
            // Paramètres de la requête vers l'API Wave
            $checkout_params = [
                "amount" => $amount ,
                // "amount" => 100 ,
                "currency" => "XOF",
                "error_url" => "https://example.com/error",
                // "success_url" => "https://comparateurtest.assurpro.sn/success_url_done_wave/".$client_id."/".$id_talon
                "success_url" => "https://comparateur.assurpro.sn/success_url_done_wave/".$client_id."/".$id_talon
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

    public function paiementOM(Request $request) {

        $accessToken = $this->getToken();

        $client_id  = $request->client_id;  
        $id_talon  = $request->id_talon;
        // dd( $id_talon);
        $talon = Talons::where('statut', 0)
                        ->where('id_talon', $id_talon)
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
                // "callbackSuccessUrl" => "https://comparateurtest.assurpro.sn/success_url_done_om/".$client_id."/".$id_talon,
                "callbackSuccessUrl" => "https://comparateur.assurpro.sn/success_url_done_om/".$client_id."/".$id_talon,
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

    public function successWave($client_id,$id_talon) {
        // Décrypter les paramètres client_id et id_talon
        $client_partenaire= Client::where('id', $client_id)
        ->first();

        $talon = Talons::where('statut',0)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
                // dd($talon);
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

            Mail::send('email.contact', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'marque' => $talon->marque,
                'model' => $talon->model,
                'vin' => $talon->vin,
                'dure' => $talon->duree,
                'effet' => $talon->date_effet,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                'echeance' => $talon->date_echeance,
                'police' => $talon->n_police,
                'prime' => $talon->prime_ttc,


                ], function($message){
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('NOUVELLE SOUSCRIPTION');
                    $message->to('contact@platineassurances.sn');
            });
           // Mettre à jour le statut du talon à 1
           $talon->update(['statut' => 1,'mode_paiement' => 1]);

            // Récupération des données de la requête
            $app_url = env('APP_URL');

            $content_sms = "Bonjour {$client->name},"."\n".
            "Nous vous remercions pour votre souscription auprès de Platine assurances!" ."\n".
            "Retrouver votre facture:{$app_url}/facture_client/{$talon->client_id}/{$talon->id_talon}" ."\n" .
            "Votre attestation vous sera livrée sous 24h ouvrées";
            $subject_sms='Comparateur Auto';

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
                return redirect(env('APP_URL').'/detail-talon/'.$client_id.'/'.$id_talon);  
                }
                else{
                // Le talon a été trouvé, vous pouvez maintenant accéder à ses propriétés
                return redirect(env('APP_URL').'/facture/'.$client_id.'/'.$id_talon);  
                }
            }
            else {
                // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
                // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
                return response()->json(['error' => 'Not found'], 404);
            }
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    public function successOm($client_id,$id_talon) {
        // Décrypter les paramètres client_id et id_talon
        $client_partenaire= Client::where('id', $client_id)
        ->first();

        $talon = Talons::where('statut',0)
                ->where('id_talon', $id_talon)
                ->where('client_id', $client_id)
                ->first();
                // dd($talon);
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

            Mail::send('email.contact', [
                'email' => $client->email,
                'nom' => $client->name,
                'civilite' => $client->civilite,
                'marque' => $talon->marque,
                'model' => $talon->model,
                'vin' => $talon->vin,
                'dure' => $talon->duree,
                'effet' => $talon->date_effet,
                'id_client' => $talon->client_id,
                'id_talon' => $talon->id_talon,
                'echeance' => $talon->date_echeance,
                'police' => $talon->n_police,
                'prime' => $talon->prime_ttc,


                ], function($message){
                    $message->from('noreply@platineassurances.sn');
                    $message->subject('NOUVELLE SOUSCRIPTION');
                    $message->to('contact@platineassurances.sn');
            });
           // Mettre à jour le statut du talon à 1
           $talon->update(['statut' => 1,'mode_paiement' => 2]);


            // Récupération des données de la requête
            $app_url = env('APP_URL');

            $content_sms = "<p>Bonjour {$client->name},</p>" .
            "<p>Nous vous remercions pour votre souscription auprès de Platine assurances!</p>" .
            "<p>Retrouver votre facture:{$app_url}/facture_client/{$talon->client_id}/{$talon->id_talon}</p>" .
            "<p>Votre attestation vous sera livrée sous 24h ouvrées</p>";
            $subject_sms='Comparateur Auto';

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
                return redirect(env('APP_URL').'/detail-talon/'.$client_id.'/'.$id_talon);  
                }
                else{
                // Le talon a été trouvé, vous pouvez maintenant accéder à ses propriétés
                return redirect(env('APP_URL').'/facture/'.$client_id.'/'.$id_talon);  
                }
            }
            else {
                // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
                // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
                return response()->json(['error' => 'Not found'], 404);
            }
        } else {
            // Le talon n'a pas été trouvé, vous pouvez gérer cette situation en conséquence
            // Par exemple, vous pouvez renvoyer une réponse d'erreur ou effectuer d'autres actions nécessaires.
            return response()->json(['error' => 'Not found'], 404);
        }

            
    }
    
    public function OCR(Request $request) {
        $client = new ClientGuzzle();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('public/cartes_grises');
        $filePath = storage_path('app/'.$path);
        try {
            $response = $client->request('POST', 'https://api.edenai.run/v2/ocr/ocr', [
                'multipart' => [
                    [
                        'name'     => 'providers',
                        'contents' => 'api4ai'
                    ],
                    [
                        'name'     => 'language',
                        'contents' => 'fr'
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen($filePath, 'r'),
                        // 'filename' => '7UgJQsVYlStpEVj0fl0O2nGNAaI8VOZaaXkTujrd.jpg'
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiMWFmN2JhNzAtZWJiMy00NWJhLWJhMWItOWYwNjlmZTcwN2UyIiwidHlwZSI6ImFwaV90b2tlbiJ9.NiuHRrIuU1uGrfPMwv0USyLBByGkJOdM2ePu5AXY1PE' // Remplacez VOTRE_CLE_API_ICI par votre clé API réelle
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            // Vérifie si la requête OCR a réussi
            if ($data['api4ai']['status'] == 'success') {
                $text = $data['api4ai']['text'];
                // dd($text);
                // Expression régulière pour extraire l'immatriculation
                $pattern_immatriculation = '/Immatriculation\n(.*?)\n/';

                // Expression régulière pour extraire la date de première mise en circulation
                $pattern_date = '/\b\d{2}\/\d{2}\/\d{4}\b/';

                // Variable pour stocker l'immatriculation
                $immatriculation = '';
                // Variable pour stocker la date
                $date = '';

                // Extrait l'immatriculation
                if (preg_match($pattern_immatriculation, $text, $matches_immatriculation)) {
                    $immatriculation = $matches_immatriculation[1];
                }

                // Extrait la date de première mise en circulation
                if (preg_match($pattern_date, $text, $matches_date)) {
                    $date = $matches_date[0];
                }

                // Vérifie si l'immatriculation et la date ont été extraites avec succès
                if (!empty($immatriculation) && !empty($date)) {
                    // Retourner les informations extraites
                    return response()->json(['message' => 'OCR générée avec succès', 'immatriculation' => $immatriculation, 'date' => $date], 200);
                } else {
                    // Si aucune correspondance trouvée
                    return response()->json(['message' => 'Aucune correspondance trouvée pour les informations d\'immatriculation et de date'], 404);
                }
            } else {
                // Si la requête OCR a échoué
                return response()->json(['message' => 'Erreur lors de la génération de l\'OCR', 'error' => $data['api4ai']['message']], 400);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            return response()->json(['message' => 'Erreur lors de la génération de l\'OCR', 'error' => $responseBody], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    
    }

    public function OCR1(Request $request) {
        $client = new ClientGuzzle();

        $request->validate([
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image1')->store('public/cartes_grises');
        $filePath = storage_path('app/'.$path);
        try {
            $response = $client->request('POST', 'https://api.edenai.run/v2/ocr/ocr', [
                'multipart' => [
                    [
                        'name'     => 'providers',
                        'contents' => 'api4ai'
                    ],
                    [
                        'name'     => 'language',
                        'contents' => 'fr'
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen($filePath, 'r'),
                        // 'filename' => '7UgJQsVYlStpEVj0fl0O2nGNAaI8VOZaaXkTujrd.jpg'
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiMWFmN2JhNzAtZWJiMy00NWJhLWJhMWItOWYwNjlmZTcwN2UyIiwidHlwZSI6ImFwaV90b2tlbiJ9.NiuHRrIuU1uGrfPMwv0USyLBByGkJOdM2ePu5AXY1PE' // Remplacez VOTRE_CLE_API_ICI par votre clé API réelle
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            // Vérifie si la requête OCR a réussi
            if ($data['api4ai']['status'] == 'success') {
                $text = $data['api4ai']['text'];
                dd($text);
                // Expression régulière pour extraire l'immatriculation
                $pattern_immatriculation = '/Immatriculation\n(.*?)\n/';

                // Expression régulière pour extraire la date de première mise en circulation
                $pattern_date = '/\b\d{2}\/\d{2}\/\d{4}\b/';

                // Variable pour stocker l'immatriculation
                $immatriculation = '';
                // Variable pour stocker la date
                $date = '';

                // Extrait l'immatriculation
                if (preg_match($pattern_immatriculation, $text, $matches_immatriculation)) {
                    $immatriculation = $matches_immatriculation[1];
                }

                // Extrait la date de première mise en circulation
                if (preg_match($pattern_date, $text, $matches_date)) {
                    $date = $matches_date[0];
                }

                // Vérifie si l'immatriculation et la date ont été extraites avec succès
                if (!empty($immatriculation) && !empty($date)) {
                    // Retourner les informations extraites
                    return response()->json(['message' => 'OCR générée avec succès', 'immatriculation' => $immatriculation, 'date' => $date], 200);
                } else {
                    // Si aucune correspondance trouvée
                    return response()->json(['message' => 'Aucune correspondance trouvée pour les informations d\'immatriculation et de date'], 404);
                }
            } else {
                // Si la requête OCR a échoué
                return response()->json(['message' => 'Erreur lors de la génération de l\'OCR', 'error' => $data['api4ai']['message']], 400);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            return response()->json(['message' => 'Erreur lors de la génération de l\'OCR', 'error' => $responseBody], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    
    }

    public function sendSms(Request $request){
        // Valider les paramètres d'entrée
        $request->validate([
            'subject' => 'required|string',
            'recipient' => 'required|string',
            'content' => 'required|string',
        ]);

        // Récupération des données de la requête
        $login = env('login_sms');
        $api_access_key = env('api_access_key_sms');
        $token = env('token_sms');
        $subject = urlencode($request->input('subject'));
        $signature = urlencode(env('signature_sms'));
        $recipient = $request->input('recipient');
        $content = urlencode($request->input('content'));

        // Générer le timestamp et le hash
        $timestamp = time();
        $msgToEncrypt = $token . $subject . $signature . $recipient . $content . $timestamp;
        $key = hash_hmac('sha1', $msgToEncrypt, $api_access_key);

        // Construire l'URL de la requête
        $uri = "https://api.orangesmspro.sn:8443/api?token={$token}&subject={$subject}&signature={$signature}&recipient={$recipient}&content={$content}&timestamp={$timestamp}&key={$key}";

        // Envoyer la requête HTTP GET avec les identifiants d'authentification
        $response = Http::withBasicAuth($login, $token)->get($uri);

        
        return response()->json([
        'status' => $response->status(),
        'message' => 'Message envoyer avec succé',       
         ]);
       
    }
}