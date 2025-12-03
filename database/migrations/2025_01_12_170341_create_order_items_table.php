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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('online_course_id')->nullable();
            $table->double('amount')->nullable();
            $table->integer('quantity')->default(1);
            $table->double('total_amount')->nullable();
            $table->integer('discount_id')->nullable();
            $table->double('discount_amount')->default(0);
            $table->double('final_amount')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('teacher_id')->nullable();
            $table->integer('teacher_percent')->default(0);
            $table->boolean('teacher_withdraw')->default(false);
            $table->string('teacher_withdraw_date')->nullable();
            $table->string('spot_key')->nullable();
            $table->string('license_key')->nullable();
            $table->string('license_url')->nullable();
            $table->string('license_id')->nullable();
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
        Schema::dropIfExists('order_items');
    }
};
