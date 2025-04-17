<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garanties extends Model
{
    protected $primaryKey = 'id_garantie'; // Nom de votre clé primaire
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'garanties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_user','nom_garanties','description'];

    public function produits()
    {
        return $this->hasMany(Products::class);
    }
}
