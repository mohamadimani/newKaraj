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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->double('total_amount');
            $table->integer('discount_id')->nullable();
            $table->double('discount_amount')->default(0);
            $table->double('final_amount');
            $table->integer('payment_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->string('pay_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
