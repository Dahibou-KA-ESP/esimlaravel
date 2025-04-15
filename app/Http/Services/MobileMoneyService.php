<?php
namespace App\Http\Services;

use App\Models\MobileMoneySetting;
use Illuminate\Support\Facades\Http;

class MobileMoneyService
{
    protected $apiKey;
    protected $apiSecret;
    protected $paymentUrl;
    protected $callbackUrl;

    public function __construct($providerName)
    {
        $config = MobileMoneySetting::getProviderConfig($providerName);

        if ($config) {
            $this->apiKey = $config->api_key;
            $this->apiSecret = $config->api_secret;
            $this->paymentUrl = $config->payment_url;
            $this->callbackUrl = $config->callback_url;
        } else {
            throw new \Exception("Fournisseur Mobile Money introuvable.");
        }
    }

    public function initiatePayment($amount, $phoneNumber, $transactionId)
    {
        $response = Http::post($this->paymentUrl, [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'amount' => $amount,
            'phone_number' => $phoneNumber,
            'transaction_id' => $transactionId,
            'callback_url' => $this->callbackUrl
        ]);

        if ($response->successful()) {
            return $response->json();  // Retourne les détails de la transaction
        }

        throw new \Exception("Erreur lors de l'initiation du paiement.");
    }
}
