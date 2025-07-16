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
        Schema::create('work_order_tasks', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');

            // Snapshot from interval task
            $table->string('name');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('sequence_position')->default(0);

            // Completion + flagging
            $table->enum('status', ['pending', 'completed', 'flagged', 'deferred'])->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_flagged')->default(false);

            // Optional notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_tasks');
    }
};
