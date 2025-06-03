<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('menu_id');
            $table->string('shape')->nullable()->after('custom_name');
            $table->string('color')->nullable()->after('shape');
            $table->string('topping')->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['custom_name', 'shape', 'color', 'topping']);
        });
    }
};
