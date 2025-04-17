<?php

namespace App\Models;

use App\Models\Talons;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    protected $table = 'mode_paiement';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_paiement','nom','created_at','updated_at'];
    protected $primaryKey = 'id_paiement';  // La clé primaire est 'code'

     // Définir la relation avec le modèle Talon
     public function talons()
     {
         return $this->hasMany(Talons::class, 'mode_paiement', 'id_paiement');
     }

     public function talonsV()
     {
         return $this->hasMany(TalonV::class, 'mode_paiement', 'id_paiement');
     }
}
