<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReimbursement extends Model
{
    use HasFactory;

    protected $guarded = ['medical_reimbursement_id'];
}
