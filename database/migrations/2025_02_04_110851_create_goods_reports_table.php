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
        Schema::create('goods_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('class_room_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('count')->nullable();
            $table->enum('health_status', ['good', 'damaged', 'not_exist'])->default('good');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_reports');
    }
};
