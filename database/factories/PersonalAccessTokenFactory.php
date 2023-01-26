<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PersonalAccessTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'token' => Str::random(10),
            'abilities' => ['*'],
            'expires_at' => now(),
            'last_used_at' => now(),
            'tokenable_id' => User::factory()->create()->id,
            'tokenable_type' => 'App\Models\User',
        ];
    }
}
