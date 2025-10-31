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
        Schema::create('chef_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('statut');
            $table->foreignId('user_id')->constrained();
            $table->timestamp('annule_at')->nullable();
            $table->text('note')->nullable();
            $table->text('validation_note')->nullable();
            $table->timestamps();
        });

        Schema::create('chef_commandes_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantite_commandee', 8, 2);
            $table->foreignId('article_id')->constrained();
            $table->foreignId('chef_commande_id')->constrained('chef_commandes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chef_commandes_items');
        Schema::dropIfExists('chef_commandes');
    }
};
