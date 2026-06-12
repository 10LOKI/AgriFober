<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Rapport généré pour une parcelle : analyse, programme (fertilisation,
     * etc.) et données calculées. JSON structuré pour parsing Flutter.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parcel_id')->nullable()->constrained('parcels')->nullOnDelete();
            $table->foreignId('culture_id')->nullable()->constrained('cultures')->nullOnDelete();

            $table->string('titre');
            $table->enum('type', ['fertilisation', 'diagnostic', 'rendement', 'sol', 'meteo'])->index();
            $table->enum('status', ['brouillon', 'genere', 'valide', 'archive'])->default('brouillon')->index();

            $table->text('resume')->nullable();
            $table->json('programme')->nullable();   // programme agricole/fertilisation généré
            $table->json('donnees')->nullable();     // données calculées (scores, métriques)

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
