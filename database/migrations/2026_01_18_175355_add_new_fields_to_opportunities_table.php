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
            // New fields for multi-step form
            $table->string('subcategory')->nullable()->after('category');
            $table->string('activity_type')->nullable()->after('subcategory');
            $table->string('execution_method')->nullable()->after('activity_type'); // in_person, remote
            $table->date('application_deadline')->nullable()->after('end_date');
            
            // Volunteer-specific fields
            $table->string('volunteer_type')->nullable()->after('type'); // individual, group
            
            // Training-specific fields
            $table->string('training_field')->nullable()->after('training_outcomes');
            $table->string('training_duration')->nullable()->after('training_field');
            $table->string('is_certified')->nullable()->after('training_duration'); // yes, no
            $table->string('is_paid')->nullable()->after('is_certified'); // yes, no
            
            // Applicant requirements
            $table->string('education_level')->nullable()->after('skills_requirement');
            $table->string('previous_experience')->nullable()->after('education_level'); // yes, no
            $table->text('additional_requirements')->nullable()->after('previous_experience');
            
            // Certificate fields
            $table->string('provides_certificate')->nullable()->after('requires_certification'); // yes, no
            $table->string('requires_cover_letter')->nullable()->after('provides_certificate'); // yes, no
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'subcategory',
                'activity_type',
                'execution_method',
                'application_deadline',
                'volunteer_type',
                'training_field',
                'training_duration',
                'is_certified',
                'is_paid',
                'education_level',
                'previous_experience',
                'additional_requirements',
                'provides_certificate',
                'requires_cover_letter'
            ]);
        });
    }
};
