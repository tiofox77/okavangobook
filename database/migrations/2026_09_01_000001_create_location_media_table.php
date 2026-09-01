<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Galeria multimédia dos destinos (/destino/{província}):
 * várias imagens + vídeos (MP4 direto ou YouTube/Vimeo embebido).
 * Gerível pela Agent API (locations:write).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('url', 1000);
            $table->string('title')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['location_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_media');
    }
};
