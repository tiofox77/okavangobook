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
        Schema::create('update_histories', function (Blueprint $table) {
            $table->id();
            $table->string('version_from')->nullable();
            $table->string('version_to');
            $table->string('status')->default('pending'); // pending, in_progress, completed, failed, rolled_back
            $table->string('release_name')->nullable();
            $table->text('release_notes')->nullable();
            $table->string('backup_file')->nullable();
            $table->text('log')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_histories');
    }
};
