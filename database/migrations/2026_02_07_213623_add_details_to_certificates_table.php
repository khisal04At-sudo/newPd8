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
        Schema::table('certificates', function (Blueprint $table) {
            $table->decimal('attendance_percentage', 5, 2)->nullable()->after('issue_date');
            $table->integer('total_hours')->nullable()->after('attendance_percentage');
            $table->integer('attended_hours')->nullable()->after('total_hours');
            $table->string('organization_name')->nullable()->after('attended_hours');
            $table->string('opportunity_title')->nullable()->after('organization_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_percentage',
                'total_hours',
                'attended_hours',
                'organization_name',
                'opportunity_title'
            ]);
        });
    }
};
