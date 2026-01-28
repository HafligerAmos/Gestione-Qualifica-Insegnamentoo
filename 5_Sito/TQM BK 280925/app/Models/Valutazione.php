<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Valutazione extends Model {

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'valutazione';
	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
	public $timestamps = false;
	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = [];

	/**
	 * Prendo i dati del sondaggio template.
	 */
	public function sondaggio()
	{
		return $this->belongsTo('App\Models\Sondaggio', 'id_sondaggio', 'id');
	}

	/**
	 * Prendo i dati dello stato.
	 */
	public function stato()
	{
		return $this->belongsTo('App\Models\Stato', 'id_stato', 'id');
	}

    /**
     * Prendo i dati del docente.
     */
    public function docente()
    {
        return $this->belongsTo('App\Models\Docenti', 'id_docente', 'id');
    }

    /**
     * Prendo i dati della classe.
     */
    public function classe()
    {
        return $this->belongsTo('App\Models\Classi', 'id_classe', 'id');
    }

    /**
     * Prendo i dati dell'anno.
     */
    public function anno()
    {
        return $this->belongsTo('App\Models\Anno', 'id_anno', 'id');
    }

    /**
     * Prendo i dati del semestre.
     */
    public function semestre()
    {
        return $this->belongsTo('App\Models\Semestre', 'id_semestre', 'id');
    }


	// Set Primary Key
	protected function setPrimaryKey($key)
	{
	  $this->primaryKey = $key;
	}

    /**
     * Prendo i dati delle valutazioni degli allievi.
     */
    public function allievi()
    {
		$this->setPrimaryKey('id_classe');
		$relation = $this->belongsToMany('App\Models\Allievi', 'allievi_classi', 'id_classe', 'id_allievo');
		$this->setPrimaryKey('id');
		return $relation;
    }

    /**
     * Prendo i dati delle valutazioni degli allievi.
     */
    public function allievi_valutazioni()
    {
		return $this->hasMany('App\Models\ValutazioneAllievi', 'id_valutazione', 'id');
    }

    /**
     * Prendo i dati delle domande.
     */
    public function domande()
    {
        return $this->hasMany('App\Models\SondaggioDomande', 'id_sondaggio', 'id_sondaggio');
    }

    /**
     * Prendo i dati delle risposte.
     */
    public function risposte()
    {
        return $this->hasMany('App\Models\ValutazioneRisposte', 'id_valutazione', 'id');
    }

    /**
     * Ritorno un array con le faccine felici e tristi
     * basato sul numero massimo del sondaggio.
     * @param $opzioni
     * @return array
     */
    public static function smile($opzioni){
        // Salvo il totale di opzioni
        $happy_smile = '<img src="/assets/img/happy.png" class="smile">';
        $sad_smile = '<img src="/assets/img/sad.png" class="smile">';
        $neutral = '';
        // Creo l'array che contiene un numero fisso di emoji
        // per il minimo di opzioni.
        $smiles = [$sad_smile, $happy_smile];
        // Se il numero di opzioni è dispari
        if($opzioni >= 3 && $opzioni % 2){
            $neutral = $happy_smile;
            // Inserisco la faccina nera
            array_splice($smiles, intval(sizeof($smiles)), 0, $neutral.$happy_smile);
        }
        // Se le emoji sono più delle opzioni
        if($opzioni > sizeof($smiles)){
            // Salvo le stringhe dei caratteri da mettere
            $s1 = $sad_smile;
            $s2 = $neutral.$happy_smile;
            // Passo tutte le opzioni fino a quando
            // non raggiungo il totale di emoji
            while($opzioni > sizeof($smiles)){
                // Aumento le stringhe degli smile
                // in base a quante altre opzioni devo mettere
                $s1 .= $sad_smile;
                $s2 .= $happy_smile;
                // Inserisco due faccine
                array_splice($smiles, 0, 0, $s1);
                // Inserisco due faccine
                array_splice($smiles, sizeof($smiles), 0, $s2);
            }
        }
        // Ritorno l'array di smile
        return $smiles;
    }
}
