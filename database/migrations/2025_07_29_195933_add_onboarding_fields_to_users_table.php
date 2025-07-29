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
            $table->boolean('has_completed_onboarding')->default(false);
            $table->timestamp('agreed_at')->nullable();
            $table->boolean('accepts_marketing')->default(false);
            $table->boolean('accepts_updates')->default(false);
            $table->ipAddress('agreed_ip')->nullable();
            $table->text('agreed_user_agent')->nullable();
            $table->string('terms_version')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->enum('sex', ['male', 'female', 'unspecified'])->nullable();
            $table->string('nationality')->nullable();

            $table->string('residence_address')->nullable();
            $table->string('residence_address_line_two')->nullable();
            $table->string('residence_city')->nullable();
            $table->string('residence_state')->nullable();
            $table->string('residence_zip')->nullable();
            $table->string('residence_country')->nullable();
            
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
