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
        Schema::create('post_models', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('type');
            $table->text('content')->nullable();
            $table->string('blur_hash')->nullable();
            $table->string('user_id');
            $table->string('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_models');
    }
};
