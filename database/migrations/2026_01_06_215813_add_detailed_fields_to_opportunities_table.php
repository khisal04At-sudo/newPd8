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
        Schema::table('opportunities', function (Blueprint $table) {
            $table->string('category')->default('help')->after('type'); // help, education, environment, entrepreneurship, sports, arts, health, technology
            $table->text('objectives')->nullable()->after('description');
            $table->text('tasks')->nullable()->after('objectives');
            $table->text('training_outcomes')->nullable()->after('tasks');
            $table->integer('daily_hours')->nullable()->after('total_hours');
            $table->string('age_requirement')->nullable()->after('seats');
            $table->text('skills_requirement')->nullable()->after('age_requirement');
            $table->boolean('is_practical')->default(false)->after('skills_requirement');
            $table->boolean('has_stipend')->default(false)->after('is_practical');
            $table->boolean('attendance_required')->default(false)->after('has_stipend');
            $table->boolean('pre_test_required')->default(false)->after('attendance_required');
            $table->string('contact_name')->nullable()->after('pre_test_required');
            $table->string('contact_info')->nullable()->after('contact_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'objectives', 'tasks', 'training_outcomes', 
                'daily_hours', 'age_requirement', 'skills_requirement', 
                'is_practical', 'has_stipend', 'attendance_required', 
                'pre_test_required', 'contact_name', 'contact_info'
            ]);
        });
    }
};
