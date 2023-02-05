<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotaKab extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kota_kab';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_kotakab';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function rumahsakit()
    {
        return $this->hasMany(RumahSakit::class);
    }
    
}
