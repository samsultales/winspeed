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
        Schema::table('trn_file_sound', function (Blueprint $table) {
            if (!Schema::hasColumn('trn_file_sound', 'transkrip')) {
                $table->smallInteger('transkrip')->nullable()->default(0);
            }
            if (!Schema::hasColumn('trn_file_sound', 'transkrip_text')) {
                $table->text('transkrip_text')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kolom tidak di-drop karena mungkin sudah ada sebelum migration ini
    }
};
