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
        Schema::create('order_item_change_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_item_id')->nullable(false);
            $table->string('field_name_en')->nullable();
            $table->string('field_name_fa')->nullable();
            $table->text('previous_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('description')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_change_logs');
    }
};
