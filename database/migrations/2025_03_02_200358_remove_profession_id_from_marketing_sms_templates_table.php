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
        DB::table('marketing_sms_templates')->get()->each(function ($template) {
            DB::table('marketing_sms_template_profession')->insert([
                'template_id' => $template->id,
                'profession_id' => $template->profession_id,
            ]);
        });
        Schema::table('marketing_sms_templates', function (Blueprint $table) {
            $table->dropColumn('profession_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_sms_templates', function (Blueprint $table) {
            $table->foreignId('profession_id')->constrained('professions')->cascadeOnDelete();
        });
    }
};
