<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Application;
use App\Models\SchoolYear;
use App\Models\User;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        // Get a random user ID or create a new user and get its ID
        $userId = User::inRandomOrder()->value('id') ?? User::factory()->create()->id;

        // Get a random school year ID or create a new one
        $schoolYearId = SchoolYear::inRandomOrder()->value('id') ?? SchoolYear::factory()->create()->id;

        // Ensure (user_id, school_year_id) is unique
        while (Application::where('user_id', $userId)
                        ->where('school_year_id', $schoolYearId)
                        ->exists()) {
            $schoolYearId = SchoolYear::factory()->create()->id;
        }

        return [
            'user_id' => $userId,
            'school_year_id' => $schoolYearId,

            // Personal Information - CHANGED TO SNAKE_CASE
            'email_address' => $this->faker->unique()->safeEmail(),
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->date(),
            'citizenship' => 'Filipino',
            'permanent_home_address' => $this->faker->address(),
            'municipality' => $this->faker->city(),
            'province' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'active_gmail_account' => $this->faker->safeEmail(),
            'cellphone_number' => $this->faker->phoneNumber(),
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed']),
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'religion' => $this->faker->randomElement(['Catholic', 'Christian', 'Muslim', 'Iglesia ni Cristo', 'Others']),

            // Indigenous Group and Physical Disability - CHANGED TO SNAKE_CASE
            'indigenous_group' => $this->faker->randomElement(['YES', 'NO']),
            'indigenous_group_specify' => $this->faker->randomElement([null, 'Aeta', 'Mangyan', 'Ifugao']),
            'physical_disability' => $this->faker->randomElement(['YES', 'NO']),
            'physical_disability_specify' => $this->faker->randomElement([null, 'Visual impairment', 'Hearing impairment']),
            'community_health_volunteer' => $this->faker->randomElement(['YES', 'NO']),
            'community_health_organization' => $this->faker->randomElement([null, 'Barangay Health Workers']),

            // Educational Background - Bachelors - CHANGED TO SNAKE_CASE
            'bachelors_degree' => $this->faker->randomElement(['BS Biology', 'BS Nursing', 'BS Psychology', 'BS Medical Technology']),
            'name_of_school_bachelors_degree' => $this->faker->company() . " University",
            'gwa_for_bachelors_degree' => $this->faker->randomFloat(2, 1.00, 5.00),
            'bachelors_transcript_of_records' => ['uploads/tor/sample.pdf'],

            // Educational Background - Graduate Studies - CHANGED TO SNAKE_CASE
            'graduate_studies' => $this->faker->randomElement([null, 'MS Biology', 'MA Education']),
            'name_of_school_graduate_studies' => $this->faker->randomElement([null, $this->faker->company() . " University"]),
            'date_of_graduation' => $this->faker->randomElement([null, $this->faker->date()]),
            'graduate_studies_transcript_of_records' => $this->faker->randomElement([null, ['uploads/tor/graduate/sample.pdf']]),

            // Family Information - CHANGED TO SNAKE_CASE
            'father_first_name' => $this->faker->firstName(),
            'father_middle_name' => $this->faker->lastName(),
            'father_last_name' => $this->faker->lastName(),
            'father_occupation' => $this->faker->jobTitle(),
            'mother_first_name' => $this->faker->firstName(),
            'mother_middle_name' => $this->faker->lastName(),
            'mother_last_name' => $this->faker->lastName(),
            'mother_occupation' => $this->faker->jobTitle(),
            'guardian_first_name' => $this->faker->randomElement([null, $this->faker->firstName()]),
            'guardian_middle_name' => $this->faker->randomElement([null, $this->faker->lastName()]),
            'guardian_last_name' => $this->faker->randomElement([null, $this->faker->lastName()]),
            'guardian_occupation' => $this->faker->randomElement([null, $this->faker->jobTitle()]),
            'combined_annual_income_of_family' => $this->faker->randomElement([
                'P 00.00 - P 300 000.00',
                'P 301 000.00 - P 450 000.00',
                'P 451 000.00 - P 700 000.00',
                'P 701 000.00 - above'
            ]),

            // NMAT - CHANGED TO SNAKE_CASE
            'nmat_score' => $this->faker->numberBetween(200, 800),
            'nmat_certification' => ['uploads/nmat/sample.pdf'],
            'date_of_nmat_examination' => $this->faker->date(),

            // Essay - CHANGED TO SNAKE_CASE
            'why_choose_southern_luzon_state_university' => $this->faker->paragraph(3),

            // Application Status - CHANGED TO SNAKE_CASE
            'application_status' => 'For Review',
            // 'school_year_id' => SchoolYear::factory(),
        ];
    }
}