<?php

namespace Modules\Pendaftaran\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pendaftaran\Models\Registration;

class RegistrationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Registration::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'program' => fake()->words(2, true),
            'status' => 'pending',
        ];
    }
}
