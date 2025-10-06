<?php
// database/migrations/2025_01_05_000000_create_magasiniers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('magasiniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom_complet');
            $table->enum('role', ['magasinier', 'responsable_magasin', 'assistant_magasin'])->default('magasinier');
            $table->string('telephone')->nullable();
            $table->string('matricule')->unique()->nullable();
            $table->boolean('est_actif')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('user_id');
            $table->index('role');
            $table->index('est_actif');
        });
    }

    public function down()
    {
        Schema::dropIfExists('magasiniers');
    }
};