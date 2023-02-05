<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCovid extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_covid';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_covid';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
}
