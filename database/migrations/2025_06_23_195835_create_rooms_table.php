<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de quartos de hotel.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('number', 20)->nullable();
            $table->string('type', 50);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('capacity')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->json('features')->nullable(); // Para armazenar facilidades como WiFi, TV, etc.
            $table->timestamps();
            
            // Índices para melhorar performance
            $table->index(['hotel_id', 'is_available']);
            $table->index('type');
        });
    }

    /**
     * Remove a tabela de quartos de hotel.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
