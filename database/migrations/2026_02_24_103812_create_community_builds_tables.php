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
        // Table for the main build post
        Schema::create('community_builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('likes_count')->default(0);
            $table->timestamps();
        });

        // Pivot table to link products used in the build
        Schema::create('build_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_build_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('build_product');
        Schema::dropIfExists('community_builds');
    }
};
