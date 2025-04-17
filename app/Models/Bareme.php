<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bareme extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'duree';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom','taux'];
}

