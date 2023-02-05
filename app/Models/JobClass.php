<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobClass extends Model
{
    use HasFactory;

    protected $guarded = ['job_class_id'];
}
