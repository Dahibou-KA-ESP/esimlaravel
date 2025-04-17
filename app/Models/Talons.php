<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talons extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'talons';

    protected $primaryKey = 'id_talon';
    
    protected $fillable = ['client_id','statut','mode_paiement','franchise_vol','franchise_bdg','franchise_tr','capital_dece','capital_invalidite','frais_medicaux','garantie','force_fiscale','marque','model','energie','prime','vin','date_first_circ',
    'duree','date_effet','date_etablissement','date_echeance','n_police','n_attestation_jaune','n_attestation_cedeao','valeur_neuf','valeur_venale','image_recto','image_verso'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function entreprise()
    {
        return $this->belongsTo(Company::class);
    }
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_talons');
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    // Définir la relation avec le modèle Paiement
    public function paiementW()
    {
        return $this->belongsTo(Paiement::class, 'mode_paiement', 'id_paiement');
    }
}
 