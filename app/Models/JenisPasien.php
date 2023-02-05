<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPasien extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jenis_pasien';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_jenis_pasien';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
}
