<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->unique()->slug(2),
            'label' => ucfirst($this->faker->words(2, true)),
        ];
    }

    public function manageUsers(): static
    {
        return $this->state(fn () => [
            'name'  => 'manage-users',
            'label' => 'Manage Users',
        ]);
    }
}
