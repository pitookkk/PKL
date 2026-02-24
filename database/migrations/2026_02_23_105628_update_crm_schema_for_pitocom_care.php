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
        // 1. Add new CRM-related columns to the users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('points')->default(0)->after('remember_token');
            $table->string('membership_level')->default('Silver')->after('points'); // Using string for flexibility over ENUM
            $table->decimal('total_spent', 15, 2)->default(0)->after('membership_level');
        });

        // 2. Create the wishlists table
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'product_id']); // A user can only wishlist a product once
        });

        // 3. Create the interaction_logs table
        Schema::create('interaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interaction_logs');
        Schema::dropIfExists('wishlists');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'membership_level', 'total_spent']);
        });
    }
};
