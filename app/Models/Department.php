<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $primaryKey = 'department_id';

    protected $guarded = ['department_id'];

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'department_branch_id');
    }
}
