<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValutazioneRisposte extends Model
{

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'valutazione_risposte';

	public $timestamps = false;
	protected $guarded = [];

	/**
	 * Prendo i dati dei docenti.
	 */
	public function docenti()
	{
		return $this->belongsTo('App\Models\Docenti', 'id_docente', 'id');
	}

	/**
	 * Prendo il sondaggio.
	 */
	public function valutazione()
	{
		return $this->belongsTo('App\Models\Valutazione', 'id_valutazione', 'id');
	}

	/**
	 * Prendo la domanda.
	 */
	public function domanda()
	{
		return $this->belongsTo('App\Models\SondaggioDomande', 'id_domanda', 'id');
	}
}
