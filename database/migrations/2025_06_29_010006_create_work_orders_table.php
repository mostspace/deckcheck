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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('equipment_interval_id')->constrained()->onDelete('cascade');

            // Scheduling & lifecycle
            $table->date('due_date')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'overdue', 'flagged', 'scheduled', 'deferred'])->default('scheduled');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();


            // Completion tracking
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();

            // Additional notes/comments per work order
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
