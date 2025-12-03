<?php

use App\Enums\OnlinePayment\StatusEnum;
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
        Schema::create('online_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->double('amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::PENDING);
            $table->boolean('pay_confirm')->default(false);
            $table->string('token')->nullable();
            $table->string('bank_error')->nullable();
            $table->string('bank_error_code')->nullable();
            $table->string('RRN')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payments');
    }
};
