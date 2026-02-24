<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Change column to string first so it can accept 'user' value
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });

        // 2. Now safely update old data
        DB::table('users')->where('role', 'customer')->update(['role' => 'user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'customer'])->default('customer')->change();
        });
    }
};
