<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semestre extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'semestri';
    protected $guarded = [];

    /**
     *  Inserisce i dati di default
     */
    public static function static(){
        self::insert([
            ['id' => 1, 'semestre' => 'Primo'],
            ['id' => 2, 'semestre' => 'Secondo'],
            ['id' => 3, 'semestre' => 'Annuale'],
        ]);
    }
}
