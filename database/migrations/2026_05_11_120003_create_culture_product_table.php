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
        Schema::create('culture_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('culture_id')->constrained('cultures')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('dosage_specifique')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['culture_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('culture_product');
    }
};
