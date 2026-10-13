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
        Schema::create('budget_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->decimal('total_budget', 12, 2);
            $table->integer('package_quantity');
            $table->decimal('average_budget_per_package', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->decimal('remaining_budget', 12, 2);
            $table->string('status')->default('Menunggu');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('budget_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_order_id')->constrained('budget_orders')->onDelete('cascade');
            $table->integer('package_number');
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('set null');
            $table->string('menu_name');
            $table->string('menu_category');
            $table->decimal('menu_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_order_items');
        Schema::dropIfExists('budget_orders');
    }
};
