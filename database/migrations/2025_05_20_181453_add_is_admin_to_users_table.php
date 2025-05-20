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
        Schema::table('users', function (Blueprint $table) {
            // It's good practice to check if the column doesn't already exist,
            // especially if you're unsure about the table's current state.
            if (!Schema::hasColumn('users', 'is_admin')) {
                // We take the definition from your provided users table structure:
                // $table->boolean('is_admin')->default(false);
                // And we try to place it in a similar position (after 'password').
                $table->boolean('is_admin')->default(false)->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only attempt to drop the column if it exists.
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }
};