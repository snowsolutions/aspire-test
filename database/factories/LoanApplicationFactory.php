<?php

namespace Database\Factories;

use App\Models\Attributes\LoanApplication\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanApplication>
 */
class LoanApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(1000, 10000);

        return [
            'status' => Status::PENDING->name,
            'purpose' => fake()->text(100),
            'term' => fake()->numberBetween(3, 10),
            'amount' => $amount,
            'remaining_amount' => $amount,
            'user_id' => 1,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
