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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->nullable()->constrained('menus');
            $table->foreignId('courier_id')->nullable()->constrained('couriers')->onDelete('cascade');
            $table->unsignedInteger('amount');
            $table->double('price');
            $table->date('delivery_date');
            $table->string('delivery_status', 50);
            $table->string('custom_name')->nullable();
            $table->string('shape')->nullable();
            $table->string('color')->nullable();
            $table->string('topping')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
