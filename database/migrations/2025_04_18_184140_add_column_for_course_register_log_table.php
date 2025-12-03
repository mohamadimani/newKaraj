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
        Schema::table('course_register_change_logs', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('course_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_register_change_logs', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('branch_id');
            $table->dropColumn('course_id');
        });
    }
};
