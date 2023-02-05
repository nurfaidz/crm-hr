<?php

namespace Database\Factories;

use App\Models\Customer;
use Carbon\Factory as CarbonFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class CustomerFactory extends CarbonFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'nama' => $this->faker->name,
                'alamat' => $this->faker->address,
                'nohp' => $this->faker->PhoneNumber,
                'email' => $this->faker->freeEmail,
                'facebook' => $this->faker->freeEmail,
                'instagram' => $this->faker->freeEmail,
                'whatsapp' => $this->faker->phoneNumber,
                'company' => $this->faker->company,
        ];
    }
    }
