<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingandLoan extends Model
{
    use HasFactory;

    protected $guarded = ['cooperative_id'];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
