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
        Schema::create('entree_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->date('date_entree');
            $table->text('notes')->nullable();
            $table->string('statut')->nullable();
            $table->foreignId('bon_reception_id')->constrained('bon_receptions');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
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
        Schema::dropIfExists('entree_stocks');
    }
};
