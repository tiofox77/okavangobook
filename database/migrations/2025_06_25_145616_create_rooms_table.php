<?php

declare(strict_types=1);

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
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->string('floor')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_clean')->default(true);
            $table->boolean('is_maintenance')->default(false);
            $table->string('status')->default('available'); // available, occupied, reserved, maintenance, cleaning
            $table->text('notes')->nullable();
            $table->date('available_from')->nullable();
            $table->unique(['hotel_id', 'room_number']);
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
