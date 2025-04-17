<?php

namespace App\Http\Controllers;

use Storage;
use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Bareme;
use GuzzleHttp\Client;
use App\Models\Package;
use App\Models\Produit;
use App\Models\Garanties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexPackage(){
        $packages= Package::all()->where('deleted_at',NULL);

        foreach($packages as $pack){
            $red = rand(200, 255);
            $green = rand(200, 255);
            $blue = rand(200, 255);
            $color = "rgb($red, $green, $blue)";
            $pack->color=$color;
        }
        // Format the RGB values into a CSS color string
        $garanties=Garanties::all();
        $bareme= Bareme::all();
        // $allGaranties = Package::where('nom_court', 'pack_s')->pluck('garanties')->first();
        return view('package.index',compact('packages','bareme','garanties')); 
    }

    public function indexSimulateurPackage(){
        $packages= Package::all()->where('deleted_at',NULL);
        $garanties=Garanties::all();
        $bareme= Bareme::all();
        // $allGaranties = Package::where('nom_court', 'pack_s')->pluck('garanties')->first();
        return view('package.simulateur',compact('packages','bareme','garanties')); 
    }

    public function createPackage(Request $request) {

        try {
            
            $request->validate ([
                'nom'           => 'required',
                'nom_court'           => 'required',
                'logo_pack'           => 'required',
                'description'           => 'required'
            ]);  
            
           
         
            $id_entreprise = auth()->user()->entreprise_id;
            
            $package = new Package();  
            $package ->nom                   = $request->nom ;
            $package ->nom_court             = $request->nom_court;
            $package ->description           = $request->description;
    
            
            $pack_filename = 'LOGOPACKGOLD.jpg';   
            
            if ($request->hasFile('logo_pack')) {
                $logo = $request->file('logo_pack');
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier
            
                // Générer un nom de fichier unique
                $pack_filename = 'LOGO' . str_replace(' ', '', $request->nom_court) . '.' . $logo_extension;
                // var_dump($pack_filename);exit;
                try {
                    // Déplacer le fichier vers le dossier de stockage approprié
                    $logo->move(storage_path('app/public/image/logo_pack/'), $pack_filename);
                } catch (FileException $e) {
                    // Gérer les erreurs de déplacement de fichier
                    return redirect()->back()->with('error','photo non telecharger');
                }
            }
                $package ->logo_pack           = $pack_filename;
        
                $package ->id_entreprise         = $id_entreprise ;
                $package->save();    
    
            $id_pack = $package->id_pack;
    
            // Vous pouvez ensuite utiliser cet identifiant comme vous le souhaitez
            $packagec = Package::find($id_pack);
            for($i=0; $i <count($request->garanties);$i++){
                $produits= DB::table('products')->where('id_garantie','=',$request->garanties[$i])->get();
                foreach($produits as $pro){
                    $packagec->produits()->attach($pro->id_produit);
                }
            }
            return redirect()->back()->with('success', 'Package ajouté avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du package .');
        }

        
    }

    public function updatePackage(Request $request ,$id) {
        
        $packages = DB::table('packages')->where('id_pack','=',$id)->get();
        
        $garanties = DB::table('garanties')->get();
        $distinctGaranties = DB::table('produit_pack')
                                ->join('products', 'produit_pack.produit_id_produit', '=', 'products.id_produit')
                                ->join('packages', 'produit_pack.package_id_pack', '=', 'packages.id_pack')
                                ->join('garanties', 'products.id_garantie', '=', 'garanties.id_garantie')
                                ->select('products.id_garantie', 'garanties.*') // Include other columns from the garanties table if needed
                                ->distinct()
                                ->where('packages.id_pack', '=', $id)
                                ->get()
                                ->pluck('id_garantie')
                                ->toArray();
        // var_dump($distinctGaranties);exit;
        return view('package.edit_package',compact(['packages','garanties','distinctGaranties']));
       
    }

    public function updateStatus(Request $request, $id_pack) {
        try {
        $package = Package::findOrFail($id_pack);
        $package->statut = $request->statut;
        $package->save();
        
            return redirect()->back()->with('success', 'Package desactivé avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la desactivation du package .');
        }
    }

    public function updatePackageSaving(Request $request ,$id) {
        
        try {
        
        $package = Package::where('id_pack', $id)->first();
        // Maintenant, vous pouvez appeler la méthode produits sur l'objet $package
        $package->produits()->detach();
        $package ->nom                   = $request->nom ;
        $package ->nom_court             = $request->nom_court;
        $package ->description           = $request->description ;

        $pack_filename = $package ->logo_pack;   
        // $logo = $request->file('logo_pack_new');
        // $logo1 =$request->hasFile('logo_pack_new');
        // // Log pour vérifier la réception de la requête
        // Log::info('Requête reçue', $request->all());
        // var_dump($logo1);exit;


            if ($request->hasFile('logo_pack')) {
                $logo = $request->file('logo_pack');
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier
                // Generate a base filename
                $base_filename = 'LOGO_' . $request->nom_court;
                $pack_filename = $base_filename . '.' . $logo_extension;

                // Check if the file already exists and generate a unique name
                $i = 1;
                while (file_exists(storage_path('app/public/image/logo_pack/') . $pack_filename)) {
                    $pack_filename = $base_filename . '_' . $i . '.' . $logo_extension;
                    $i++;
                }

                try {
                    // Move the file to the appropriate storage directory
                    $logo->move(storage_path('app/public/image/logo_pack/'), $pack_filename);
                } catch (FileException $e) {
                    // Handle file move errors
                    return redirect()->back()->with('error', 'photo non telecharger');
                }
            }
                $package->logo_pack           = $pack_filename;
        

            for($i=0; $i <count($request->garanties);$i++){
                $produits= DB::table('products')->where('id_garantie','=',$request->garanties[$i])->get();
                // $pt[]=$produits;
                foreach($produits as $pro){
                    $package->produits()->attach($pro->id_produit);
                }
            }

            
       
            $package->save();
            return redirect()->back()->with(['success' => 'Package ajouté avec succès']);

        } catch (Exception $e) {    
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du package .');

        }
    }

    public function traiterDonneesPackApi(Request $request){
        
        $packages = Package::with(['produits.garantie'])->get();
        $duree = $request->duree;
        $formattedPackages = [];
        $Duree = DB::table('duree')->where('nom', $duree)->value('taux'); 

        foreach ($packages as $package) {
            $logoPackContent = Storage::get('/public/image/logo_pack/' . $package->logo_pack);   
            $logoPackBase64 = base64_encode($logoPackContent);
            $dataLogoPackURL = 'data:image/png;base64,' . $logoPackBase64;
            $formattedPackage = [
                // 'id_pack' => $package->id_pack,
                // 'id_entreprise' => $package->id_entreprise,
                'nom' => $package->nom,
                'nom_court' => $package->nom_court,
                'description' => $package->description,
                // "logo_pack" => $package->logo_pack, 
                "logo_pack_base64" => $dataLogoPackURL, 
                // 'created_at' => $package->created_at,
                // 'updated_at' => $package->updated_at,
                // 'deleted_at' => $package->deleted_at,
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
                            if($produit->force_fiscale == $request->force_fiscale && $produit->cat_vehicule == $request->cat_vehicule && $produit->type_carrosserie == $request->type_carrosserie  && $produit->energie == $request->energie ){           
                                $fgaPercent = 2.5;
                                $produit->prime -= 0.2 * $produit->prime;
                                $fgaAmount = ($produit->prime * $fgaPercent) / 100; // Calcul de la FGA
                                $produit->fga = round($fgaAmount * $Duree);
                                $produit->prime = round($produit->prime* $Duree);
                                $fgaTotal = $produit->fga;
                                $primeTotal = $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'dr':
                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                $produit->prime = round($produit->prime * $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'ar':
                            if($produit->cat_vehicule == $request->cat_vehicule){
                                $capitale_assure=500000;    
                                $produit->prime = round((($produit->prime)/100 * $capitale_assure)* $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'bdg':
                            if($produit->force_fiscale == $request->force_fiscale && $produit->energie == $request->energie ){    
                                $produit->prime = round($produit->prime * $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        
                        case 'sr':
                            if($produit->cat_vehicule == $request->cat_vehicule && $produit->option_pers_tr == 'option1' ){  
                                $produit->prime =round(($request->nombre_place*$produit->prime)*$Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'icd':
                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'vol':
                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                $produit->prime = round((($produit->prime)/100 *$request->valeur_venale) * $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'tcol':
                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                $produit->prime = round((($produit->prime)/100 *$request->valeur_neuf)  * $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
                            }
                        break;
                        case 'tcmp':
                            if($produit->cat_vehicule == $request->cat_vehicule){    
                                $produit->prime = round((($produit->prime)/100 *$request->valeur_neuf) * $Duree);
                                $primeTotal += $produit->prime;
                                $nom_garantie=$produit->garantie->nom_garantie;
                                $garantiespack[]=['id_garantie' => $produit->id_produit,'nom_garantie' => $nom_garantie,'prix_garantie' =>round( $produit->prime*$Duree)];
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
                 // Récupérer le chemin du logo
                $logoContent = Storage::get('/public/image/logo/' . $compagnie->logo);   
                $logoBase64 = base64_encode($logoContent);
                $dataURL = 'data:image/png;base64,' . $logoBase64;
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
                    'valeur_neuf' =>  $request->valeur_neuf,
                    'logo_base64' => $dataURL,
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
        
        return response()->json(['packages' => $formattedPackages]);
      
    }

    public function SimulerPack(Request $request) {
        $url = env('APP_URL') . '/api/traiter-pack';
        $keyurl = env('API_KEY');

        $client = new \GuzzleHttp\Client();
        
        try {        

            // Valider les données du formulaire, y compris la correspondance des mots de passe
            $request->validate([
                'contact' => 'required',
                'duree' => 'required',
                'type_carrosserie' => 'required',
                'nombre_place' => 'required',
                'energie' => 'required',
                'cat_vehicule' => 'required',
                'force_fiscale' => 'required',


            ]);
            $donnee= [
                'contact'=>$request->contact,
                'duree'=> $request->duree,
                'type_carrosserie'=>$request->type_carrosserie,
                // 'date_first_circ'=> $request->date_first_circ,
                'date_first_circ' => $request->date_first_circ ? (new DateTime($request->date_first_circ))->format('d/m/Y') : null,
                'valeur_neuf'=> $request->valeur_neuf,
                'valeur_venale'=> $request->valeur_venale,
                'nombre_place'=> $request->nombre_place,
                'energie'=>$request->energie,
                'cat_vehicule'=>$request->cat_vehicule,
                'force_fiscale'=>$request->force_fiscale,
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
                return view('package.result', compact('responseData'));

        } 
        catch (\Exception $e) {
                // Handle exceptions
                // echo "An error occurred: " . $e->getMessage();
                $response=  $e->getMessage();
        }

    } 

    public function SouscrirPack(Request $request) { 
        $url = env('APP_URL') . '/api/add-client';
        $keyurl = env('API_KEY');

        $client = new \GuzzleHttp\Client();
        $url_facture = null; // Initialiser la variable ici

        try {     
            // Valider les données du formulaire, y compris la correspondance des mots de passe
            $request->validate([
                'contact'                   => 'required',
                'name'                      => 'required',
                'garantie'                  => 'required',
                'force_fiscale'             => 'required',
                'energie'                   => 'required',
                'marque'                    => 'required',
                'model'                     => 'required',
                'vin'                       => 'required',
                'compagnie'                 => 'required',
                'prime_ttc'                 => 'required',
                'prime_net'                 => 'required',
                'taxe'                      => 'required',
                'fga'                       => 'required',
                'logo'                      => 'required',
                'date_effet'                => 'required',
                'energie'                   => 'required',
            ]);  
                
            $partenaireId = (string)auth()->user()->id; // Supposons que l'ID du partenaire est l'ID de l'utilisateur connecté

            $donnee= [
                'civilite' => $request->civilite,
                'name' => $request->name,
                'email' => $request->email,
                'ville' => $request->ville,
                'profession' => $request->profession,
                'adrss' => $request->adrss,
                'date_effet' => (new DateTime($request->date_effet))->format('d/m/Y'),
                'vin' => $request->vin,
                'marque' => $request->marque,
                // 'date_first_circ' => $request->date_first_circ,
                'date_first_circ' => $request->date_first_circ ? (new DateTime($request->date_first_circ))->format('d/m/Y') : null,
                'force_fiscale' => $request->force_fiscale,
                'energie' => $request->energie,
                'valeur_neuf' => $request->valeur_neuf,
                'valeur_venale' => $request->valeur_venale,
                'nombre_place' => $request->nombre_place,
                'type_carrosserie' => $request->type_carrosserie,
                'prime_ttc' => $request->prime_ttc,
                'prime_net' => $request->prime_net,
                'taxe' => $request->taxe,
                'coup_police' => $request->coup_police,
                'logo' => $request->logo,
                'fga' => $request->fga,
                'duree' => $request->duree,
                'contact' => $request->contact,
                'marque' => $request->marque,
                'model' => $request->model,
                'option_pers_tr' => 'option1',
                'capitale_assure' => 500000,
                'compagnie' => $request->compagnie,
                'garantie' => $request->garantie,
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
            if (isset($responseData['url_facture'])) {
                $url_facture = $responseData['url_facture'];
            } else {
                throw new \Exception('URL de facture non trouvée dans la réponse');
            }
        } 
        catch (\Exception $e) {
            // Handle exceptions
            return redirect()->back()->with('error', 'Une erreur s\'est produite: ' . $e->getMessage());
        }

        // Rediriger vers l'URL de la facture si elle est définie
        if ($url_facture) {
            return redirect($url_facture);
        } else {
            return redirect()->back()->with('error', 'URL de facture non disponible.');
        }
    }
    
    public function passiveDelete($id_pack){
        
        $package = Package::find($id_pack);
        $package->deleted_at = Carbon::now();
        $package->update();

        return redirect()->back()->with('success', 'Package supprimé avec succès');

    }
}
