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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rating')->unsigned()->comment('1-5 stars');
            $table->string('title')->nullable();
            $table->text('comment');
            $table->json('photos')->nullable()->comment('Array of photo paths uploaded by user');
            $table->boolean('is_verified')->default(false)->comment('Verified stay');
            $table->boolean('is_approved')->default(true)->comment('Admin approval');
            $table->string('response')->nullable()->comment('Hotel response to review');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            
            $table->index(['hotel_id', 'is_approved']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
