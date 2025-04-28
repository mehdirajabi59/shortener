<?php

namespace Database\Factories;

use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(TransactionTypeEnum::cases()),
            'amount' => $this->faker->randomElement([$this->faker->numerify('#00000'), $this->faker->numerify('##000000'), $this->faker->numerify('###000000')]),
        ];
    }
}
