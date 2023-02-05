<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKomando extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_komando';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_subkomando';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
}
