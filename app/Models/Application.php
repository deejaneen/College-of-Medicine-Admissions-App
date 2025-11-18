<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        // Personal Information
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

        // Religious Group
        'memberOfReligiousGroup',
        'isMemberOfReligiousGroup',

        // Physical Disability
        'physicalDisability',
        'hasPhysicalDisability',

        // Educational Background - Bachelors
        'bachelorsDegree',
        'nameOfSchoolBachelorsDegree',
        'gwaforBachelorsDegree',
        'trascriptOfRecords',

        // Educational Background - Graduate Studies
        'graduateStudies',
        'graduateStudiesSchool',
        'dateOrPossibleDateOfGrad',
        'trascriptOfRecordsGraduate',

        // Family Information
        'fatherLastName',
        'fatherMiddleName',
        'fatherFirstName',
        'fatherOccupation',
        'motherLastName',
        'motherMiddleName',
        'motherFirstName',
        'motherOccupation',
        'guardianLastName',
        'guardianMiddleName',
        'guardianFirstName',
        'guardianOccupation',
        'combinedAnnualIncomeOfFamily',

        // NMAT Information
        'nmatNationalMedicalAdmissionTestScore',
        'nmatNationalMedicalAdmissionTestCertification',
        'dateOfNmatNationalMedicalAdmissionTestExamination',

        // Community Involvement
        'parentCommunityHealthVolunteer',
        'isParentCommunityHealthVolunteer',

        // Essay/Reason
        'whyChooseSouthernLuzonStateUniversity'
    ];
    protected $hidden = [
        // Add any attributes you want to hide from JSON/array output
        // For example: 'password', 'remember_token', etc.
    ];
}
