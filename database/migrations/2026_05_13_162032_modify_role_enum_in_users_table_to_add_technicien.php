<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to include 'technicien'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','technicien','agriculteur') DEFAULT 'agriculteur'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM (without technicien)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','agriculteur') DEFAULT 'agriculteur'");
    }
};
