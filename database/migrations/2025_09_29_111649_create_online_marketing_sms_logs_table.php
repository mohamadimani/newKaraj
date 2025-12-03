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
        Schema::create('online_marketing_sms_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('online_marketing_sms_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('target_type', ['clue', 'student'])->nullable();
            $table->string('mobile');
            $table->boolean('is_sent');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_marketing_sms_logs');
    }
};
