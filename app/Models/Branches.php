<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;

    public $table = 'branches';

    protected $primaryKey = 'branch_id';

    protected $fillable = ['branch_name',  'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function department()
    {
        return $this->hasMany(Department::class, 'department_branch_id');
    }
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
