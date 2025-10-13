<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('code', 20);
            $table->text('description')->nullable();

            $table->foreignId('categorie_principale_id')
                  ->constrained('categorie_principales');

            $table->foreignId('nature_prestation_id')
                  ->constrained('nature_prestations');

            $table->boolean('est_actif')->default(true);
            $table->timestamps();

            // Index composite avec NOM COURT (évite 1059)
            $table->unique(
                ['code','categorie_principale_id','nature_prestation_id'],
                'cat_code_cp_np_uniq'
            );
        });
    }
    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
