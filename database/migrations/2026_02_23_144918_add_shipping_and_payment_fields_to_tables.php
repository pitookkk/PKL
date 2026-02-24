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
        // 1. Add weight to products
        Schema::table('products', function (Blueprint $table) {
            $table->integer('weight')->default(1000)->after('stock'); // In grams
        });

        // 2. Add shipping and payment fields to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->text('address')->nullable()->after('total_amount');
            $table->integer('province_id')->nullable()->after('address');
            $table->integer('city_id')->nullable()->after('province_id');
            $table->string('courier')->nullable()->after('city_id'); // jne, pos, tiki
            $table->string('shipping_method')->nullable()->after('courier'); // reg, oke, yes
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_method');
            $table->string('snap_token')->nullable()->after('status'); // Midtrans Snap Token
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['address', 'province_id', 'city_id', 'courier', 'shipping_method', 'shipping_cost', 'snap_token']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
