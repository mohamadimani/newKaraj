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
        Schema::create('course_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->double('amount')->nullable();
            $table->integer('discount_id')->nullable();
            $table->double('discount_amount')->default(0);
            $table->double('final_amount')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_full_pay')->default(false);
            $table->integer('teacher_id')->nullable();
            $table->string('pay_date')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_order_items');
    }
};
