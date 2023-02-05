<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RoleHas extends Model
{
    use HasFactory;

    protected $table = 'role_has_permissions';

    protected $fillable =  ['permission_id', 'role_id'];
}
