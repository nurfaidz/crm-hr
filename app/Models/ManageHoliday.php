<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageHoliday extends Model
{
    use HasFactory;
    
    protected $guarded = ['holiday_id'];
}
