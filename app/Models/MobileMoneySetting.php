<?php

namespace App\Models;

class MobileMoneySetting extends BaseModel
{
    protected $fillable = [
        'provider_name',
        'api_key',
        'api_secret',
        'payment_url',
        'callback_url'
    ];

    public static function getProviderConfig($providerName)
    {
        return self::where('provider_name', $providerName)->first();
    }
}
