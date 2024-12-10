<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $n = fake()->numberBetween(1, 34);
        $roleRate = fake()->numberBetween(1, 2);
        $role = $roleRate === 1 ? 'manager' : 'member';
        $name = $role .' '. fake()->userName();
        $email = $role . fake()->unique()->safeEmail();
        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => $role,
            'remember_token' => Str::random(10),
            'avatar' => '/assets/images/profile-' . $n . '.jpeg',
            'department_id' => Department::factory(),
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
