<?php

namespace Database\Factories\api\v1;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function fake;
use function now;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\v1\User>
 */
class UserFactory extends Factory
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
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address'=>'fsdkbdhdlkhdglkhbjdglkhbdlh',
            'mobile'=>$this->faker->numberBetween(100000000000,99999999999),
            'postal_code'=>'sgfhdgjfhkfjhdfsghd',
            'province_id'=>1,
            'remember_token' => Str::random(10),
            'city_id'=>1,

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
