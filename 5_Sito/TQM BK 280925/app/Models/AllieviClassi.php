<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllieviClassi extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'allievi_classi';
	protected $guarded = [];
	public $timestamps = false;

    public function allievo(){
        return $this->belongsTo('App\Models\Allievi', 'id_allievo', 'id');
    }

    public function classe(){
        return $this->belongsTo('App\Models\Classi', 'id_classe', 'id');
    }

	/**
	 *  Ritorno gli id degli allievi
	 *  @return array Array con gli id degli allievi
	 */
	public static function getAllievi($classe_id){
		// Inizializzo un array vuoto
		$allievi = [];
		// Passo tutte le classi con le chiavi
		foreach(self::where('id_classe', $classe_id)->get()->toArray() as $allievo){
			// Aggiungo all'array solo l'id della classe
			array_push($allievi, $allievo["id_allievo"]);
		}
		// Ritorno l'array con le classi
		return $allievi;
	}
}
