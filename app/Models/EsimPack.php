<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsimPack extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'data_volume', 'duration_days', 'price',
    ];

    // Un pack d'eSIM peut avoir plusieurs abonnements
    public function subscriptions()
    {
        return $this->hasMany(EsimSubscription::class);
    }
}
