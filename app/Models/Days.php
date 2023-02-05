<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    use HasFactory;

    protected $table = 'days';

    protected $guarded = ['days_id'];

    public function workdays()
    {
        return $this->belongsTo(Workdays::class);
    }
}
