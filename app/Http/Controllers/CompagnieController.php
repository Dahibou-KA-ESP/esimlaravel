<?php

namespace App\Http\Controllers;
use Exception;
use Carbon\Carbon;
use App\Models\Talons;
use App\Models\TalonV;
use App\Models\Produit;
use App\Models\Compagnie;
use App\Models\Garanties;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class CompagnieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function indexCompagnie(){
        $validation_rules = [
            'nom_complet'           => 'string|required',
            'abr'                   => 'required|string'
        ];    
        $compagnie= Compagnie::all()->where('deleted_at',NULL);
        return view('compagnie.index',compact('compagnie'));
    }

    public function profilCompagnie(Request $request,$id){  
        $compagnie = Compagnie::find($id);
        $produit = $compagnie->produit()->where('deleted_at',NULL)->get();
        $souscrit = Talons::with('client')
        ->where('statut',1)
        ->where('compagnie_id', $compagnie->id)
        ->get();
        $souscritV = TalonV::with('client')
        ->where('statut',1)
        ->where('compagnie_id', $compagnie->id)
        ->get();
        $garantie= Garanties::all();
        // $police=DB::table('talons')->where('comapagnie','=','comapagnie.nom_complet')->get();
        return view('compagnie.profil',compact('compagnie','produit','souscrit','souscritV','garantie'));
    }

    public function produitCompagnie(){      
        $produit = Produit::with('compagnie')->where('deleted_at',Null)->get();
        return view('compagnie.produit',compact('produit'));
    }
    
    public function createCompagnie(Request $request) {

        try {

            $request->validate([
                'nom_complet'           => 'required',
                'abr'                   => 'required',
                'address'               => 'required',
                'tel_1'                 => 'required',
                'ville'                 => 'required',
                'mail_1'                => 'required',
                'logo'                  => 'required',
            ]); 
            
            $logo_filename = 'default-profile.jpg';   
            
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier
            
                // Générer un nom de fichier unique
                $logo_filename = Str::random(7) . '-logo.' . $logo_extension;
            
                try {
                    // Déplacer le fichier vers le dossier de stockage approprié
                    $logo->move(storage_path('app/public/image/logo/'), $logo_filename);
                } catch (FileException $e) {
                    // Gérer les erreurs de déplacement de fichier
                    return redirect()->back()->with('error','photo non telecharger');
                }
            }
            $id_entreprise = auth()->user()->entreprise_id;
            // var_dump($logo_filename);exit;
            $compagnie = Compagnie::create([
                'abr'                       => $request->abr,
                'nom_complet'               => $request->nom_complet ,
                'address'                   => $request->address,
                'cp'                        => $request->cp,
                'ville'                     => $request->ville,
                'tel_1'                     => $request->tel_1,
                'fax'                       => $request->fax,
                'mail_1'                    => $request->mail_1,
                'logo'                      => $logo_filename ,
                'id_entreprise'             => $id_entreprise,
                
            ]);        
            $compagnie->save();	
                    
            return redirect()->back()->with('success', 'Compagnie ajoutée avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout de la compagnie.');
        }

        
    }

    public function createProduit(Request $request) {  

        try {
            $compagnie = Compagnie::find($request->compagnie_id);

            $energie = $request->input('energie');
    
            if (!empty($energie)) {
    
                $request->validate([
                    'cat_vehicule' => 'required',
                    'id_garantie' => 'required',
                    'prime' => 'required',
                    'force_fiscale' => 'required|array', 
                    
                ]);  
    
                $forceFiscales =$request->input('force_fiscale');
    
                $produits = [];
    
                foreach ($forceFiscales as $forceFiscale) {
                    $produits[] = [
                        'id_garantie' => $request->id_garantie,
                        'cat_vehicule' => $request->cat_vehicule,
                        'energie' => $energie,
                        'prime' => $request->prime,
                        'force_fiscale' => $forceFiscale,
                        'compagnie_id' => $compagnie->id,
                        'type_carrosserie' => $request->type_carrosserie
                    ];
                }
            
                Produit::insert($produits);
    
                return redirect()->back()->with('success', 'Produit ajoutée avec succès !');
                
            }
    
            else{
    
                $request->validate ([
                    'cat_vehicule'           => 'required',
                    'id_garantie'           => 'required',
                    'prime'           => 'required'
                ]);
    
                $produit = new Produit($request->only([ 'id_garantie', 'option_pers_tr', 'cat_vehicule', 'prime', 'remorque', 'type_carrosserie']));  
                $produit ->id_garantie               = $request->id_garantie;
                $produit ->cat_vehicule           = $request->cat_vehicule;
                $produit ->prime                  = $request->prime;
                $produit ->remorque               = $request->remorque;
                $produit ->option_pers_tr         = $request->option_pers_tr;
                $produit ->type_carrosserie       = $request->type_carrosserie;
        
                    
                $produit ->compagnie()->associate($compagnie);
                
                $produit->save();	
                
                // Compagnie::create($request->all());
        
                return redirect()->back()->with('success', 'Produit ajoutée avec succès !');
            }
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du produit.');
        }

       
        

    }

    public function updateCompagnie(Request $request ,$id) {
        
        $compagnie = DB::table('compagnie')->where('id','=',$id)->get();
        return view('compagnie.edit_compagnie',compact('compagnie'));
       
    }

    public function updateProduit(Request $request ,$id) {
        
        $produit = DB::table('products')->where('id_produit','=',$id)->get();
        $garantie= Garanties::all();

        return view('compagnie.edit_produit',compact('produit','garantie'));
       
    }

    public function updateCompagnieSaving(Request $request ,$id) {

        try{

            $request->validate([
                'nom_complet'           => 'required',
                'abr'                   => 'required',
                'address'               => 'required',
                'tel_1'                 => 'required',
                'ville'                 => 'required',
                'mail_1'                => 'required',
    
            ]);

            

            $compagnie=Compagnie::find($id);

            $compagnie->nom_complet=$request->input('nom_complet');
            $compagnie->address=$request->input('address');
            $compagnie->ville=$request->input('ville');
            $compagnie->fax=$request->input('fax');
            $compagnie->cp=$request->input('cp');
            $compagnie->abr=$request->input('abr');
            $compagnie->mail_1=$request->input('mail_1');
            $compagnie->mail_2=$request->input('mail_2');
            $compagnie->mail_3=$request->input('mail_3');
            $compagnie->tel_1=$request->input('tel_1');
            $compagnie->tel_2=$request->input('tel_2');
            
            $compagnie->update();

            return redirect()->back()->with('success', 'Compagnie modifiée avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise a jour.');
        }

    }

    public function updateCompagnieProfileSaving(Request $request ,$id) {
        try {
            $compagnie=Compagnie::find($id);
        
            $profil_filename = 'default-profile.jpg';   
            
            if ($request->hasFile('profile_image_filename')) {
                $logo = $request->file('profile_image_filename');
                
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier
            
                // Générer un nom de fichier unique
                $profil_filename = Str::random(7) . '-profile_image.' . $logo_extension;
                try {
                    // Déplacer le fichier vers le dossier de stockage approprié
                    $logo->move(storage_path('app/public/image/profile_image/'), $profil_filename);
                } catch (FileException $e) {
                    // Gérer les erreurs de déplacement de fichier
                    return redirect()->back()->with('error','photo non telecharger');
                }
            }
            // var_dump($profil_filename);exit;
            
            $compagnie->profile_image_filename = $profil_filename;
            $compagnie->update();

            return redirect()->back()->with('success', 'Photo modifié avec succès !'); 
        }
           
         catch (Exception $e) {

            return redirect()->back()->with('error', "Un soucis avec la photo ! ");
        }
    }

    public function updateProduitSaving(Request $request ,$id_produit) {

        try {

            $id_produit = $request->id_produit;
            // $user_id = Auth::id();
    
            Produit::where('id_produit', $id_produit)->update([
                'id_garantie' => $request->input('garantie') ,
                'force_fiscale' => $request->force_fiscale,
                'cat_vehicule' => $request->cat_vehicule,
                'prime' => $request->prime,
                'remorque' => $request->remorque,
                'option_pers_tr' => $request->option_pers_tr,
                'energie' => $request->energie,
                'type_carrosserie' => $request->type_carrosserie,
                'poid_carrosserie' => $request->poid_carrosserie,
                // 'id_user' => $user_id,
            ]);
    
            return redirect()->back()->with('success', 'Produit modifier avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit.');
        }

        

    }

    public function passiveDelete($id){
        $compagnie=Compagnie::find($id);
        $compagnie->deleted_at = Carbon::now();
        $compagnie->update();

        return redirect()->back()->with('success', 'Compagnie supprimé avec succès');

    }

    public function passiveDeleteProduit($id){
        
        $produit=Produit::find($id);
        $produit->deleted_at = Carbon::now();
        $produit->update();

        return redirect()->back()->with('success', 'Produit supprimé avec succès');

    }
}
