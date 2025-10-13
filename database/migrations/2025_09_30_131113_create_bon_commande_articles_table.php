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
        Schema::create('bon_commande_articles', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantite_commandee', 8, 2);
            $table->decimal('taux_tva', 8, 2)->nullable();
            $table->decimal('prix_unitaire_ht', 8, 2)->nullable();
            $table->decimal('montant_ht', 8, 2)->nullable();
            $table->decimal('montant_tva', 8, 2)->nullable();
            $table->decimal('montant_ttc', 8, 2)->nullable();
            $table->foreignId('bon_commande_id')->constrained('bon_commandes')->cascadeOnDelete();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_commande_articles');
    }
};
