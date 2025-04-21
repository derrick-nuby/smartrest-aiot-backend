<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
<<<<<<< HEAD
            'email_verified_at' => now(),
=======
            'phone' => fake()->phoneNumber(),
>>>>>>> f945b34 (Initial commit with Docker support)
            'password' => static::$password ??= Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
<<<<<<< HEAD
        return $this->state(fn (array $attributes) => [
=======
        return $this->state(fn(array $attributes) => [
>>>>>>> f945b34 (Initial commit with Docker support)
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a specific role.
     */
    public function withRole(string $role): static
    {
        return $this->afterCreating(function (User $user) use ($role) {
            $user->assignRole($role);
        });
    }

    /**
     * Indicate that the user should have specific permissions.
     */
    public function withPermissions(array $permissions): static
    {
        return $this->afterCreating(function (User $user) use ($permissions) {
            $user->givePermissionTo($permissions);
        });
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user should be an admin.
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('admin');
        });
    }

    /**
     * Indicate that the user should be a super admin.
     */
    public function superAdmin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('super admin');
        });
    }
}