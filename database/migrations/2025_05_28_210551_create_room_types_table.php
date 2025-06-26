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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade'); // Relação com a tabela de hotéis
            $table->string('name'); // Nome do tipo de quarto (ex: Standard, Deluxe, Suite)
            $table->text('description')->nullable(); // Descrição do tipo de quarto
            $table->integer('capacity')->default(2); // Capacidade máxima de hóspedes
            $table->integer('beds')->default(1); // Número de camas
            $table->string('bed_type')->nullable(); // Tipo de cama (ex: King, Queen, Twin)
            $table->integer('size')->nullable(); // Tamanho em metros quadrados
            $table->json('amenities')->nullable(); // Comodidades específicas do quarto
            $table->json('images')->nullable(); // Imagens do tipo de quarto
            $table->boolean('is_available')->default(true); // Disponibilidade geral
            $table->decimal('base_price', 12, 2); // Preço base (em Kwanzas)
            $table->integer('rooms_count')->default(0); // Quantidade de quartos deste tipo
            $table->boolean('is_featured')->default(false); // Tipo de quarto em destaque
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
