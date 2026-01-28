<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categoria';
	protected $guarded = [];

	/**
	 *  Controllo se esiste l'id della categoria
	 *  @param  int    $id Id della categoria
	 *  @return boolean     Se esiste o no.
	 */
	public static function exists(int $id){
		$cat = self::where(function ($query) use ($id){
			$query->where(compact($id));
		});

		if(!is_null($cat)) return true;
		else return false;
	}

	/**
	 *  Inserisce i dati di default
	 */
    public static function static(){
		self::insert([
			['id' => 1, 'nome' => 'Personalizzazione', 'abb' => 'Pe'],
			['id' => 2, 'nome' => 'Partecipazione', 'abb' => 'Pa'],
			['id' => 3, 'nome' => 'Indipendenza', 'abb' => 'In'],
			['id' => 4, 'nome' => 'Ricerca di soluzioni', 'abb' => 'So'],
			['id' => 5, 'nome' => 'Differenziazione', 'abb' => 'Di'],
			['id' => 6, 'nome' => 'Organizzazione', 'abb' => 'Or'],
		]);
	}
}
