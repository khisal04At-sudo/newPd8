<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('category', 50); // e.g. 'بيئة', 'تعليم', etc.
            $table->timestamps();

            $table->unique(['user_id', 'category']); // Prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_interests');
    }
};
