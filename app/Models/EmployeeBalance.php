<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'total_balance',
        'remaining_balance',
        'used_balance',
    ];
}
