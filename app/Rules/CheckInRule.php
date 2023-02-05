<?php

namespace App\Rules;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckInRule implements Rule
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
        $attendance = Attendance::whereDate('date', '=', $value)
                                ->where('employee_id', '=', $employeeId)
                                ->first();
        return (!$attendance) ?  true : $attendance->status != 'acw';
        // return $attendance->status != 'acw';
        // return dd($attendance);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You've already checked in";
    }
}
