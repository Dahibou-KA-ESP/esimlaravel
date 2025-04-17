<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModePaiement extends Controller
{
    public function index(){
        $paiement= Paiement::all();
        return view('mode_paiement.index',compact('paiement'));
    }

    public function createMpaiement(Request $request) {

        try {
            $request->validate ([
                'nom'           => 'required',
            ]);  

            $paiement = new Paiement;
            $paiement ->nom     = $request->nom;
            $paiement->save();	
            
    
            return redirect()->back()->with('success', 'Mode de paiement ajoutée avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        
    }

    public function updateMpaiement(Request $request ,$id) {
        
        $paiement = DB::table('mode_paiement')->where('id_paiement','=',$id)->get();
     
        return view('mode_paiement.edit',compact('paiement'));
       
    }

    public function updateMPaiementSaving(Request $request ,$id) {

        try {
            
            $request->validate ([
                'nom'           => 'required',
            ]); 

            $paiement=Paiement::find($id);
            $paiement->nom   =$request->input('nom');
           
            $paiement->update();
            $paiement= Paiement::all();
            return redirect()->back()->with('success', 'Mode de paiement mis à jour avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du mode de paiement');
        }   

    }
}
