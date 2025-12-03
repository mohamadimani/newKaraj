<?php

use App\Enums\CourseRegister\StatusEnum;
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
        Schema::create('course_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('internal_branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('secretary_id')->constrained('secretaries')->onDelete('cascade');
            $table->enum('status', StatusEnum::values())->default(StatusEnum::REGISTERED);
            $table->unsignedBigInteger('paid_amount')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_registers');
    }
};
