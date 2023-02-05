<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardBuilder extends Model
{
    use HasFactory;

    protected $table = 'dashboard_builder';

    protected $guarded = ['id'];
}
