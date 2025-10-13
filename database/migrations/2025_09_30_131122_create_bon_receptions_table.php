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
        Schema::create('bon_receptions', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->dateTime('date_reception');
            $table->string('type_reception');
            $table->string('statut');
            $table->string('fichier_bonlivraison')->nullable();
            $table->string('fichier_facture')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('bon_commande_id')->constrained('bon_commandes');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
            $table->foreignId('responsable_reception_id')->constrained('users', 'id');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_receptions');
    }
};
