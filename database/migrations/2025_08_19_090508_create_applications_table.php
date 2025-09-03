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
            $table->timestamps();

            // Personal Information
            $table->string('emailAddress');
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName');
            $table->date('dateOfBirth');
            $table->string('citizenship');
            $table->text('permanentHomeAddress');
            $table->string('municipality');
            $table->string('province');
            $table->string('zipCode');
            $table->string('activeGmailAccount');
            $table->string('cellphoneNumber');
            $table->string('civilStatus');
            $table->string('sex');
            $table->string('religion');

            // Religious Group
            $table->enum('memberOfReligiousGroup', ['yes', 'no']);
            $table->string('isMemberOfReligiousGroup')->default('N/A');

            // Physical Disability
            $table->enum('physicalDisability', ['yes', 'no']);
            $table->string('hasPhysicalDisability')->default('N/A');

            // Educational Background - Bachelors
            $table->string('bachelorsDegree');
            $table->string('nameOfSchoolBachelorsDegree');
            $table->decimal('gwaforBachelorsDegree', 5, 2);
            $table->string('trascriptOfRecords'); // File path for uploaded file

            // Educational Background - Graduate Studies
            $table->string('graduateStudies')->default('N/A');
            $table->string('graduateStudiesSchool')->default('N/A');
            $table->string('dateOrPossibleDateOfGrad')->default('N/A');
            $table->string('trascriptOfRecordsGraduate')->default('N/A'); // File path

            // Family Information
            $table->string('fatherLastName');
            $table->string('fatherMiddleName');
            $table->string('fatherFirstName');
            $table->string('fatherOccupation');
            $table->string('motherLastName');
            $table->string('motherMiddleName');
            $table->string('motherFirstName');
            $table->string('motherOccupation');
            $table->string('guardianLastName');
            $table->string('guardianMiddleName');
            $table->string('guardianFirstName');
            $table->string('guardianOccupation');
            $table->enum('combinedAnnualIncomeOfFamily', [
                'P 00.00 - P 300 000.00',
                'P 301 000.00 - P 450 000.00',
                'P 451 000.00 - P 700 000.00',
                'P 701 000.00 - above'
            ]);

            // NMAT Information
            $table->integer('nmatNationalMedicalAdmissionTestScore')->nullable();
            $table->string('nmatNationalMedicalAdmissionTestCertification')->nullable(); // File path
            $table->date('dateOfNmatNationalMedicalAdmissionTestExamination')->nullable();

            // Community Involvement
            $table->enum('parentCommunityHealthVolunteer', ['yes', 'no']);
            $table->string('isParentCommunityHealthVolunteer')->default('N/A');

            // Essay/Reason
            $table->text('whyChooseSouthernLuzonStateUniversity');
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
