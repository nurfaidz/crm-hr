<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePeriod extends Model
{
    use HasFactory;

    protected $table = 'leave_periods';

    protected $primaryKey = 'leave_period_id';

    protected $guarded = ['leave_period_id'];
}
