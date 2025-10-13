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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('type_mouvement');
            $table->string('reference');
            $table->string('source_type')->nullable();
            $table->bigInteger('source_id')->nullable();
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('prix_total', 10, 2);
            $table->decimal('stock_avant', 10, 2);
            $table->decimal('stock_apres', 10, 2);
            $table->date('date_mouvement');
            $table->text('motif')->nullable();
            $table->foreignId('article_id')->constrained('articles');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
