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
        Schema::table('users', function (Blueprint $table) {
            // Activity & login
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->unsignedInteger('login_count')->default(0);
            $table->timestamp('last_seen_at')->nullable();

            // Signup origin
            $table->string('signup_source')->nullable();       
            $table->string('signup_campaign')->nullable();      
            $table->foreignId('referrer_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Feature usage flags
            $table->boolean('has_used_mobile')->default(false);
            $table->boolean('has_submitted_feedback')->default(false);

            // Error/friction
            $table->unsignedInteger('failed_login_attempts')->default(0);
            $table->timestamp('last_failed_login_at')->nullable();
            $table->timestamp('email_bounce_at')->nullable();
            $table->timestamp('password_reset_requested_at')->nullable();

            // Admin Tracking
            $table->text('admin_notes')->nullable();
            $table->boolean('is_test_user')->default(false);
            $table->boolean('is_beta_user')->default(true);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
