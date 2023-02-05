<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'angkatan';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_angkatan';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    public function rumahsakit()
    {
        return $this->hasMany(RumahSakit::class);
    }

    public function parent()
    {
        return $this->belongsTo(Angkatan::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(Angkatan::class, 'parent');
    }
    
}
