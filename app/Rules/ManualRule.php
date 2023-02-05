<?php

namespace App\Rules;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ManualRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $employeeId = Auth::user()->employee->employee_id;
        $attendance = Attendance::where('employee_id', '=', $employeeId)
                                ->whereDate('date', '=', $value)
                                ->first();
        return (!$attendance) ?  true : false;

        // dd($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You've sent manual attendance";
    }
}
