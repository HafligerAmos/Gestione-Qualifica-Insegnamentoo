<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classi extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classi';
	protected $guarded = [];
	public $timestamps = false;

	public function allievi(){
		return $this->belongsToMany('App\Models\ClassiAllievi', 'classi', 'id');
	}

	public function docenti(){
		return $this->hasMany('App\Models\Docenti', 'id');
	}

}
