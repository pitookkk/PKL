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
        Schema::table('products', function (Blueprint $table) {
            $table->string('socket_type')->nullable()->after('brand'); // LGA1700, AM5, etc.
            $table->string('ram_type')->nullable()->after('socket_type'); // DDR4, DDR5, etc.
            $table->integer('wattage_requirement')->default(0)->after('ram_type'); // Estimated TDP in Watts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['socket_type', 'ram_type', 'wattage_requirement']);
        });
    }
};
