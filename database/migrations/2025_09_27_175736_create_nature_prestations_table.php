<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nature_prestations', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->foreignId('categorie_principale_id')
                  ->constrained('categorie_principales')
                  ->cascadeOnDelete();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('nature_prestations');
    }
};
