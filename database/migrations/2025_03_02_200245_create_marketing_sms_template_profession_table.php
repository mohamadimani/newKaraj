<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE marketing_sms_templates ENGINE = InnoDB;');
        DB::statement('ALTER TABLE professions ENGINE = InnoDB;');
        Schema::create('marketing_sms_template_profession', function (Blueprint $table) {
            $table->bigInteger('template_id')->unsigned();
            $table->bigInteger('profession_id')->unsigned();

            $table->foreign('template_id')
                ->references('id')
                ->on('marketing_sms_templates')
                ->cascadeOnDelete();

            $table->foreign('profession_id')
                ->references('id')
                ->on('professions')
                ->cascadeOnDelete();
        });
        DB::statement('ALTER TABLE marketing_sms_template_profession ENGINE = InnoDB;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_sms_template_profession');
    }
};
