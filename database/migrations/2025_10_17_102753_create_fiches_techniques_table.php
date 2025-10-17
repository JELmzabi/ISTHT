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
        Schema::create('fiches_techniques', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('nom');
            $table->string('plat');
            $table->string('responsable');
            $table->integer('effectif');
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
        Schema::dropIfExists('fiches_techniques');
    }
};
