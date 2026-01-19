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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->onDelete('cascade');

            $table->string('file_name');
            $table->string('file_type');//pdf , docx, jpg , png ...
            $table->text('file_url'); // storage plance
            $table->enum('file_category', ['cv', 'avatar', 'certificate', 'verification_document', 'other'])->default('other');
            $table->bigInteger('file_size');
            $table->text('meta')->nullable(); // data اضافية  ~~ JSON  !

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
