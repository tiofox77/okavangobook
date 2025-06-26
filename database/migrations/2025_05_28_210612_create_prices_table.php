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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade'); // Relação com a tabela de hotéis
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade'); // Relação com a tabela de tipos de quarto
            $table->string('provider'); // Nome do provedor (ex: Booking, Expedia, Direto)
            $table->decimal('price', 12, 2); // Preço em Kwanzas
            $table->string('currency')->default('AKZ'); // Moeda (AKZ, USD, etc.)
            $table->decimal('original_price', 12, 2)->nullable(); // Preço original (se houver desconto)
            $table->decimal('discount_percentage', 5, 2)->nullable(); // Percentual de desconto
            $table->string('link'); // Link para reserva
            $table->date('check_in'); // Data de check-in para este preço
            $table->date('check_out'); // Data de check-out para este preço
            $table->boolean('breakfast_included')->default(false); // Se inclui café da manhã
            $table->boolean('free_cancellation')->default(false); // Se permite cancelamento gratuito
            $table->boolean('pay_at_hotel')->default(false); // Se permite pagamento no hotel
            $table->string('cancellation_policy')->nullable(); // Política de cancelamento
            $table->json('taxes_fees')->nullable(); // Impostos e taxas adicionais
            $table->dateTime('last_updated'); // Última atualização deste preço
            $table->boolean('is_available')->default(true); // Disponibilidade
            $table->timestamps();
            
            // Índice composto para buscas eficientes
            $table->index(['hotel_id', 'room_type_id', 'provider', 'check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
