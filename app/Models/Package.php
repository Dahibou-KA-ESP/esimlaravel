<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages';
    protected $primaryKey = 'id_pack';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom','garanties','description','logo_pack'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_pack');
    }
}
