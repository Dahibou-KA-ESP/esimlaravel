<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compagnie extends Model
{
    

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'compagnie';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['abr','nom_complet','id_entreprise','logo','address','address2','address3','cp','ville','tel_1','tel_2','fax','mail_1','mail_2','mail_3',];

    public function produit()
    {
        return $this->hasMany(Produit::class);
    }
    public function talons()
    {
        return $this->hasMany(Talons::class);
    }
    public function entreprise()
    {
        return $this->belongsTo(Compagnie::class);
    }
    public function produits()
    {
        return $this->hasMany(ProduitV::class, 'compagnie_id');
    }
}
