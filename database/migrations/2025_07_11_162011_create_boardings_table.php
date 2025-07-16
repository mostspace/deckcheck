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
        Schema::create('boardings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vessel_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['active', 'invited', 'denied', 'archived' ])->default('active');
            $table->unique(['user_id', 'vessel_id']); // Prevent duplicate boardings
            
            // Active vessel for the user
            $table->boolean('is_primary')->default(false); 

            // Onboard Parameters
            $table->boolean('is_crew')->default(true);
            $table->enum('access_level', ['owner', 'admin', 'crew', 'viewer'])->default('crew');
            $table->enum('department', ['bridge', 'interior', 'exterior', 'galley', 'engineering', 'management', 'owner' ]);
            $table->string('role');
            $table->integer('crew_number')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('terminated_at')->nullable();

            $table->timestamps();           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boardings');
    }
};
