<?php

use App\Enums\Payment\StatusEnum;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paid_amount');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', StatusEnum::values())->default(StatusEnum::PENDING);
            $table->morphs('paymentable');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->date('pay_date');
            $table->text('description')->nullable();
            $table->text('reject_description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
