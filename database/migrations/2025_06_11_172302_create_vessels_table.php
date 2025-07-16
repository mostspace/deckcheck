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
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['MY', 'SY', 'MV', 'SV', 'FV', 'RV'])->default('MY');
            $table->string('flag')->nullable();
            $table->string('registry_port')->nullable();
            $table->string('build_year')->nullable();
            $table->string('vessel_make')->nullable();
            $table->integer('vessel_size')->nullable();
            $table->integer('vessel_loa')->nullable();
            $table->integer('vessel_lwl')->nullable();
            $table->integer('vessel_beam')->nullable();
            $table->integer('vessel_draft')->nullable();
            $table->integer('vessel_gt')->nullable();
            $table->string('official_number')->nullable();
            $table->string('mmsi_number')->nullable();
            $table->string('imo_number')->nullable();
            $table->string('callsign')->nullable();
            $table->string('hero_photo')->nullable();
            $table->string('dpa_name')->nullable();
            $table->string('dpa_phone')->nullable();
            $table->string('dpa_email')->nullable();
            $table->string('vessel_phone')->nullable();
            $table->string('vessel_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
