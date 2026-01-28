<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocentiClassi extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'docenti_classi';
	protected $guarded = [];
	public $timestamps = false;

    public function docente(){
        return $this->belongsTo('App\Models\Docenti', 'id_docente', 'id');
    }

    public function classe(){
        return $this->belongsTo('App\Models\Classi', 'id_classe', 'id');
    }
}
