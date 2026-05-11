<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Composition forte avec User. JSON structuré pour parsing Flutter.
     */
    public function up(): void
    {
        Schema::create('interaction_ias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Composition
            $table->foreignId('parcel_id')->nullable()->constrained('parcels')->nullOnDelete();

            $table->enum('type', ['chat', 'diagnostic', 'recommandation', 'analyse_image']);
            $table->enum('input_mode', ['text', 'voice', 'image'])->default('text');

            $table->text('prompt_text');
            $table->json('response_data');                      // structuré (advice, products[], confidence)
            $table->string('image_path')->nullable();           // V2 vision
            $table->integer('tokens_used')->default(0);
            $table->tinyInteger('feedback_rating')->nullable(); // 1..5

            // Abstraction IA — pas de "DeepSeek" en dur
            $table->string('engine')->default('default');       // "deepseek", "gpt5", "ollama", ...
            $table->string('model_version')->nullable();

            $table->timestamps();
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interaction_ias');
    }
};
