<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historiques extends Model
{
    protected $table = 'historiques';

    protected $fillable = ['contact','force_fiscale','energie','duree','valeur_neuf','valeur_venale','type_carrosserie','nombre_place'];

    public function entreprise()
    {
        return $this->belongsTo(Company::class);
    }
}
