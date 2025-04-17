<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitV extends Model
{
    protected $table = 'produits_voyages';
    protected $fillable = ['id','compagnie_id','code_pays','duree_max','duree_min','age_min','age_max'];

    // Relations avec les autres modèles
    public function pays()
    {
        return $this->belongsTo(Pays::class, 'code_pays', 'Code');  // Relation avec Pays
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class, 'compagnie_id');  // Relation avec Compagnie
    }
}
