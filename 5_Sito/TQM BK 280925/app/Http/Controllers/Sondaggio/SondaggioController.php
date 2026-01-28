<?php

namespace App\Http\Controllers\Sondaggio;

use \App\Http\Controllers\Controller;
use \App\Models\Allievi;
use \App\Models\Anno;
use \App\Models\Categoria;
use \App\Models\Classi;
use \App\Models\Domanda;
use \App\Models\Docenti;
use \App\Models\DocentiClassi;
use \App\Models\Semestre;
use \App\Models\Sondaggio;
use \App\Models\SondaggioDomande;
use \App\Models\Valutazione;
use \App\Models\ValutazioneAllievi;
use \App\Models\ValutazioneRisposte;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 *  Gestisce i sondaggi.
 *
 *  Autenticazione necessaria come amministratore.
 */
class SondaggioController extends Controller {
    /**
     * Mostra la pagina per far iniziare un sondaggio.
     * @return mixed
     */
    public function index(){
        // Prendo la lista dei sondaggi
        $sondaggi = Sondaggio::all();
        // Prendo gli anni
        $anni = Anno::all();
        // Prendo i semestri
        $semestri = Semestre::all();
        //Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('sondaggio.inizia', compact('sondaggi', 'anni', 'semestri'));
    }

    /**
     * Fa partire il sondaggio, memorizzando nel database
     * tutte le valutazioni per i docenti.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        // Faccio la validazione ai dati del form
        $data = $request->validate([
            'sondaggio' => 'bail|required|numeric|integer|exists:sondaggio,id',
            'classi' => 'bail|required|numeric|integer|min:1|max:6',
            'anno' => 'bail|required|numeric|integer|min:0|exists:anni,id',
            'semestre' => 'bail|required|numeric|integer|min:0|exists:semestri,id',
            'durata' => 'bail|required|numeric|integer|min:1|max:15'
        ]);
        // Salvo i docenti
        // e gli aggiungo ai sondaggi_dati la classe con quel docente
        $docenti = Docenti::where('semestre_configurato', 1)->get();
        // Passo tutti i docenti
        foreach($docenti as $docente){
			// Prendo le classi tramite l'id del docente e li mischio randomicamente $docente->id
			$docenti_classi = DocentiClassi::with('classe')->where([
			    'id_docente' => $docente->id,
			    'id_semestre' => $data['semestre']
			])->get()->groupBy('id_classe')->shuffle();
			// Se sono di più del numero di classi che vuole iniziare l'amministratore
			// prendo il numero massimo di classi di quel docente e lo setto ad il massimo
			// che è consentito dal totale di classi
			$docenti_classi = $docenti_classi->slice(0, ($data['classi'] > $docenti_classi->count() ? $docenti_classi->count() : $data['classi']));
			// Passo tutte le classi di quel docente
			foreach($docenti_classi as $id_classe => $docente_classe){
                // Se non esiste la classe
                if(is_null($valutazione = Valutazione::where([
                    'id_sondaggio' => $data['sondaggio'], // ID Sondaggio
                    'id_docente' => $docente->id, // Email del docente
                    'id_classe' => $docente_classe->first()->id_classe, // Classe
                    'id_anno' => $data['anno'], // Anno
                    'id_semestre' => $data['semestre'], // Semestre
                ])->first())){
                    // Salvo gli id degli allievi
                    $allievi = AllieviClassi::getAllievi($docente_classe->first()->id_classe);
                    // Inserisco i dati della valutazione
                    $valutazione_id = Valutazione::insertGetId([
                        'id_sondaggio' => $data['sondaggio'], // ID Sondaggio
                        'id_docente' => $docente->id, // Email del docente
                        'id_classe' => $docente_classe->first()->id_classe, // Classe
                        'id_anno' => $data['anno'], // Anno
                        'id_semestre' => $data['semestre'], // Semestre
                        'id_stato' => 1, // Stato "Aperto" poiché non ancora completato dal docente
                        'allievi_completato' => 0, // Allievi che hanno completato il sondaggio
                        'allievi_totali' => sizeof($allievi), // Numero di allievi totali della classe
                        'fine' => Carbon::now()->addWeeks($data['durata']), // Data di adesso più le settimane che ha inserito
                    ]);
                    // Per ogni allievi nella classe
                    foreach($allievi as $allievo_id){
                        // Se non esiste l'allievo nelle valutazioni
                        if(is_null(ValutazioneAllievi::where([
                            'id_valutazione' => $valutazione_id,
                            'id_allievo' => $allievo_id,
                        ])->first())) {
                            // Inserisco gli allievi nella valutazione
                            ValutazioneAllievi::insert([
                                'id_valutazione' => $valutazione_id,
                                'id_allievo' => $allievo_id,
                            ]);
                        }
                    }
                }
                // Aumento l'uso di volte del sondaggio
                Sondaggio::where('id', $data['sondaggio'])->increment('usato');
            }
        }
        // Ritorno alla pagina di gestione dei sondaggi dei docenti
        return redirect()->route('sondaggio.inizia')->with('success', 'Sondaggio iniziato con successo!');
    }
}
