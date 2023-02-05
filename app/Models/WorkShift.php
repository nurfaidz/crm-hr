<?php

namespace App\Models;

use App\Models\Workdays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkShift extends Model
{
    use HasFactory;

    protected $table = 'work_shifts';

    protected $guarded = ['work_shift_id'];

    public function workdays()
    {
        return $this->hasMany(Workdays::class);
    }
}
