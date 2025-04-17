<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalonV extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'talons_voyages';

    protected $primaryKey = 'id_talon_v';
    
    protected $fillable = ['client_id','contact','name','adrss','email','ville','statut','mode_paiement','prime_ttc','date_depart','date_retour','date_naissance','age','code_pays','pays_destination','n_passport','duree','n_police','image_verso_v'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function entreprise()
    {
        return $this->belongsTo(Company::class);
    }
    // public function produits()
    // {
    //     return $this->belongsToMany(Produit::class, 'produit_talons');
    // }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function paiementO()
    {
        return $this->belongsTo(Paiement::class, 'mode_paiement', 'id_paiement');
    }

}