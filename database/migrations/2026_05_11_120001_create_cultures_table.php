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
        Schema::create('cultures', function (Blueprint $table) {
            $table->id();
            $table->string('nom_commun');
            $table->string('nom_scientifique')->nullable();
            $table->enum('type', ['fruit', 'legume', 'cereale', 'legumineuse', 'autre']);
            $table->enum('saison', ['printemps', 'ete', 'automne', 'hiver', 'toute_annee']);

            $table->float('ph_sol_min')->nullable();
            $table->float('ph_sol_max')->nullable();
            $table->integer('temp_min')->nullable();
            $table->integer('temp_max')->nullable();
            $table->integer('besoin_eau_cycle')->nullable();
            $table->enum('soil_type', ['argileux', 'sableux', 'limoneux', 'humifere'])->nullable();
            $table->text('conseils')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultures');
    }
};
