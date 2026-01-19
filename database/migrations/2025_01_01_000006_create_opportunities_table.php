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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('title', 100);
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('address');
            $table->enum('type', ['volunteering', 'training']);
            $table->integer('total_hours');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');

            $table->integer('seats');
      //   ( 0 = قبل النشر، 1 = منشورة، 9 = مغلقة، 8 = ملغاة)

            $table->text('requires_certification');

            $table->smallInteger('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
