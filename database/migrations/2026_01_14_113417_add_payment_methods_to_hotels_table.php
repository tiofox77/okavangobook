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
        Schema::table('hotels', function (Blueprint $table) {
            $table->boolean('accept_transfer')->default(true);
            $table->boolean('accept_tpa_onsite')->default(true);
            $table->text('transfer_instructions')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'accept_transfer',
                'accept_tpa_onsite',
                'transfer_instructions',
                'bank_name',
                'account_number',
                'iban',
            ]);
        });
    }
};
