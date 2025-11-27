<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
       // Step 1: Personal Information
        'emailAddress',
        'verifyEmailAddress',
        'lastName',
        'firstName',
        'middleName',
        'dateOfBirth',
        'citizenship',
        'permanentHomeAddress',
        'municipality',
        'province',
        'zipCode',
        'activeGmailAccount',
        'cellphoneNumber',
        'civilStatus',
        'sex',
        'religion',
        'indigenousGroup',
        'indigenousGroupSpecify',
        'physicalDisability',
        'physicalDisabilitySpecify',

        // Step 2: Educational Background - Bachelors
        'bachelorsDegree',
        'nameOfSchoolBachelorsDegree',
        'gwaforBachelorsDegree',
        'trascriptOfRecords',

        // Step 3: Family Information
        'fatherLastName',
        'fatherMiddleName',
        'fatherFirstName',
        'fatherOccupation',
        'motherLastName',
        'motherMiddleName',
        'motherFirstName',
        'motherOccupation',
        'combinedAnnualIncomeOfFamily',
        'communityHealthVolunteer',
        'communityHealthOrganization',

        // Step 4: NMAT Information
        'nmatNationalMedicalAdmissionTestScore',
        'nmatNationalMedicalAdmissionTestCertification',
        'dateOfNmatNationalMedicalAdmissionTestExamination',

        // Step 5: Essay/Reason
        'whyChooseSouthernLuzonStateUniversity',

        //Application status
        'applicationStatus',
    ];
     protected $attributes = [
        'applicationStatus' => 'For Review'
    ];
    protected $hidden = [
        // Add any attributes you want to hide from JSON/array output
        // For example: 'password', 'remember_token', etc.
    ];
}
