<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValutazioneAllievi extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'valutazione_allievi';

    public $timestamps = false;
    protected $guarded = [];

    /**
     * Prendo i dati dei docenti.
     */
    public function allievo()
    {
        return $this->belongsTo('App\Models\Allievi', 'id_allievo', 'id');
    }

    /**
     * Prendo il sondaggio.
     */
    public function valutazione()
    {
        return $this->belongsTo('App\Models\Valutazione', 'id_valutazione', 'id');
    }
}
