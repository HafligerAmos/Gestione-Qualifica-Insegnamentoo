<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Sondaggio extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sondaggio';

	public $timestamps = false;

	/**
	 * Prendo le domande del sondaggio.
	 */
	public function domande()
	{
		return $this->hasMany('App\Models\SondaggioDomande', 'id_sondaggio', 'id');
	}

	/**
	 * Prendo i dati dello stato.
	 */
	public function stato()
	{
		return $this->belongsTo('App\Models\Stato', 'id_stato', 'id');
	}
}
