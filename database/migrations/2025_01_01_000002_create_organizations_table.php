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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('logo_url', 100)->nullable();
            
            $table->enum('organization_type', ['volunteering', 'training', 'both']);
            $table->enum('sector', [
                'private',      // قطاع خاص شركة او مؤسسة
                'public',       // مؤسسة قطاع عام (حكومي)
                'initiative',   // مبادرات و فرق تطوعية (غير مسجلة)
                'non_profit'    // مؤسسة او جمعية او شركة غير ربحية (مسجلة)
            ])->nullable();
            
            $table->string('registration_number', 100)->nullable();
            $table->date('established_at')->nullable();
            
            $table->integer('max_volunteers')->nullable();
            $table->json('services')->nullable();
            
            $table->float('rating')->default(0);
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            $table->json('social_links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
