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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do destino (ex: Luanda, Benguela)
            $table->string('province'); // Província de Angola
            $table->text('description')->nullable(); // Descrição do destino
            $table->string('image')->nullable(); // URL da imagem do destino
            $table->string('slug')->unique(); // Slug para URLs amigáveis
            $table->decimal('latitude', 10, 7)->nullable(); // Coordenadas geográficas
            $table->decimal('longitude', 10, 7)->nullable(); // Coordenadas geográficas
            $table->boolean('is_featured')->default(false); // Destino em destaque
            $table->integer('hotels_count')->default(0); // Contador de hotéis neste destino
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
