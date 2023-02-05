<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komando extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'komando';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_komando';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'parent', 'parent');
    }
}
