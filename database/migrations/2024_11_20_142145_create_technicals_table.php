<?php

use App\Enums\Technical\StatusEnum;
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
        Schema::create('technicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_register_id')->constrained('course_registers')->cascadeOnDelete();
            $table->timestamp('written_exam_date')->nullable();
            $table->float('written_exam_number')->nullable();
            $table->float('written_exam_number2')->nullable();
            $table->string('written_description')->nullable();
            $table->timestamp('practical_exam_date')->nullable();
            $table->float('practical_exam_number')->nullable();
            $table->float('practical_exam_number2')->nullable();
            $table->float('practical_exam_number3')->nullable();
            $table->string('practical_description')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::PROCESSING);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicals');
    }
};
