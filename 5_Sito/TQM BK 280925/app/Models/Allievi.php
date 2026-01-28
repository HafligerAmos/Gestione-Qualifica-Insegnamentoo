<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Allievi extends Authenticatable {

    use Notifiable;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'allievi';
    protected $guard = 'allievi';
	protected $guarded = [];
	public $timestamps = false;

	public function classi(){
	    return $this->hasMany('App\Models\AllieviClassi', 'id_allievo', 'id');
    }

	/**
	 *  Ritorno gli id delle classi
	 *  @return array Array con gli id delle classi
	 */
	public function getClassi(){
		// Inizializzo un array vuoto
		$classi = [];
		// Passo tutte le classi con le chiavi
		foreach($this->classi->toArray() as $classe){
			// Aggiungo all'array solo l'id della classe
			array_push($classi, $classe["id_classe"]);
		}
		// Ritorno l'array con le classi
		return $classi;
	}

}
