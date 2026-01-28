<?php

namespace App\Http\Controllers\Valutazione;

use \App\Http\Controllers\Controller;
use \App\Models\Classi;
use \App\Models\Docenti;
use \App\Models\DocentiClassi;
use \App\Models\Semestre;
use \App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 *  Gestisce i sondaggi.
 *
 *  Autenticazione necessaria come amministratore.
 */
class SemestriController extends Controller {
    /**
     * Mostra la lista dei docenti.
     * @return mixed
     */
    public function index(){
        // Se è autenticato il docente
        if(auth()->guard('docenti')->check()){
            // Prendo i dati del docente
            $docente = auth()->guard('docenti')->user();
            // Prendo tutti i sondaggi del docente
            $classi = DocentiClassi::with(['classe'])->where('id_docente', auth()->guard('docenti')->user()->id)->get();
            // Prendo i semestri
            $semestri = Semestre::all();
            //Se il result è vuoto, lo rimando alla lista dei sondaggi
            return view('valutazione.semestri.edit', compact('docente', 'classi', 'semestri'));
        // Se è autenticato l'amministratore o la segretaria
		} else {
            // Prendo tutti i docenti
            $docenti = Docenti::orderBy('cognome', 'ASC')->orderBy('nome', 'ASC')->get();
            // Prendo le classi dei docenti
            $classi = DocentiClassi::all();
            //Se il result è vuoto, ritorno la view con l'avviso che è vuoto
            return view('valutazione.semestri.index', compact('docenti', 'classi'));
        }
    }

    /**
     * Mostra le classi ed i semestri del docente.
     * @param int $id
     * @return mixed
     */
    public function show(int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|exists:docenti,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails())
            // Ritorno un errore
            return redirect()->back()->withErrors(['Il docente non esiste!']);
        // Prendo i dati del docente
        $docente = Docenti::find($id);
        // Prendo tutti i sondaggi del docente
        $classi = DocentiClassi::with(['classe'])->where('id_docente', $id)->get();
        // Prendo i semestri
        $semestri = Semestre::all();
        //Se il result è vuoto, lo rimando alla lista dei sondaggi
        return view('valutazione.semestri.edit', compact('docente', 'classi', 'semestri'));
    }

    /**
     * Salvo i semestri del docente.
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|exists:docenti,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails())
            // Ritorno un errore
            return redirect()->back()->withErrors(['Il docente non esiste!']);
        // Prendo i dati del docente
        $docente = Docenti::find($id);
		// Se è già stato configurato ed è un docente
		if((auth()->guard('docenti')->check() && $docente->id !== $id) || (auth()->guard('docenti')->check() && $docente->semestre_configurato))
			// Ritorno indietro
			return redirect()->back();
        // Prendo tutti i sondaggi del docente
        $classi = DocentiClassi::where('id_docente', $id)->get();
        // Creo l'array per la validazione dei semestri
        $validazione_semestri = [];
        // Per ogni classi->docente
        foreach($classi as $classe){
            // Inserisco la validazione per i dati della classe->docente
            $validazione_semestri[$classe->id_classe.';;'.str_replace('.', '_', str_replace(' ', '_', $classe->materia))] = 'bail|required|integer|numeric|exists:semestri,id';
        }
        // Valido la richiesta dei semestri
        $dati = $request->validate($validazione_semestri);
        // Passo tutte le classi materia
        foreach($dati as $classe_materia => $id_semestre){
            // Salvo l'array classe/materia
            $classe_materia = explode(';;', str_replace('_', ' ', $classe_materia));
            // Se non sono due elementi
            if(sizeof($classe_materia) !== 2)
                // Ritorno indietro
                return redirect()->back();
            // Aggiorno i dati dei semestri
            DocentiClassi::where([
                'id_classe' => $classe_materia[0],
                'id_docente' => $id,
                'materia' => $classe_materia[1],
            ])->update([
               'id_semestre' => $id_semestre
            ]);
        }
		// Setto che è il semestre è stato configurato
		$docente->semestre_configurato = 1;
		$docente->save();
        // Lo rimando alla lista dei semestri
        return redirect()->route('valutazione.gestione.semestri.index')->with('success', 'Semestri '.(!auth()->guard('docenti')->check() ? ' di '.$docente->cognome.' '.$docente->nome : '').' modificati con successo!');
    }

	/**
	 * Disattivo tutti i docenti.
	 * @param int $id
	 * @return mixed
	 */
	public function deactivate(Request $request){
		// Modifico la configurazione a tutte le classi di tutti i docenti
		Docenti::where('semestre_configurato', 1)->update([
			'semestre_configurato' => 0
		]);
		// Ritorno alla lista dei semestri
		return redirect()->route('valutazione.gestione.semestri.index')->with('success', 'Semestri sconfigurati con successo!');
	}

	/**
     * Attivo tutti i docenti.
     * @param int $id
     * @return mixed
     */
    public function activate(Request $request){
        // Modifico la configurazione a tutte le classi di tutti i docenti
        Docenti::where('semestre_configurato', 0)->update([
			'semestre_configurato' => 1
		]);
        // Ritorno alla lista dei semestri
        return redirect()->route('valutazione.gestione.semestri.index')->with('success', 'Semestri configurati con successo!');
	}
}
