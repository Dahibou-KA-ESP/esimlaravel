<?php

namespace App\Http\Controllers;
use App\Models\Talons;
use App\Models\TalonV;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function index(Request  $request,$client_id,$id_talon){  
        // $package = Talons::with(['produits'])->where('id_talon',$id_talon)->get();

        $package = Talons::with(['produits' => function ($query) {
        $query->withPivot(['prix','garantie']);
        }])->where('id_talon', $id_talon)
        ->first();
        return view('facture',compact('package')); 
    }

    public function indexV(Request  $request,$client_id,$id_talon_v){  
        // $package = Talons::with(['produits'])->where('id_talon',$id_talon)->get();

        $package = TalonV::where('id_talon_v', $id_talon_v)
        ->first();
        return view('facture_voyage',compact('package')); 
    }

    public function FAcClient(Request  $request,$client_id,$id_talon){  
        // $package = Talons::with(['produits'])->where('id_talon',$id_talon)->get();

        $package = Talons::with(['produits' => function ($query) {
        $query->withPivot(['prix','garantie']);
        }])->where('id_talon', $id_talon)
        ->where('statut', 1)
        ->first();
        if ($package !== null) {
            return view('facture_client',compact('package'));
        } else {
            return redirect(env('APP_URL').'/facture/'.$client_id.'/'.$id_talon);  
        } 
    }

    public function FAcClient_V(Request  $request,$client_id,$id_talon_v){  
        // $package = Talons::with(['produits'])->where('id_talon',$id_talon)->get();

        $package = TalonV::where('id_talon_v', $id_talon_v)->where('statut', 1)
        ->first();
        if ($package !== null) {
            return view('facture_client_v',compact('package'));
        } else {
            return redirect(env('APP_URL').'/facture-voyage/'.$client_id.'/'.$id_talon_v);  
        } 
       
    }

    public function conditionPaticuliere(Request  $request,$client_id,$id_talon){  
        // $package = Talons::with(['produits'])->where('id_talon',$id_talon)->get();

        $package = Talons::with(['produits' => function ($query) {
        $query->withPivot(['prix','garantie']);
        }])->where('id_talon', $id_talon)
        ->first();
        return view('condition_particuliere',compact('package')); 
    }
}
