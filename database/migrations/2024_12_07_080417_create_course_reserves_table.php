<?php

use App\Enums\CourseReserve\StatusEnum;
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
        Schema::create('course_reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clue_id')->constrained('clues')->cascadeOnDelete();
            $table->foreignId('profession_id')->constrained('professions')->cascadeOnDelete();
            $table->foreignId('secretary_id')->constrained('secretaries')->cascadeOnDelete();
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->string('description')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::PENDING);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_reserves');
    }
};
