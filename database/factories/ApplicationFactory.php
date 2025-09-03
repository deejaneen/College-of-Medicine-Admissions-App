<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Application::class;
    public function definition(): array
    {
        return [
            // Personal Information
            'emailAddress' => fake()->unique()->safeEmail(),
            'lastName' => fake()->lastName(),
            'firstName' => fake()->firstName(),
            'middleName' => fake()->lastName(),
            'dateOfBirth' => fake()->dateTimeBetween('-30 years', '-16 years'),
            'citizenship' => fake()->randomElement(['Filipino', 'Dual Citizenship']),
            'permanentHomeAddress' => fake()->address(),
            'municipality' => fake()->city(),
            'province' => fake()->state(),
            'zipCode' => fake()->postcode(),
            'activeGmailAccount' => fake()->unique()->userName() . '@gmail.com',
            'cellphoneNumber' => fake()->phoneNumber(),
            'civilStatus' => fake()->randomElement(['Single', 'Married', 'Widowed', 'Separated']),
            'sex' => fake()->randomElement(['Male', 'Female']),
            'religion' => fake()->randomElement(['Catholic', 'Christian', 'Islam', 'Iglesia ni Cristo', 'Born Again', 'Seventh-day Adventist']),

            // Religious Group
            'memberOfReligiousGroup' => fake()->randomElement(['yes', 'no']),
            'isMemberOfReligiousGroup' => function (array $attributes) {
                return $attributes['memberOfReligiousGroup'] === 'yes'
                    ? fake()->randomElement(['Catholic Youth Organization', 'Youth for Christ', 'Muslim Youth Association', 'INC Youth', 'Adventist Youth'])
                    : 'N/A';
            },

            // Physical Disability
            'physicalDisability' => fake()->randomElement(['yes', 'no']),
            'hasPhysicalDisability' => function (array $attributes) {
                return $attributes['physicalDisability'] === 'yes'
                    ? fake()->randomElement(['Visual Impairment', 'Hearing Impairment', 'Mobility Impairment', 'Speech Impairment'])
                    : 'N/A';
            },

            // Educational Background - Bachelors
            'bachelorsDegree' => fake()->randomElement(['BS Information Technology', 'BS Computer Science', 'BS Nursing', 'BS Education', 'BS Biology', 'BS Psychology', 'BS Criminology']),
            'nameOfSchoolBachelorsDegree' => fake()->company() . ' ' . fake()->randomElement(['College', 'University', 'Institute']),
            'gwaforBachelorsDegree' => fake()->randomFloat(2, 1.0, 1.5), // Assuming GWA scale 1.0-5.0
            'trascriptOfRecords' => 'transcripts/' . fake()->uuid() . '.pdf',

            // Educational Background - Graduate Studies
            'graduateStudies' => fake()->randomElement(['N/A', 'Master of Arts', 'Master of Science', 'PhD']),
            'graduateStudiesSchool' => function (array $attributes) {
                return $attributes['graduateStudies'] !== 'N/A'
                    ? fake()->company() . ' ' . fake()->randomElement(['College', 'University', 'Institute'])
                    : 'N/A';
            },
            'dateOrPossibleDateOfGrad' => function (array $attributes) {
                return $attributes['graduateStudies'] !== 'N/A'
                    ? fake()->dateTimeBetween('2000-01-01', '+1 year')->format('m/d/Y')
                    : 'N/A';
            },
            'trascriptOfRecordsGraduate' => function (array $attributes) {
                return $attributes['graduateStudies'] !== 'N/A'
                    ? 'grad_transcripts/' . fake()->uuid() . '.pdf'
                    : 'N/A';
            },

            // Family Information
            'fatherLastName' => fake()->lastName(),
            'fatherMiddleName' => fake()->lastName(),
            'fatherFirstName' => fake()->firstName(),
            'fatherOccupation' => fake()->jobTitle(),
            'motherLastName' => fake()->lastName(),
            'motherMiddleName' => fake()->lastName(),
            'motherFirstName' => fake()->firstName(),
            'motherOccupation' => fake()->jobTitle(),
            'guardianLastName' => fake()->lastName(),
            'guardianMiddleName' => fake()->lastName(),
            'guardianFirstName' => fake()->firstName(),
            'guardianOccupation' => fake()->jobTitle(),
            'combinedAnnualIncomeOfFamily' => fake()->randomElement([
                'P 00.00 - P 300 000.00',
                'P 301 000.00 - P 450 000.00',
                'P 451 000.00 - P 700 000.00',
                'P 701 000.00 - above'
            ]),

            // NMAT Information
            'nmatNationalMedicalAdmissionTestScore' => fake()->optional(0.3)->numberBetween(40, 99), // 30% chance of having NMAT score
            'nmatNationalMedicalAdmissionTestCertification' => function (array $attributes) {
                return $attributes['nmatNationalMedicalAdmissionTestScore'] !== null
                    ? 'nmat_certificates/' . fake()->uuid() . '.pdf'
                    : null;
            },
            'dateOfNmatNationalMedicalAdmissionTestExamination' => function (array $attributes) {
                return $attributes['nmatNationalMedicalAdmissionTestScore'] !== null
                    ? fake()->dateTimeBetween('-1 year', 'now')
                    : null;
            },

            // Community Involvement
            'parentCommunityHealthVolunteer' => fake()->randomElement(['yes', 'no']),
            'isParentCommunityHealthVolunteer' => function (array $attributes) {
                return $attributes['parentCommunityHealthVolunteer'] === 'yes'
                    ? fake()->randomElement(['Red Cross', 'Barangay Health Workers', 'Medical Mission', 'Community Outreach Program'])
                    : 'N/A';
            },

            // Essay/Reason
            'whyChooseSouthernLuzonStateUniversity' => fake()->paragraphs(3, true),
        ];
    }
}
