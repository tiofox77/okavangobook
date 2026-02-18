<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('billing_cycle')->default('monthly');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('AOA');
            $table->string('payment_method')->default('bank_transfer');
            $table->string('status')->default('pending'); // pending, approved, rejected, expired
            $table->string('reference_code')->unique();
            $table->string('bank_name')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('transfer_reference')->nullable();
            $table->timestamp('transfer_date')->nullable();
            $table->string('proof_file')->nullable();
            $table->text('user_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('reference_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
