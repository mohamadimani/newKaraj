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
        Schema::create('technical_exams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exam_date')->nullable();
            $table->bigInteger('exam_number')->nullable();
            $table->text('exam_description')->nullable();
            $table->enum('exam_type', ['written', 'practical'])->nullable();
            $table->integer('technical_id');
            $table->integer('technical_address_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_exams');
    }
};
