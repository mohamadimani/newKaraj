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
        Schema::create('phone_internals', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('number')->nullable();
            $table->foreignId('phone_id')->constrained('phones')->onDelete('cascade');
            $table->foreignId('secretary_id')->nullable()->constrained('secretaries')->onDelete('cascade');
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
        Schema::dropIfExists('phone_internals');
    }
};
