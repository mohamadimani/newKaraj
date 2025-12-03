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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('course_register_id')->nullable();
            $table->text('comment')->nullable();
            $table->enum('star', [1, 2, 3, 4, 5])->nullable();
            $table->enum('q_1', [1, 2, 3, 4, 5])->nullable();
            $table->string('q_1_comment')->nullable();
            $table->enum('q_2', [1, 2, 3, 4, 5])->nullable();
            $table->string('q_2_comment')->nullable();
            $table->enum('q_3', [1, 2, 3, 4, 5])->nullable();
            $table->string('q_3_comment')->nullable();
            $table->enum('q_4', [1, 2, 3, 4, 5])->nullable();
            $table->string('q_4_comment')->nullable();
            $table->boolean('yes_no_q_1')->nullable();
            $table->boolean('yes_no_q_2')->nullable();
            $table->boolean('yes_no_q_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
