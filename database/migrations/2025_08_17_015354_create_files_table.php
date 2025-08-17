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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('disk')->default('s3_private');
            $table->string('path'); // storage path relative to disk root
            $table->string('original_name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('version')->nullable();
            $table->tinyInteger('is_latest')->default(1);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable(); // in bytes
            $table->string('sha256', 64)->nullable()->index(); // dedup
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vessel')->nullable()->constrained('vessels')->nullOnDelete();
            $table->string('visibility')->default('private'); // private | public
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['sha256', 'size']); // optional dedupe index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
