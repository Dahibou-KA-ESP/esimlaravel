<?php

namespace App\Http\Controllers;
use Exception;

use App\Models\Garanties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class GarantieController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        
    }

    public function indexGarantie(){
        // $validation_rules = [
        //     'nom_complet'           => 'string|required',
        //     'abr'                   => 'required|string'
        // ];    
        $garantie= Garanties::all()->where('deleted_at',NULL);
        return view('garantie.index',compact('garantie'));
    }

    public function createGarantie(Request $request) {

        try {
            $request->validate ([
                'nom_garantie'           => 'required',
                'nom_court'           => 'required',
                'description'           => 'required',
            ]);  

            $garantie = new Garanties;
            $garantie ->nom_garantie     = $request->nom_garantie;
            $garantie ->nom_court        = $request->nom_court;
            $garantie ->description      = $request->description;
    
            $id_entreprise = auth()->user()->entreprise_id;
            $garantie ->id_entreprise        = $id_entreprise;
            $garantie->save();	
            
    
            return redirect()->back()->with('success', 'Garantie ajoutée avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout de la garantie.');
        }

        
    }

    public function updateGarantie(Request $request ,$id) {
        
        $garantie = DB::table('garanties')->where('id_garantie','=',$id)->get();
     
        return view('garantie.edit_garantie',compact('garantie'));
       
    }

    public function updateGarantieSaving(Request $request ,$id) {

        try {
            
            $request->validate ([
                'nom_garantie'           => 'required',
                'nom_court'           => 'required',
            ]); 

            $garantie=Garanties::find($id);
            $garantie->nom_garantie   =$request->input('nom_garantie');
            $garantie->nom_court      =$request->input('nom_court');
            $garantie->description      =$request->input('description');
            $garantie->update();
            $garantie= Garanties::all();
            return redirect()->back()->with('success', 'Garantie mis à jour avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la garantie');
        }   

    }

    public function passiveDelete($id){
        $garantie=Garanties::find($id);
        $garantie->deleted_at = Carbon::now();
        $garantie->update();

        return redirect()->back()->with('success', 'Garantie supprimé avec succès');

    }
}
