<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivio extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'archivio';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Prendo i dati delle risposte.
     */
    public function risposte()
    {
        return $this->hasMany('App\Models\ArchivioRisposte', 'id_archivio', 'id');
    }

}
