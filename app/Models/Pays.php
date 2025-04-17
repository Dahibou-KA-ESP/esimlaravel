<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    protected $table = 'codepays';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Code','Nom'];
    protected $primaryKey = 'code';  // La clé primaire est 'code'

    // Si tu as besoin de définir des relations inverses
    public function produits()
    {
        return $this->hasMany(ProduitV::class, 'code_pays', 'Code');
    }
    
    
}
