<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\User;
use App\Models\Client;
use App\Models\Talons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class PartenaireController extends Controller
{
    public function index(){
        $users= User::all()->where('deleted_at',NULL)->where('role','==','partenaire');
        $user = auth()->user();
        return view('partenaire.index',compact('users','user'));
    }


    public function clientPartenaire(Request $request,$id){
        $today = Carbon::today();

        $souscritpart = Talons::where('statut',1)
            ->join('clients', 'talons.client_id', '=', 'clients.id')
            ->where('clients.id_partenaire',  $id)
            ->with('client')->orderBy('talons.updated_at', 'desc')
            ->get();

        $numberOfClientsPart = Client::join('talons', 'clients.id', '=', 'talons.client_id')
            //->select('clients.*', 'talons.*')
            ->where('id_partenaire',  $id)
            ->where('talons.statut', 1)
            ->count();

        $numberOfClientsDayPart = Talons::whereDate('talons.created_at', $today)
            ->join('clients', 'talons.client_id', '=', 'clients.id')
            ->where('clients.id_partenaire',  $id)
            ->where('statut',1)
            ->count();

        $annee = Carbon::now()->year;

        $TotalEncaissePart =Talons::where('statut',1)
            ->join('clients', 'talons.client_id', '=', 'clients.id')
            ->where('clients.id_partenaire',  $id)
            ->whereYear('talons.created_at', $annee)
            ->sum('prime_ttc');

        $TotalEncaisseDayPart = Talons::whereDate('talons.created_at', $today)
            ->join('clients', 'talons.client_id', '=', 'clients.id')
            ->where('clients.id_partenaire',  $id)
            ->where('statut',1)
            ->sum('prime_ttc');

        // $annee = Carbon::now()->year;
        
        $prime = Talons::join('clients', 'talons.client_id', '=', 'clients.id')
            ->where('clients.id_partenaire','=',   $id)
            ->whereYear('talons.created_at', $annee)
            ->where('statut',1)
            ->sum('prime_net');
        
       // Calcul de la commission
        if ($prime > 0) {
            $commission = ($prime * 0.2);

            // Récupération du taux de commission de l'utilisateur
            $user_commission_rate = User::where('id', '=', $id)->first();
            $commission_rate = $user_commission_rate->commission_rate;

            // Calcul de la commission du partenaire
            $commission_partenaire = ($commission * $commission_rate) / 100;
        } else {
            $commission_partenaire = 0; // Retourne 0 si $prime est nulle
        }

        return view('partenaire.client',compact('annee','commission_partenaire','souscritpart','TotalEncaisseDayPart','TotalEncaissePart','numberOfClientsDayPart','numberOfClientsPart'));

    }

    public function userPartenaire(){
        $users = User::whereNull('deleted_at')
            ->where('role', 'partenaire')
            ->get();

        $user = auth()->user();
        return view('partenaire.liste_partenaire',compact('users','user'));
    }

    public function updateStatus(Request $request, $id) {
        try {
        $user = User::findOrFail($id);
        $user->user_actif = $request->user_actif;
        $user->save();
            return redirect()->back()->with('success', 'Utilisateur desactivé avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la desactivation .');
        }
    }
}
