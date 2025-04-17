<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';
    protected $primaryKey = 'id_produit';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['compagnie_id','id_garantie','force_fiscale','prime','cat_vehicule','option_pers_tr','poid_carrosserie','remorque','valeur_neuf','valeur_venale','energie','type_carrosserie'];


    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function garantie()
    {
        return $this->belongsTo(Garanties::class, 'id_garantie', 'id_garantie');
    }
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'produit_pack');
    }
    public function talons()
    {
        return $this->belongsToMany(Talons::class, 'produit_talons');
    }
}
