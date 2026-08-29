<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agent_media', function (Blueprint $table) {
            $table->foreignId('hotel_id')->nullable()->after('id')->constrained('hotels')->nullOnDelete();
            $table->unsignedInteger('position')->default(0)->after('title');
            $table->boolean('is_cover')->default(false)->after('position');
            $table->index(['hotel_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::table('agent_media', function (Blueprint $table) {
            $table->dropIndex(['hotel_id', 'position']);
            $table->dropConstrainedForeignId('hotel_id');
            $table->dropColumn(['position', 'is_cover']);
        });
    }
};
