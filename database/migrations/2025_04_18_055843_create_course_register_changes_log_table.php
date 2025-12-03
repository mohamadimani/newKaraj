<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseRegisterChangesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_register_change_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('course_register_id')->nullable(false);
            $table->string('field_name');
            $table->text('previous_value')->nullable();
            $table->text('new_value')->nullable();
            $table->integer('user_id')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_register_change_logs');
    }
}
