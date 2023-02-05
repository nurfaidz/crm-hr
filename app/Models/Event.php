<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    public function customer()
    {
        return $this->belongsTo('App/Cusomer');
    }
    
    protected $fillable = [ 'start',  'nama_event', 'tempat_event', 'finish'];
}



                        