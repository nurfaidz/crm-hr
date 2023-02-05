<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasDashboard extends Model
{
    use HasFactory;

    protected $table = 'user_has_dashboard';

    protected $guarded = ['id'];
}
