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
        Schema::create('online_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->double('amount')->default(0);
            $table->integer('duration_hour')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('registered_count')->default(0);
            $table->string('spot_key')->nullable();
            $table->double('discount_amount')->default(0);
            $table->string('discount_start_at')->nullable();
            $table->string('discount_expire_at')->nullable();
            $table->string('discount_start_at_jalali')->nullable();
            $table->string('discount_expire_at_jalali')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('percent')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('online_courses');
    }
};
