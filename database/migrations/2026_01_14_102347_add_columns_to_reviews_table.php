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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('hotel_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->after('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->integer('rating')->unsigned()->after('reservation_id')->comment('1-5 stars');
            $table->string('title')->nullable()->after('rating');
            $table->text('comment')->after('title');
            $table->json('photos')->nullable()->after('comment')->comment('Array of photo paths uploaded by user');
            $table->boolean('is_verified')->default(false)->after('photos')->comment('Verified stay');
            $table->boolean('is_approved')->default(true)->after('is_verified')->comment('Admin approval');
            $table->text('response')->nullable()->after('is_approved')->comment('Hotel response to review');
            $table->timestamp('responded_at')->nullable()->after('response');
            
            $table->index(['hotel_id', 'is_approved']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['reservation_id']);
            $table->dropIndex(['hotel_id', 'is_approved']);
            $table->dropIndex(['user_id']);
            $table->dropColumn([
                'hotel_id',
                'user_id',
                'reservation_id',
                'rating',
                'title',
                'comment',
                'photos',
                'is_verified',
                'is_approved',
                'response',
                'responded_at'
            ]);
        });
    }
};
