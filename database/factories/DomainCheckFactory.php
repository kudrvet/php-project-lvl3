<?php

namespace Database\Factories;

use App\Models\DomainCheck;
use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class DomainCheckFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DomainCheck::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomDateTime = $this->faker->dateTimeThisMonth;

        return [
            'domain_id' => Domain::factory(),
            'status_code' => $this->faker->randomElement([200,302,null]),
            'keywords' => $this->faker->text(),
            'description' => $this->faker->text(),
            'updated_at' => $randomDateTime,
            'created_at' => $randomDateTime,
        ];
    }
}
