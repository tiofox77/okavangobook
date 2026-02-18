<?php

namespace App\Console\Commands;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire overdue subscriptions and pending payments';

    public function handle(): int
    {
        $this->info('A verificar subscrições expiradas...');

        // 1. Expirar subscrições activas cujo end_date passou
        $expiredSubs = Subscription::expireOverdue();
        $this->info("Subscrições expiradas: {$expiredSubs}");

        // 2. Expirar pagamentos pendentes com mais de 72h
        $expiredPayments = PaymentTransaction::where('status', PaymentTransaction::STATUS_PENDING)
            ->where('expires_at', '<=', now())
            ->update(['status' => PaymentTransaction::STATUS_EXPIRED]);
        $this->info("Pagamentos pendentes expirados: {$expiredPayments}");

        if ($expiredSubs > 0 || $expiredPayments > 0) {
            Log::info("Subscription expiration run: {$expiredSubs} subscriptions expired, {$expiredPayments} payments expired.");
        }

        $this->info('Concluído.');
        return Command::SUCCESS;
    }
}
