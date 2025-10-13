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
        Schema::create('ligne_entree_stocks', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('taux_tva', 5, 2)->default(0);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('prix_total', 10, 2);
            $table->foreignId('entree_stock_id')->constrained('entree_stocks');
            $table->foreignId('article_id')->constrained('articles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_entree_stocks');
    }
};
