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
        Schema::create('clue_online_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('clue_id')->nullable();
            $table->integer('online_course_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('order_item_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clue_online_courses');
    }
};
