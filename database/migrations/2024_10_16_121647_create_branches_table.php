<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('site')->nullable();
            $table->string('bank_card_name')->nullable();
            $table->string('bank_card_number')->nullable();
            $table->string('bank_card_owner')->nullable();
            $table->string('manager')->nullable();
            $table->foreignId('province_id')->nullable()->constrained('provinces');
            $table->decimal('minimum_pay', 10, 2)->nullable();
            $table->string('online_pay_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
