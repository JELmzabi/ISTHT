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
        Schema::create('sortie_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('type_sortie');
            $table->date('date_sortie');
            $table->text('motif');
            $table->text('notes')->nullable();
            $table->string('statut');
            # what the client_id should be 
            $table->foreignId('demandeur_id')->nullable()->constrained('users', 'id');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sortie_stocks');
    }
};
