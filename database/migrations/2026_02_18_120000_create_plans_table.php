<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('badge_color')->default('blue');
            $table->string('icon')->nullable();
            $table->decimal('price_monthly', 12, 2)->default(0);
            $table->decimal('price_yearly', 12, 2)->default(0);
            $table->string('currency')->default('AOA');
            $table->integer('max_hotels')->default(1);
            $table->integer('max_room_types_per_hotel')->default(5);
            $table->integer('max_images_per_hotel')->default(5);
            $table->integer('max_images_per_room')->default(3);
            $table->boolean('featured_listing')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('advanced_analytics')->default(false);
            $table->boolean('review_responses')->default(false);
            $table->boolean('restaurant_management')->default(false);
            $table->boolean('leisure_management')->default(false);
            $table->boolean('custom_branding')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('priority_search')->default(false);
            $table->boolean('promotions')->default(false);
            $table->boolean('export_reports')->default(false);
            $table->integer('trial_days')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_free')->default(false);
            $table->json('extra_features')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
