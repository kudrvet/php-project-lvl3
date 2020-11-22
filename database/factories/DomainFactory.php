<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomDateTime = $this->faker->dateTimeThisMonth;
        return [
            'name' => "http://{$this->faker->domainName}",
            'updated_at' => $randomDateTime,
            'created_at'=> $randomDateTime,
        ];
    }
}