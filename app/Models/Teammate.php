<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teammate extends Model
{
    use HasFactory;

    public $table = 'teammate';

    protected $primaryKey = 'teammate_id';

    protected $fillable = [
        'teammate_id', 'user_id', 'project_id', 'department_id', 'whatsapp', 'status'
    ];
    

}
