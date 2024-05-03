<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => \fake()->name(),
            'email' => \fake()->unique()->safeEmail(),
            'email_verified_at' => \now(),
            'password' => '12345',
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function customUser(string $name, string $email, string $password, string|array $role)
    {
        return $this
            ->state(fn (array $attributes) => [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ])
            ->afterCreating(function (User $user)  use ($role) {
                $user->assignRole($role);
            });
    }
}
