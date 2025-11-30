<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SchoolYear;

class SchoolYearFactory extends Factory
{
    protected $model = SchoolYear::class;

    public function definition()
    {
        $year = $this->faker->unique()->numberBetween(2023, 2030);
        
        return [
            'school_year' => $year . '-' . ($year + 1),
            'application_deadline' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ];
    }
}