<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Journal des changements (change log) lié à un rapport : qui a fait
     * quoi et quand, avec le diff structuré en JSON.
     */
    public function up(): void
    {
        Schema::create('report_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('action');                 // created, updated, status_changed, regenerated, ...
            $table->json('changements')->nullable();  // diff { field: { old, new } }
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['report_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_histories');
    }
};
