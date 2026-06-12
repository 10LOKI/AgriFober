<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Groups individual AI exchanges into a conversation thread so chat
     * context can be replayed to DeepSeek and reloaded by the frontend.
     */
    public function up(): void
    {
        Schema::table('interaction_ias', function (Blueprint $table) {
            $table->uuid('conversation_id')->nullable()->after('parcel_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interaction_ias', function (Blueprint $table) {
            $table->dropIndex(['conversation_id']);
            $table->dropColumn('conversation_id');
        });
    }
};
