<?php

namespace App\Rules;

use App\Models\Attendance;
use Illuminate\Contracts\Validation\Rule;

class SickRule implements Rule
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
        $attendance = Attendance::whereDate('date', '=', $value)->first();
        return (!$attendance) ?  true : $attendance->status != 'asc';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are getting sick';
    }
}
