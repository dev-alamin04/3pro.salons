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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password')->nullable();
            $table->string('avatar_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('location')->nullable();
            $table->string('secret_key')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->boolean('is_used_key')->default(false);
            $table->string('role')->default('staff');
            $table->string('specialist')->nullable();
            $table->decimal('badage')->default(0);
            $table->decimal('tier_lavel')->default(0);
            $table->string('pronoun')->nullable();
            $table->string('exprience_level')->default('foundation');
            $table->string('disc_tag')->nullable();

            $table->json('metadata')->nullable()->comment('Flexible storage for additional user data');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('term_accept')->default(true);
            $table->string('provider', 50)->nullable();
            $table->longText('provider_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
