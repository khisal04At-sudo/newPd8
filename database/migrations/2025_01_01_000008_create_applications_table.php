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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->foreignId('resum_file_id')->constrained('files')->onDelete('cascade');
            $table->foreignId('decision_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('cover_letter')->nullable();
            $table->string('status', 50)->default('pending');
            // تواريخ
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('decision_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
