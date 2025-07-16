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
        Schema::create('deficiency_updates', function (Blueprint $table) {
            $table->id();
            $table->string('display_id')->nullable();

            // Relationships
            $table->foreignId('deficiency_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Content
            $table->text('comment')->nullable();
            $table->enum('previous_status', ['open', 'waiting', 'resolved']);
            $table->enum('new_status', ['open', 'waiting', 'resolved'])->nullable();
            $table->enum('previous_priority', ['low', 'medium', 'high'])->nullable();
            $table->enum('new_priority', ['low', 'medium', 'high'])->nullable();

            //Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deficiency_updates');
    }
};
