<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anno extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anni';
    protected $guarded = [];

    /**
     *  Inserisce i dati di default
     */
    public static function static(){
        self::insert([
            ['id' => 1, 'anno' => '2017-2018'],
            ['id' => 2, 'anno' => '2018-2019'],
            ['id' => 3, 'anno' => '2019-2020'],
            ['id' => 4, 'anno' => '2020-2021'],
            ['id' => 5, 'anno' => '2021-2022'],
            ['id' => 6, 'anno' => '2022-2023'],
	    ['id' => 7, 'anno' => '2023-2024'],
	    ['id' => 8, 'anno' => '2024-2025'],
	    ['id' => 9, 'anno' => '2025-2026'],
	    ['id' => 10, 'anno' => '2026-2027'],
	    ['id' => 11, 'anno' => '2027-2028'],
	    ['id' => 12, 'anno' => '2028-2029'],
	    ['id' => 13, 'anno' => '2029-2030'],
        ]);
    }
}
