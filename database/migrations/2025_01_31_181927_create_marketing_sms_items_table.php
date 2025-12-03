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
        Schema::create('marketing_sms_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_sms_template_id')->constrained('marketing_sms_templates')->cascadeOnDelete();
            $table->unsignedBigInteger('after_time');
            $table->text('content');
            $table->json('include_params')->nullable();
            $table->json('after_time_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_sms_items');
    }
};
