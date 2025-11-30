<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('email_address');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->string('citizenship');
            $table->text('permanent_home_address');
            $table->string('municipality');
            $table->string('province');
            $table->string('zip_code');
            $table->string('active_gmail_account');
            $table->string('cellphone_number');
            $table->string('civil_status');
            $table->string('sex');
            $table->string('religion');
            $table->enum('indigenous_group', ['YES', 'NO']);
            $table->string('indigenous_group_specify')->nullable();
            $table->enum('physical_disability', ['YES', 'NO']);
            $table->string('physical_disability_specify')->nullable();
            $table->enum('community_health_volunteer', ['YES', 'NO']);
            $table->string('community_health_organization')->nullable();
            
            // Educational Background
            $table->string('bachelors_degree');
            $table->string('name_of_school_bachelors_degree');
            $table->decimal('gwa_for_bachelors_degree', 3, 2);
            $table->json('bachelors_transcript_of_records')->nullable();
            $table->string('graduate_studies')->nullable();
            $table->string('name_of_school_graduate_studies')->nullable();
            $table->date('date_of_graduation')->nullable();
            $table->json('graduate_studies_transcript_of_records')->nullable();
            
            // Family Information
            $table->string('father_first_name');
            $table->string('father_middle_name')->nullable();
            $table->string('father_last_name');
            $table->string('father_occupation')->nullable();
            $table->string('mother_first_name');
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_last_name');
            $table->string('mother_occupation')->nullable();
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_middle_name')->nullable();
            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('combined_annual_income_of_family');
            
            // NMAT Information
            $table->integer('nmat_score');
            $table->json('nmat_certification')->nullable();
            $table->date('date_of_nmat_examination');
            
            // Essay
            $table->text('why_choose_southern_luzon_state_university');
            
            // System fields
            $table->string('application_status')->default('For Review');
            
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->unique(['user_id', 'school_year_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};