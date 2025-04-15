<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reseller extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'email',
        'adresse',
        'credit',
        'dette',
    ];

    /**
     * Get the user associated with the reseller.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
