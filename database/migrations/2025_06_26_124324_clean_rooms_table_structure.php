<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Limpa a estrutura da tabela rooms removendo colunas antigas desnecessárias.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Remover colunas antigas que não são mais necessárias
            $columnsToRemove = [
                'name', 'number', 'type', 'description', 'capacity', 
                'price_per_night', 'features'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('rooms', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Readicionar colunas removidas
            $table->string('name', 100)->after('hotel_id');
            $table->string('number', 20)->nullable()->after('name');
            $table->string('type', 50)->after('number');
            $table->text('description')->nullable()->after('type');
            $table->unsignedTinyInteger('capacity')->default(1)->after('description');
            $table->decimal('price_per_night', 10, 2)->after('capacity');
            $table->json('features')->nullable()->after('is_available');
        });
    }
};
