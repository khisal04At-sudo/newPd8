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
            $table->enum('status', ['pending', 'approved', 'rejected', 'needs_documents'])
                ->default('pending')
                ->after('verified');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->text('requested_documents')->nullable()->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason', 'requested_documents']);
        });
    }
};
