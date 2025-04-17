<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Client;
use App\Models\Talons;
use App\Models\TalonV;
use App\Models\Historiques;
use App\Models\HistoriquesV;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $ANNEE;
    public function __construct()
    {
        $this->middleware('auth');
        $this->ANNEE=date('Y');
        
    }

    /**
     * Show the application dashboard.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getNumberOfClients(){
        $numberOfClients = Client::join('talons', 'clients.id', '=', 'talons.client_id')
                            //->select('clients.*', 'talons.*')
                            ->where('talons.statut', '1')
                            ->count();
        return $numberOfClients;
    }

    public function getNumberOfClientsPart(){
        $numberOfClientsPart = Client::join('talons', 'clients.id', '=', 'talons.client_id')
                            //->select('clients.*', 'talons.*')
                            ->where('id_partenaire',  Auth::id())
                            ->where('talons.statut', '1')
                            ->count();
        return $numberOfClientsPart;
    }

    public function getNumberOfClientsDay(){
        $today = Carbon::today();
        $numberOfClientsDay = Talons::whereDate('created_at', $today)->where('statut',1)->count();
        return $numberOfClientsDay;
    }

    public function getNumberOfClientsDayPart(){
        $today = Carbon::today();
        $numberOfClientsDayPart = Talons::whereDate('talons.created_at', $today)
        ->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())
        ->where('statut',1)->count();
        return $numberOfClientsDayPart;
    }

    
    public function getnumberProspect(){
        $numberProspect = Client::join('talons', 'clients.id', '=', 'talons.client_id')
                        //->select('clients.*', 'talons.*')
                        ->where('talons.statut', '0')
                        ->count();
        return $numberProspect;
    }

    public function getNumberOfDaySim(){
        // $numberOfSim = Historiques::distinct('contact')->count('contact');
        $numberOfSim = Historiques::count();

        return $numberOfSim;

    }

    public function getTotalEncaisse(){
        $annee = Carbon::now()->year;
        $TotalEncaisse = Talons::where('statut',1)->whereYear('created_at', $annee)->sum('prime_ttc');
        return $TotalEncaisse;
    }

    public function getTotalEncaissePart(){
        $annee = Carbon::now()->year;
        $TotalEncaissePart =Talons::where('statut',1)
        ->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())
        ->whereYear('talons.created_at', $annee)
        ->sum('prime_ttc'); 
        return $TotalEncaissePart;
    }

    public function getTotalEncaisseDay(){
        $today = Carbon::today();
        $TotalEncaisseDay = Talons::whereDate('created_at', $today)->where('statut',1)->sum('prime_ttc');
        return $TotalEncaisseDay;
    }

    public function getCommission(){
        $annee = Carbon::now()->year;
        $prime = Talons::where('statut',1)
        ->whereYear('created_at', $annee)
        ->sum('prime_net');
        $commissionCP = Talons::where('statut',1)
        ->whereYear('created_at', $annee)
        ->sum('coup_police');
        if ($prime > 0) {
        $commission = ($prime*0.2) + $commissionCP;
        
        return $commission;
        } else {
            return $commission = 0; // Retourne 0 si $prime est nulle
        }
    }

    public function getCommissionPart(){
        $annee = Carbon::now()->year;
        $prime = Talons::join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',   Auth::id())
        ->where('statut',1)
        ->whereYear('talons.created_at', $annee)
        ->sum('prime_net');

            if ($prime > 0) {
                $commission= ($prime*0.2);
                $user_commission_rate= User::where('id',  Auth::id())->first();
                $commission_rate=$user_commission_rate->commission_rate;
                $commission_partenaire=($commission*($commission_rate))/100;

                return $commission_partenaire;
            } 
            else {
                return $commission_partenaire = 0; // Retourne 0 si $prime est nulle
            }

    }

    public function getTotalEncaisseDayPart(){
        $today = Carbon::today();
        $TotalEncaisseDayPart = Talons::whereDate('talons.created_at', $today)
        ->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())
        ->where('statut',1)->sum('prime_ttc');
        return $TotalEncaisseDayPart;
    }

    public function index(Request $request){
        $this->ANNEE=intval($request->input('annee', Carbon::now()->year));

        if(auth()->user()->role == 'social'){
            return Redirect::route('sponsoring');
        }
        else{
            $numberOfClientsPart= $this->getNumberOfClientsPart();
            $numberOfClients = $this->getNumberOfClients(); 
            $numberOfClientsDay = $this->getNumberOfClientsDay(); 
            $numberOfClientsDayPart = $this->getNumberOfClientsDayPart(); 
            $numberProspect = $this->getnumberProspect();
            $TotalEncaisseDay = $this->getTotalEncaisseDay();
            $TotalEncaisseDayPart = $this->getTotalEncaisseDayPart();
            $TotalEncaisse = $this->getTotalEncaisse();
            $TotalEncaissePart = $this->getTotalEncaissePart();
            $numberOfSim = $this->getNumberOfDaySim();
            $commission = $this->getCommission();
            $commissionPart = $this->getCommissionPart();
            $annee_p=$this->ANNEE;
            $souscrit = Talons::where('statut',1)->with('client')->orderBy('updated_at', 'desc')->get();

        $souscritpart = Talons::where('statut',1)
        ->join('clients', 'talons.client_id', '=', 'clients.id')
        ->where('clients.id_partenaire',  Auth::id())
        ->with('client')->orderBy('talons.updated_at', 'desc')
        ->get();
        
        
        $simulation = Historiques::select('*')->orderBy('created_at', 'desc')->get();
        $simulationV = HistoriquesV::select('*')->orderBy('created_at', 'desc')->get();
        $nonsouscrit= Talons::where('statut',0)->with('client')->orderBy('updated_at', 'desc')->get(); 
        $nonsouscritV= TalonV::where('statut',0)->with('client')->orderBy('updated_at', 'desc')->get(); 
        $souscritv= TalonV::where('statut',1)->with('client')->orderBy('updated_at', 'desc')->get();

        $user = auth()->user();
        $annee = Carbon::now()->year;
       
            return view('home',compact('annee','simulationV','souscritv','nonsouscritV','annee_p','commissionPart','commission','souscritpart','TotalEncaisseDayPart','TotalEncaissePart','numberOfClientsDayPart','numberOfClientsPart','simulation','souscrit','nonsouscrit','numberOfClients','numberProspect','user','numberOfSim','numberOfClientsDay','TotalEncaisseDay','TotalEncaisse'));
        }
    }

    public function getClient() {
        // Récupérer l'année actuelle
        $annee = $this->ANNEE;
        
        // Récupérer les clients groupés par mois
        $Client = Client::whereYear('created_at', $annee)
            ->selectRaw('MONTH(created_at) as mois, COUNT(*) as nbClie')
            ->groupBy('mois')
            ->get()
            ->keyBy('mois'); // Indexer les résultats par mois
        
        // Initialiser les tableaux pour les mois et les données clients
        $moisLabels = [];
        $ClientData = [];
    
        // Boucle à travers chaque mois de l'année en cours (de 1 à 12)
        for ($mois = 1; $mois <= 12; $mois++) {
            // Ajouter le label du mois (nom du mois ou simplement numéro)
            // $moisLabels[] = Carbon::create($annee, $mois, 1)->format('F'); // Format 'F' donne le nom complet du mois
            $moisLabels[] = Carbon::create($annee, $mois, 1)->locale('fr')->isoFormat('MMMM'); // 'MMMM' pour le nom complet du mois

            // Chercher les données du client pour le mois courant
            $MoisClient = $Client->get($mois);
    
            // Ajoutez le nombre de clients pour ce mois, sinon 0
            $ClientData[] = $MoisClient ? $MoisClient->nbClie : 0;
        }
    
        // Retournez les données au format JSON
        return response()->json([
            'mois' => $moisLabels, // Les mois sous forme de labels
            'ClientData' => $ClientData, // Les données des clients
            // 'annee' => $annee // Optionnel, renvoyer l'année
        ]);
    }

    public function getDataGraphe() {
        // Récupérer l'année actuelle
        $annee = $this->ANNEE;
        
        // Obtenez les données pour les simulations et les souscriptions pour chaque jour de l'année en cours
        $simulations = Historiques::whereYear('updated_at', $annee)
                        ->selectRaw('DATE(updated_at) as jour, COUNT(*) as nbSimulations')
                        ->groupBy('jour')
                        ->get();
    
        $simulationsV = HistoriquesV::whereYear('updated_at', $annee)
        ->selectRaw('DATE(updated_at) as jour, COUNT(*) as nbSimulationsV')
        ->groupBy('jour')
        ->get();
    

        $souscriptions = Talons::whereYear('created_at', $annee)
                            ->where('statut', 1)
                            ->selectRaw('DATE(created_at) as jour, COUNT(*) as nbSouscriptions')
                            ->groupBy('jour')
                            ->get();

                            

        $souscriptionsV = TalonV::whereYear('created_at', $annee)
        ->where('statut', 1)
        ->selectRaw('DATE(created_at) as jour, COUNT(*) as nbSouscriptionsVo')
        ->groupBy('jour')
        ->get();
    
        // Formatez les données pour les séries du graphique
        $jours = [];
        $simulationsData = [];
        $simulationsDataV = [];
        $souscriptionsData = [];
        $souscriptionsDataV = [];

    
        // Boucle à travers chaque mois de l'année en cours
        for ($mois = 1; $mois <= 12; $mois++) {
            // Obtenez le nombre de jours dans le mois courant de l'année en cours
            $daysInMonth = Carbon::create($annee, $mois, 1)->daysInMonth;
    
            // Boucle à travers tous les jours du mois courant
            for ($jour = 1; $jour <= $daysInMonth; $jour++) {
                // Créez une nouvelle date pour chaque jour du mois
                $jourDate = Carbon::create($annee, $mois, $jour)->format('Y-m-d');
                $jourSimulations = $simulations->firstWhere('jour', $jourDate);
                $jourSimulationsV = $simulationsV->firstWhere('jour', $jourDate);
                $jourSouscriptions = $souscriptions->firstWhere('jour', $jourDate);
                $jourSouscriptionsV = $souscriptionsV->firstWhere('jour', $jourDate);
    
                // Ajoutez le nombre de simulations pour ce jour
                if ($jourSimulations) {
                    $simulationsData[] = $jourSimulations->nbSimulations;
                } else {
                    $simulationsData[] = 0;
                }
                if ($jourSimulationsV) {
                    $simulationsDataV[] = $jourSimulationsV->nbSimulationsV;
                } else {
                    $simulationsDataV[] = 0;
                }
    
                // Ajoutez le nombre de souscriptions pour ce jour
                if ($jourSouscriptions) {
                    $souscriptionsData[] = $jourSouscriptions->nbSouscriptions;
                } else {
                    $souscriptionsData[] = 0;
                }
                if ($jourSouscriptionsV) {
                    $souscriptionsDataV[] = $jourSouscriptionsV->nbSouscriptionsVo;
                } else {
                    $souscriptionsDataV[] = 0;
                }
    
                // Ajoutez le jour à la liste des jours
                $jours[] = $jourDate;
            }
        }

        // Retournez les données au format JSON
        return response()->json([
            'jours' => $jours,
            'simulationsData' => $simulationsData,
            'simulationsDataV' => $simulationsDataV,
            'souscriptionsData' => $souscriptionsData,
            'souscriptionsDataV' => $souscriptionsDataV,
        ]);
    }

    

    public function getDataGraphePart() {
        // Récupérer l'année actuelle
        $annee = Carbon::now()->year;
    
        // Obtenez les données pour les souscriptions pour chaque jour de l'année en cours
        $souscriptions = Talons::whereYear('talons.created_at', $annee)
                            ->join('clients', 'talons.client_id', '=', 'clients.id')
                            ->where('clients.id_partenaire', Auth::id())
                            ->where('statut', 1)
                            ->selectRaw('DATE(talons.created_at) as jour, COUNT(*) as nbSouscriptions')
                            ->groupBy('jour')
                            ->get();
    
        // Formatez les données pour les séries du graphique
        $jours = [];
        $souscriptionsData = [];
    
        // Boucle à travers chaque mois de l'année en cours
        for ($mois = 1; $mois <= 12; $mois++) {
            // Obtenez le nombre de jours dans le mois courant de l'année en cours
            $daysInMonth = Carbon::create($annee, $mois, 1)->daysInMonth;
    
            // Boucle à travers tous les jours du mois courant
            for ($jour = 1; $jour <= $daysInMonth; $jour++) {
                // Créez une nouvelle date pour chaque jour du mois
                $jourDate = Carbon::create($annee, $mois, $jour)->format('Y-m-d');
                $jourSouscriptions = $souscriptions->firstWhere('jour', $jourDate);
    
                // Ajoutez le nombre de souscriptions pour ce jour
                if ($jourSouscriptions) {
                    $souscriptionsData[] = $jourSouscriptions->nbSouscriptions;
                } else {
                    $souscriptionsData[] = 0;
                }
    
                // Ajoutez le jour à la liste des jours
                $jours[] = $jourDate;
            }
        }
    
        // Retournez les données au format JSON
        return response()->json([
            'jours' => $jours,
            'souscriptionsData' => $souscriptionsData,
        ]);
    }

    public function getData(){
        // Récupérer le nombre de voyages et autos depuis la base de données
        $nbVoyage = TalonV::where('statut', 1)->get()->count();
        $nbAuto = Talons::where('statut', 1)->get()->count();

        $nbSimVoyage = HistoriquesV::count();
        $nbSimAuto = Historiques    ::count();
        // Retourner les données en format JSON
        return response()->json([
            'voyage' => $nbVoyage,
            'auto' => $nbAuto,
            'sim_auto'=>$nbSimAuto,
            'sim_voyage'=>$nbSimVoyage
        ]);
    }

    public function getDataPay(){
        // Récupérer le nombre de voyages et autos depuis la base de données
        $nbWaveV = TalonV::where('statut', 1)->where('mode_paiement',1)->get()->count();
        $nbWave = Talons::where('statut', 1)->where('mode_paiement', 1)->get()->count();
        $wave=$nbWaveV+$nbWave;

        $nbOmV = TalonV::where('statut', 1)->where('mode_paiement', 2)->get()->count();
        $nbOm = Talons::where('statut', 1)->where('mode_paiement', 2)->get()->count();
        $om=$nbOmV+$nbOm;
        // Retourner les données en format JSON
        return response()->json([
            'wave' => $wave,
            'om' => $om
        ]);
    }

}
