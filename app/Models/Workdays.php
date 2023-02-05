<?php

namespace App\Models;

use App\Models\Days;
use App\Models\WorkShift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workdays extends Model
{
    use HasFactory;

    protected $table = 'workdays';

    protected $guarded = ['workdays_id'];

    public function days()
    {
        return $this->hasMany(Days::class, 'days_id');
    }

    public function workshift()
    {
        return $this->belongsTo(WorkShift::class, 'workshift_id');
    }
}
