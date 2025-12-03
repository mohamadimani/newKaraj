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
        Schema::table('course_registers', function (Blueprint $table) {
            $table->string('cancel_description')->nullable();
            $table->string('reserve_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_registers', function (Blueprint $table) {
            $table->dropColumn('cancel_description');
            $table->dropColumn('reserve_description');
        });
    }
};
