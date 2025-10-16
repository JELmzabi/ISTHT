<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mouvement_stocks', function (Blueprint $table) {
            $table->renameColumn('prix_ht', 'taux_tva');
        });

        Schema::table('mouvement_stocks', function (Blueprint $table) {
            $table->decimal('taux_tva', 5, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('mouvement_stocks', function (Blueprint $table) {
            $table->decimal('taux_tva', 8, 2)->change();
        });

        Schema::table('mouvement_stocks', function (Blueprint $table) {
            $table->renameColumn('taux_tva', 'prix_ht');
        });
    }
};
