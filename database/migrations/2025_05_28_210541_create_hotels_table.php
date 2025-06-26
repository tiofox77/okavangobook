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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do hotel
            $table->text('description')->nullable(); // Descrição do hotel
            $table->string('address'); // Endereço completo
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); // Relação com a tabela de localizações
            $table->tinyInteger('stars')->default(3); // Classificação em estrelas (1-5)
            $table->string('thumbnail')->nullable(); // Imagem principal/thumbnail
            $table->json('images')->nullable(); // Array de imagens adicionais
            $table->decimal('latitude', 10, 7)->nullable(); // Coordenadas geográficas
            $table->decimal('longitude', 10, 7)->nullable(); // Coordenadas geográficas
            $table->json('amenities')->nullable(); // Comodidades (JSON com Wi-Fi, piscina, etc.)
            $table->time('check_in_time')->default('14:00:00'); // Horário padrão de check-in
            $table->time('check_out_time')->default('12:00:00'); // Horário padrão de check-out
            $table->string('phone')->nullable(); // Telefone de contato
            $table->string('email')->nullable(); // Email de contato
            $table->string('website')->nullable(); // Website oficial
            $table->decimal('min_price', 12, 2)->nullable(); // Preço mínimo para exibição
            $table->decimal('rating', 3, 2)->default(0); // Avaliação média (0-5)
            $table->integer('reviews_count')->default(0); // Número de avaliações
            $table->boolean('is_featured')->default(false); // Hotel em destaque
            $table->boolean('is_active')->default(true); // Status do hotel
            $table->string('slug')->unique(); // Slug para URLs amigáveis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
