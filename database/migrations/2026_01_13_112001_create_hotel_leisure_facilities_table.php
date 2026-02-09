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
        Schema::create('hotel_leisure_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // piscina, spa, ginasio, sauna, campo_tenis, etc.
            $table->text('description')->nullable();
            $table->json('images')->nullable(); // Array de imagens
            $table->boolean('is_available')->default(true);
            $table->boolean('is_free')->default(true); // Grátis para hóspedes
            $table->decimal('price_per_hour', 10, 2)->nullable(); // Preço por hora se pago
            $table->decimal('daily_price', 10, 2)->nullable(); // Preço diário se aplicável
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->string('operating_days')->nullable(); // JSON: ["monday", "tuesday", ...]
            $table->integer('capacity')->nullable(); // Capacidade máxima
            $table->boolean('requires_booking')->default(false);
            $table->text('rules')->nullable(); // Regras de uso
            $table->string('location')->nullable(); // Localização dentro do hotel
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_leisure_facilities');
    }
};
