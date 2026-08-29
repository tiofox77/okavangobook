<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token_hash', 64)->unique();
            $table->json('scopes');
            $table->json('allowed_ips')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('agent_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('blocks')->nullable();
            $table->json('seo')->nullable();
            $table->string('preview_token', 64)->nullable()->unique();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('agent_media', function (Blueprint $table) {
            $table->id();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('url');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->foreignId('uploaded_by_token_id')->nullable()->constrained('agent_tokens')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('agent_idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_token_id')->constrained('agent_tokens')->cascadeOnDelete();
            $table->string('key', 128);
            $table->string('method', 12);
            $table->string('path');
            $table->string('request_hash', 64);
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->longText('response_body')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['agent_token_id', 'key']);
        });

        Schema::create('agent_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_token_id')->nullable()->constrained('agent_tokens')->nullOnDelete();
            $table->string('actor');
            $table->string('method', 12);
            $table->string('route');
            $table->string('ip', 45)->nullable();
            $table->text('reason')->nullable();
            $table->string('idempotency_key', 128)->nullable();
            $table->string('subject_type')->nullable();
            $table->string('subject_id')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->boolean('dry_run')->default(false);
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_audit_logs');
        Schema::dropIfExists('agent_idempotency_keys');
        Schema::dropIfExists('agent_media');
        Schema::dropIfExists('agent_pages');
        Schema::dropIfExists('agent_tokens');
    }
};
