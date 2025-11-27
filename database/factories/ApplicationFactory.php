<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [

            // Personal Information
            'emailAddress' => $this->faker->unique()->safeEmail(),
            'verifyEmailAddress' => $this->faker->unique()->safeEmail(),
            'lastName' => $this->faker->lastName(),
            'firstName' => $this->faker->firstName(),
            'middleName' => $this->faker->lastName(),
            'dateOfBirth' => $this->faker->date(),
            'citizenship' => 'Filipino',
            'permanentHomeAddress' => $this->faker->address(),
            'municipality' => $this->faker->city(),
            'province' => $this->faker->state(),
            'zipCode' => $this->faker->postcode(),
            'activeGmailAccount' => $this->faker->safeEmail(),
            'cellphoneNumber' => $this->faker->phoneNumber(),
            'civilStatus' => $this->faker->randomElement(['Single', 'Married', 'Widowed']),
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'religion' => $this->faker->randomElement(['Catholic', 'Christian', 'Muslim', 'Iglesia ni Cristo', 'Others']),

            // Religious Group
            'memberOfReligiousGroup' => $this->faker->randomElement(['yes', 'no']),
            'isMemberOfReligiousGroup' => 'N/A',

            // Physical Disability
            'physicalDisability' => $this->faker->randomElement(['yes', 'no']),
            'hasPhysicalDisability' => 'N/A',

            // Educational Background - Bachelors
            'bachelorsDegree' => 'BS Biology',
            'nameOfSchoolBachelorsDegree' => $this->faker->company() . " University",
            'gwaforBachelorsDegree' => $this->faker->randomFloat(2, 1.00, 5.00),
            'trascriptOfRecords' => 'uploads/tor/sample.pdf',

            // Educational Background - Graduate Studies
            'graduateStudies' => 'N/A',
            'graduateStudiesSchool' => 'N/A',
            'dateOrPossibleDateOfGrad' => 'N/A',
            'trascriptOfRecordsGraduate' => 'N/A',

            // Family Information
            'fatherLastName' => $this->faker->lastName(),
            'fatherMiddleName' => $this->faker->lastName(),
            'fatherFirstName' => $this->faker->firstName(),
            'fatherOccupation' => $this->faker->jobTitle(),
            'motherLastName' => $this->faker->lastName(),
            'motherMiddleName' => $this->faker->lastName(),
            'motherFirstName' => $this->faker->firstName(),
            'motherOccupation' => $this->faker->jobTitle(),
            'guardianLastName' => $this->faker->lastName(),
            'guardianMiddleName' => $this->faker->lastName(),
            'guardianFirstName' => $this->faker->firstName(),
            'guardianOccupation' => $this->faker->jobTitle(),
            'combinedAnnualIncomeOfFamily' => $this->faker->randomElement([
                'P 00.00 - P 300 000.00',
                'P 301 000.00 - P 450 000.00',
                'P 451 000.00 - P 700 000.00',
                'P 701 000.00 - above'
            ]),

            // NMAT
            'nmatNationalMedicalAdmissionTestScore' => $this->faker->optional()->numberBetween(200, 800),
            'nmatNationalMedicalAdmissionTestCertification' => 'uploads/nmat/sample.pdf',
            'dateOfNmatNationalMedicalAdmissionTestExamination' => $this->faker->optional()->date(),

            // Community Involvement
            'parentCommunityHealthVolunteer' => $this->faker->randomElement(['yes', 'no']),
            'isParentCommunityHealthVolunteer' => 'N/A',

            // Essay
            'whyChooseSouthernLuzonStateUniversity' => $this->faker->paragraph(3),

            // Application Status
            'applicationStatus' => 'For Review',
        ];
    }
}
