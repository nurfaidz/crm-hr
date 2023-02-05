<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';

    public function event()
    {
        return $this->hasOne('App/Event');
    }
    
    protected $fillable = [ 'nama',  'alamat', 'nohp', 'email', 'facebook', 'instagram', 'whatsapp', 'website', 'company'];
}
