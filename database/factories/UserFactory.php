<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => function() {
                $email = [null, 'superadmin@gmail.com', 'worker@gmail.com', 'manager@gmail.com', 'humanresources@gmail.com', 'finance@gmail.com'];
                $i = User::max('id');

                return $email[$i + 1];
            },
            'password' => bcrypt('password')
        ];
    }
}
