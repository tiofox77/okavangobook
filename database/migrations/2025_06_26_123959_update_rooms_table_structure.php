<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Atualiza a estrutura da tabela rooms para incluir room_type_id e modernizar colunas.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Adicionar room_type_id se não existir
            if (!Schema::hasColumn('rooms', 'room_type_id')) {
                $table->foreignId('room_type_id')->nullable()->constrained()->onDelete('cascade')->after('hotel_id');
            }
            
            // Adicionar/modificar colunas conforme estrutura moderna
            if (!Schema::hasColumn('rooms', 'room_number')) {
                $table->string('room_number')->after('room_type_id');
            }
            
            if (!Schema::hasColumn('rooms', 'floor')) {
                $table->string('floor')->nullable()->after('room_number');
            }
            
            if (!Schema::hasColumn('rooms', 'is_clean')) {
                $table->boolean('is_clean')->default(true)->after('is_available');
            }
            
            if (!Schema::hasColumn('rooms', 'is_maintenance')) {
                $table->boolean('is_maintenance')->default(false)->after('is_clean');
            }
            
            if (!Schema::hasColumn('rooms', 'status')) {
                $table->string('status')->default('available')->after('is_maintenance');
            }
            
            if (!Schema::hasColumn('rooms', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('rooms', 'available_from')) {
                $table->date('available_from')->nullable()->after('notes');
            }
        });
        
        // Adicionar índice único após adicionar colunas
        if (Schema::hasColumn('rooms', 'room_number')) {
            try {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->unique(['hotel_id', 'room_number']);
                });
            } catch (\Exception $e) {
                // Índice pode já existir, ignorar erro
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Remover colunas adicionadas
            $columnsToRemove = [
                'room_type_id', 'room_number', 'floor', 'is_clean', 
                'is_maintenance', 'status', 'notes', 'available_from'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('rooms', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Remover índice único
            try {
                $table->dropUnique(['hotel_id', 'room_number']);
            } catch (\Exception $e) {
                // Índice pode não existir, ignorar erro
            }
        });
    }
};
