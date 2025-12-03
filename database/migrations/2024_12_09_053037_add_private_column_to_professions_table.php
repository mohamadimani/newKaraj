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
        Schema::table('professions', function (Blueprint $table) {
            $table->unsignedBigInteger('private_price')->nullable();
            $table->unsignedBigInteger('private_duration_hours')->nullable();
            $table->unsignedBigInteger('private_capacity')->nullable();
            $table->renameColumn('price', 'public_price');
            $table->renameColumn('duration_hours', 'public_duration_hours');
            $table->renameColumn('capacity', 'public_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professions', function (Blueprint $table) {
            $table->dropColumn('private_price');
            $table->dropColumn('private_duration_hours');
            $table->dropColumn('private_capacity');
            $table->renameColumn('public_price', 'price');
            $table->renameColumn('public_duration_hours', 'duration_hours');
            $table->renameColumn('public_capacity', 'capacity');
        });
    }
};
