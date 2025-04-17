<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriquesV extends Model
{
    protected $table = 'historiques_v';

    protected $fillable = ['contact','date_depart','date_retour','duree','date_naissance','code_pays','age'];

    public function paysH()
    {
        return $this->belongsTo(Pays::class, 'code_pays', 'Code');  // Relation avec Pays
    }
}
