<?php

use App\Enums\MarketingSms\TargetTypeEnum;
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
        Schema::create('online_marketing_sms', function (Blueprint $table) {
            $table->id();
            $table->integer('online_course_id')->nullable();
            $table->enum('target_type', ['clue', 'student'])->nullable();
            $table->integer('after_time')->nullable();
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_marketing_sms');
    }
};
