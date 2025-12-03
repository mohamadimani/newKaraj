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
        Schema::table('course_baskets', function (Blueprint $table) {
            $table->integer('discount_id')->nullable();
            $table->boolean('is_full_pay')->default(false);
            $table->dropColumn(['quantity', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_baskets', function (Blueprint $table) {
            $table->dropColumn(['discount_id', 'full_pay']);
            $table->integer('quantity')->default(1);
            $table->boolean('is_active')->default(true);
        });
    }
};
