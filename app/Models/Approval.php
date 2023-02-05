<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;
    
    public $table = 'approval';

    protected $primaryKey = 'approval_id';

    protected $fillable = [ 'approval_id', 'tanggal_approval', 'user_id', 'keterangan', 'status'];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
