<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Alarga o enum hotels.property_type:
 * - adiciona o novo tipo "residencial";
 * - inclui "apartment" e "house", que a Agent API já validava mas o enum
 *   da BD (hotel|resort|hospedaria) rejeitava — bug latente corrigido.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `hotels` MODIFY `property_type` ENUM('hotel','resort','hospedaria','residencial','apartment','house') NOT NULL DEFAULT 'hotel'");
    }

    public function down(): void
    {
        // Converte tipos novos para 'hotel' antes de estreitar o enum.
        DB::table('hotels')->whereIn('property_type', ['residencial', 'apartment', 'house'])->update(['property_type' => 'hotel']);
        DB::statement("ALTER TABLE `hotels` MODIFY `property_type` ENUM('hotel','resort','hospedaria') NOT NULL DEFAULT 'hotel'");
    }
};
