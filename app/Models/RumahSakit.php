<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumahSakit extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rs';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_rs';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'id_angkatan', 'id_angkatan');
    }

    public function kotakab()
    {
        return $this->belongsTo(KotaKab::class, 'id_kotakab', 'id_kotakab');
    }
}
