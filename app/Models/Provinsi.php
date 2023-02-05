<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'provinsi';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_provinsi';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function angkatan()
    {
        return $this->hasMany(Angkatan::class);
    }
}
