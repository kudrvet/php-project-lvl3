<?php

namespace Database\Factories;

use App\Models\DomainCheck;
use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'updated_at' => $randomDateTime,
            'created_at'=> $randomDateTime,
        ];
    }
}
