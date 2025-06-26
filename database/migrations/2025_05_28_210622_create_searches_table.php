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
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Relação com a tabela de usuários (opcional para usuários não logados)
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null'); // Relação com a tabela de localizações
            $table->string('location_text'); // Texto da localização buscada
            $table->date('check_in'); // Data de check-in
            $table->date('check_out'); // Data de check-out
            $table->integer('guests')->default(2); // Número de hóspedes
            $table->integer('rooms')->default(1); // Número de quartos
            $table->json('filters')->nullable(); // Filtros aplicados (JSON)
            $table->string('sort_by')->nullable(); // Critério de ordenação
            $table->string('ip_address')->nullable(); // Endereço IP da busca (para análise)
            $table->string('user_agent')->nullable(); // User agent do navegador
            $table->integer('results_count')->nullable(); // Número de resultados encontrados
            $table->boolean('is_saved')->default(false); // Se o usuário salvou esta busca
            $table->timestamps();
            
            // Índice para buscas eficientes
            $table->index(['user_id', 'location_id', 'check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searches');
    }
};
