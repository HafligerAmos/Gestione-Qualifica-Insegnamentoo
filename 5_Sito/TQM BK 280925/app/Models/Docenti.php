<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Docenti extends Authenticatable
{
    use Notifiable;
    
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'docenti';
    protected $guard = 'docenti';
	protected $guarded = [];
	public $timestamps = false;

}
