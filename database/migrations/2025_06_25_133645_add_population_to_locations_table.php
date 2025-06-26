<?php

declare(strict_types=1);

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
        Schema::table('locations', function (Blueprint $table) {
            if (!Schema::hasColumn('locations', 'population')) {
                $table->unsignedBigInteger('population')->nullable()->comment('Número de habitantes da localização');
            }
            
            if (!Schema::hasColumn('locations', 'hotels_count')) {
                $table->unsignedInteger('hotels_count')->default(0)->comment('Contador automático de hotéis nesta localização');
            }
            
            if (!Schema::hasColumn('locations', 'capital')) {
                $table->string('capital', 100)->nullable()->comment('Nome da capital se a localização for uma província');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['population', 'hotels_count', 'capital']);
        });
    }
};
