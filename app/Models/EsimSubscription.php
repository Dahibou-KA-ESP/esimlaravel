<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EsimSubscription extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'esim_pack_id', 'remaining_data', 'expiry_date',
    ];

    // Un abonnement appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un abonnement concerne un pack d'eSIM
    public function esimPack()
    {
        return $this->belongsTo(EsimPack::class);
    }

    // Vérifier si le pack est expiré
    public function isExpired()
    {
        return now()->greaterThanOrEqualTo($this->expiry_date);
    }
}
