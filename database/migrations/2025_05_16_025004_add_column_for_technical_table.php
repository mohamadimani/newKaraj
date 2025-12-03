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
        Schema::table('technicals', function (Blueprint $table) {
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('paid_amount')->default(0);
            $table->integer('branch_id')->nullable();
            $table->string('amount_descreption')->nullable();
            $table->dropColumn('written_exam_date');
            $table->dropColumn('written_exam_number');
            $table->dropColumn('written_exam_number2');
            $table->dropColumn('written_description');
            $table->dropColumn('practical_exam_date');
            $table->dropColumn('practical_exam_number');
            $table->dropColumn('practical_exam_number2');
            $table->dropColumn('practical_exam_number3');
            $table->dropColumn('practical_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technicals', function (Blueprint $table) {
            $table->timestamp('written_exam_date')->nullable();
            $table->float('written_exam_number')->nullable();
            $table->float('written_exam_number2')->nullable();
            $table->string('written_description')->nullable();
            $table->timestamp('practical_exam_date')->nullable();
            $table->float('practical_exam_number')->nullable();
            $table->float('practical_exam_number2')->nullable();
            $table->float('practical_exam_number3')->nullable();
            $table->string('practical_description')->nullable();
            $table->dropColumn('course_id');
            $table->dropColumn('student_id');
            $table->dropColumn('paid_amount');
            $table->dropColumn('amount_descreption');
        });
    }
};
