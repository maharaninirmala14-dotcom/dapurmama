<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('orders', 'delivery_time')) {
                $table->string('delivery_time')->nullable()->after('order_date');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('Belum Dibayar')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name',
                'phone_number',
                'shipping_address',
                'delivery_time',
                'payment_method',
                'payment_status',
                'latitude',
                'longitude',
            ]);
        });
    }
};
