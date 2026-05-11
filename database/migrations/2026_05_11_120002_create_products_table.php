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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nom_commercial');
            $table->text('description')->nullable();
            $table->string('composant_actif')->nullable();
            $table->string('dosage_recommande')->nullable();
            $table->integer('delai_avant_recolte')->nullable();
            $table->enum('type', ['engrais', 'pesticide', 'fongicide', 'herbicide', 'biologique']);
            $table->text('avantages')->nullable();
            $table->text('usage_method')->nullable();
            $table->text('safety_instructions')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
