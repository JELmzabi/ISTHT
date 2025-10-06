<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('article_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('nom_fichier');
            $table->boolean('est_principale')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('article_images');
    }
};
