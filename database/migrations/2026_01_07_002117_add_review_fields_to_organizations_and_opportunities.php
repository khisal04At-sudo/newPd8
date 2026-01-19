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
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('auto_publish_opportunities')->default(false)->after('verified');
        });

        Schema::table('opportunities', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('auto_publish_opportunities');
        });

        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn('admin_notes');
        });
    }
};
