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
        Schema::create('equipment_intervals', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('interval_id')->nullable()->constrained()->nullOnDelete(); 

            // Interval snapshot
            $table->string('description');
            $table->enum('facilitator', ['crew', 'service provider']);

            // Recurrence logic
            $table->string('frequency'); 
            $table->string('frequency_interval'); 

            // Scheduling
            $table->date('first_completed_at')->nullable(); 
            $table->date('last_completed_at')->nullable();  
            $table->date('next_due_date')->nullable();      

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_intervals');
    }
};
