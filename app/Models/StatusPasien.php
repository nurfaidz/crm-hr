<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPasien extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status_pasien';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_status_pasien';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
}
