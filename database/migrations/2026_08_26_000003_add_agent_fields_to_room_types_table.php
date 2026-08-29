<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->unsignedInteger('adult_capacity')->nullable()->after('capacity');
            $table->unsignedInteger('children_capacity')->default(0)->after('adult_capacity');
            $table->unsignedInteger('position')->default(0)->after('is_featured');
            $table->string('source_url', 1000)->nullable()->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['adult_capacity', 'children_capacity', 'position', 'source_url']);
        });
    }
};
