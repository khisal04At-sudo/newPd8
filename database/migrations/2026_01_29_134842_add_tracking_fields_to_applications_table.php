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
        Schema::table('applications', function (Blueprint $table) {
            $table->integer('attended_hours')->default(0)->after('status');
            $table->integer('commitment_score')->nullable()->after('attended_hours');
            $table->text('evaluation_notes')->nullable()->after('commitment_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['attended_hours', 'commitment_score', 'evaluation_notes']);
        });
    }
};
