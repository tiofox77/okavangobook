<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela de rastreio de visitas às páginas públicas do site.
 * Alimenta o dashboard "Visitas & Tráfego" do admin (dispositivos,
 * localização, páginas, referrers, séries diárias/semanais/mensais).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('path', 512)->index();
            $table->string('url', 1024)->nullable();
            $table->string('method', 10)->default('GET');
            $table->string('referrer', 1024)->nullable();
            $table->string('referrer_host', 255)->nullable()->index();
            $table->string('ip', 45)->nullable()->index();
            $table->string('country', 100)->nullable();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('city', 100)->nullable();
            $table->string('device_type', 20)->nullable()->index(); // desktop, mobile, tablet, bot
            $table->string('browser', 50)->nullable();
            $table->string('platform', 50)->nullable();             // sistema operativo
            $table->string('language', 10)->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index(['is_bot', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
