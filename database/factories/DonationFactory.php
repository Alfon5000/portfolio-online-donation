<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'donation_code' => 'SANDBOX-' . uniqid(),
            'donor_name' => fake()->name(),
            'donor_email' => fake()->safeEmail(),
            'donation_type' => Arr::random(Donation::TYPES),
            'amount' => fake()->numberBetween(100000, 100000000),
            'note' => fake()->sentence(),
            'status' => Arr::random(Donation::STATUSES),
        ];
    }
}
