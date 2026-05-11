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
        Schema::create('weather_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->nullable()->constrained('parcels')->cascadeOnDelete();

            $table->string('region')->nullable()->index();
            $table->float('temp');                   // °C
            $table->float('humidity');               // %
            $table->float('precipitation');          // mm
            $table->float('wind_speed');             // km/h
            $table->string('condition')->nullable(); // "sunny", "rainy", ...
            $table->string('source')->default('openweather');

            $table->timestamp('recorded_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_data');
    }
};
