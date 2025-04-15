<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mobile_money_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name');  // Ex : Orange Money, M-Pesa
            $table->string('api_key');        // Clé API du fournisseur
            $table->string('api_secret');     // Secret API du fournisseur
            $table->string('payment_url');    // URL de l'API de paiement
            $table->string('callback_url');   // URL pour recevoir les notifications
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_money_settings');
    }
};
