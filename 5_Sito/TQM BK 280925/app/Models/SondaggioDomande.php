<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SondaggioDomande extends Model
{

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sondaggio_domande';

	/**
	 * Prendo i dati del sondaggio template.
	 */
	public function sondaggio()
	{
		return $this->belongsTo('App\Models\Sondaggio', 'id_sondaggio', 'id');
	}

	/**
	 * Prendo le domande del sondaggio.
	 */
	public function categoria()
	{
		return $this->belongsTo('App\Models\Categoria', 'id_categoria', 'id');
	}
}
