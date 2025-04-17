<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory;
    use Notifiable; 
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['civilite','name','phone','profession','email','adrss','ville'];

    public function package()
    {
        return $this->hasMany(Talons::class);
    }
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    
}
