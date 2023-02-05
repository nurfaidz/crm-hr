<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateMealTransport extends Model
{
    use HasFactory;
    public $table = 'late_mealtrans';
    protected $guarded = ['id'];

    // public static $rules = [
    //     'start_minutes' => 'required|numeric|min:0|max:59',
    //     'percentage' => 'required|numeric|min:0|max:100'
    // ];
}
