<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stato extends Model {

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stato';

    public static function static(){
		self::insert([
			['id' => 1, 'nome' => 'Aperto', 'class' => 'success'],
			['id' => 2, 'nome' => 'In Corso', 'class' => 'warning'],
            ['id' => 3, 'nome' => 'Chiuso', 'class' => 'danger'],
		]);
	}
}
