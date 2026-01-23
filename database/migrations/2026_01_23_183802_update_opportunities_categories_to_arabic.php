<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Map English categories to Arabic
        $categoryMap = [
            'environment' => 'بيئة',
            'technology' => 'تكنولوجيا',
            'education' => 'تعليم',
            'health' => 'صحة',
            'help' => 'مساعدة إنسانية',
            'entrepreneurship' => 'ريادة أعمال',
            'sports' => 'رياضة',
            'arts' => 'فنون',
        ];

        // Update each category
        foreach ($categoryMap as $english => $arabic) {
            DB::table('opportunities')
                ->where('category', $english)
                ->update(['category' => $arabic]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Map Arabic categories back to English for rollback
        $categoryMap = [
            'بيئة' => 'environment',
            'تكنولوجيا' => 'technology',
            'تعليم' => 'education',
            'صحة' => 'health',
            'مساعدة إنسانية' => 'help',
            'ريادة أعمال' => 'entrepreneurship',
            'رياضة' => 'sports',
            'فنون' => 'arts',
        ];

        // Revert each category
        foreach ($categoryMap as $arabic => $english) {
            DB::table('opportunities')
                ->where('category', $arabic)
                ->update(['category' => $english]);
        }
    }
};
