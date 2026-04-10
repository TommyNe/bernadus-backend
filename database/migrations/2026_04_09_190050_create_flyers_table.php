<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flyers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('pdf_path');
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'uploaded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flyers');
    }
};
