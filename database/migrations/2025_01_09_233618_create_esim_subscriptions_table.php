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
        Schema::create('esim_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'utilisateur qui a acheté le pack
            $table->foreignId('esim_pack_id')->constrained()->onDelete('cascade'); // Le pack acheté
            $table->decimal('remaining_data', 8, 2); // Données restantes dans le pack
            $table->date('expiry_date');  // Date d'expiration du pack
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esim_subscriptions');
    }
};
