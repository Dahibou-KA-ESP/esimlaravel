<?php

namespace App\Http\Controllers;
use App\Models\Talons;
use Illuminate\Http\Request;

class SuccessController extends Controller
{
    public function index(Request  $request,$client_id,$id_talon){  
        $package = Talons::with(['clients'])->where(['id_talon'=>$id_talon, 'statut'=>1])->get();

        if(isset($package)){
            return view('validpaiement',compact('package')); 
        }
        else
        {
            return view('errorpaiement8');
        }
       
    }
}
