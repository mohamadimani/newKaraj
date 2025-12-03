<?php

use App\Enums\Discount\AmountTypeEnum;
use App\Enums\Discount\DiscountTypeEnum;
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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('minimum_order_amount')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->enum('amount_type', AmountTypeEnum::values())->default(AmountTypeEnum::PERCENTAGE);
            $table->enum('discount_type', DiscountTypeEnum::values())->default(DiscountTypeEnum::PUBLIC);
            $table->bigInteger('profession_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_online')->default(false);
            $table->integer('used_count')->default(0);
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
