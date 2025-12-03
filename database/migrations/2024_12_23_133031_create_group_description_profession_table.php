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
        Schema::create('group_description_profession', function (Blueprint $table) {
            $table->foreignId('group_description_id')->constrained('group_descriptions')->onDelete('cascade');
            $table->foreignId('profession_id')->constrained('professions')->onDelete('cascade');
            $table->integer('sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_description_profession');
    }
};
