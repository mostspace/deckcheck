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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interval_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('description');
            $table->string('instructions')->nullable();
            $table->enum('applicable_to', ['All Equipment', 'Specific Equipment', 'Conditional'])->default('All Equipment');
            $table->json('applicability_conditions')->nullable();
            $table->integer('display_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
