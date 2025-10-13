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
        Schema::create('historique_statut_bcs', function (Blueprint $table) {
            $table->id();
            $table->string('ancien_statut');
            $table->string('nouveau_statut');
            $table->text('raison')->nullable();
            $table->foreignId('bon_commande_id')->constrained('bon_commandes', 'id');
            $table->foreignId('changed_by')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_statut_bcs');
    }
};
