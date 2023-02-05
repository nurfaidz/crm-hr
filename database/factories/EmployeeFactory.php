<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                $i = Employee::max('user_id');

                return $i + 1;
            },
            'first_name' => function () {
                $firstName = [null];
                $i = Employee::max('employee_id');

                for ($x = 0; $x < 5; $x++) {
                    array_push($firstName, 'Dummy');
                }

                return $firstName[$i + 1];
            },
            'last_name' => function () {
                $lastName = [null, 'Super Admin', 'Worker', 'Manager', 'Human Resources', 'Finance'];
                $i = Employee::max('employee_id');

                return $lastName[$i + 1];
            },
            'place_of_birth' => $this->faker->city,
            'date_of_birth' => $this->faker->date,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'nik' => $this->faker->randomNumber(9) . $this->faker->randomNumber(7),
            'religion_id' => $this->faker->numberBetween(1, 6),
            'marital_status_id' => $this->faker->numberBetween(1, 5),
            'address' => $this->faker->address,
            'postal_code' => $this->faker->postcode,
            'phone' => $this->faker->numerify('#############'),
            'company_id' => 1,
            'branch_id' => 1,
            'department_id' => 1,
            'job_class_id' => 1,
            'job_position_id' => 1,
            'employment_status_id' => $this->faker->numberBetween(1, 4),
            'work_shift_id' => $this->faker->randomElement([1, 2]),
            'date_of_joining' => $this->faker->date,
            'status' => $this->faker->randomElement([1, 0]),
            'nip' => function () {
                $mNIP = $this->faker->dayOfMonth();
                $yNIP = $this->faker->year();

                $i = Employee::max('user_id');
                $i++;
                    
                return "{$mNIP}{$yNIP}{$i}-X";
            },
            'basic_salary' => $this->faker->randomNumber,
            'salary_type_id' => $this->faker->numberBetween(1, 5),
            'bank_id' => $this->faker->numberBetween(1, 15),
            'bank_account_number' => $this->faker->bankAccountNumber,
            'bank_account_holder' => $this->faker->name,
            'npwp' => $this->faker->randomNumber(9) . $this->faker->randomNumber(7),
            'image' => null,
            'nationality' => 'Indonesia'
        ];
    }
}
