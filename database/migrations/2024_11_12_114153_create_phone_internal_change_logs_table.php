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
        Schema::create('phone_internal_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_internal_id')->constrained()->onDelete('cascade');
            $table->foreignId('old_secretary_id')->nullable()->constrained('secretaries')->onDelete('cascade');
            $table->foreignId('new_secretary_id')->nullable()->constrained('secretaries')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_internal_change_logs');
    }
};
