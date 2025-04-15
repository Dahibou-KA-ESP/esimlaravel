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
        Schema::create('esim_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Nom du pack, ex: "Pack 5GB", "Pack 30 jours"
            $table->decimal('data_volume', 8, 2);  // Volume de données en GB ou MB
            $table->integer('duration_days');       // Durée en jours
            $table->decimal('price', 8, 2);         // Prix du pack
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esim_packs');
    }
};
