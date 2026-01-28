<?php

namespace App\Http\Controllers\Valutazione;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Classi;
use App\Models\Domanda;
use App\Models\Docenti;
use App\Models\DocentiClassi;
use App\Models\Sondaggio;
use App\Models\SondaggioDomande;
use App\Models\Valutazione;
use App\Models\ValutazioneRisposte;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 *  Gestisce i sondaggi.
 *
 *  Autenticazione necessaria come amministratore.
 */
class GestioneController extends Controller {
    /**
     * Mostra la lista di gestione delle valutazioni.
     * @return mixed
     */
    public function index(){
		// Prendo tutti i sondaggi del docente
		$valutazioni = Valutazione::with(['sondaggio', 'classe', 'docente', 'anno', 'semestre'])->get();
		// Per ogni valutazione
		foreach($valutazioni as $valutazione){
			// Se la percentuale è diversa da 0
		    if($valutazione->allievi_totali != 0)
		        // Aggiungo la percentuale di completamento degli allievi
		        $valutazione->percentage = number_format(round($valutazione->allievi_completato / $valutazione->allievi_totali * 100, 1));
			// Se la percentuale è uguale a 0
			else
		        // Aggiungo la percentuale di completamento degli allievi
		        $valutazione->percentage = 0;
		}
        //Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('valutazione.gestisci', compact('valutazioni'));
    }

    /**
     * Elimina la valutazione.
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request){
        // Valido la richiesta
        $data = $request->validate([
            'id' => 'bail|required|numeric|integer|exists:valutazione,id'
        ]);
        // Cancello il sondaggio
        Valutazione::destroy($data['id']);
        // Ritorno alla lista
        return redirect()->route('valutazione.gestione.index')->with('success', 'Valutazione eliminata con successo!');
    }

    /**
     * Apre la valutazione del docente.
     * @param Request $request
     * @return mixed
     */
    public function open(Request $request){
        // Valido la richiesta
        $data = $request->validate([
            'id' => 'bail|required|numeric|integer|exists:valutazione,id'
        ]);
        // Prendo la valutazione
        $valutazione = Valutazione::find($data['id']);
        // Aggiorno la valutazione
        $valutazione->id_stato = 2;
        $valutazione->fine = Carbon::parse($valutazione->fine)->addWeeks(2);
        $valutazione->save();
        // Ritorno alla lista
        return redirect()->route('valutazione.gestione.index')->with('success', 'Valutazione riaperta con successo!');
    }

    /**
     * Chiude la valutazione del docente.
     * @param Request $request
     * @return mixed
     */
    public function close(Request $request){
        // Valido la richiesta
        $data = $request->validate([
            'id' => 'bail|required|numeric|integer|exists:valutazione,id'
        ]);
        // Chiudo la valutazione
        Valutazione::find($data['id'])->update(['id_stato' => 3]);
        // Ritorno alla lista
        return redirect()->route('valutazione.gestione.index')->with('success', 'Valutazione chiusa con successo!');
    }

    /**
     * Chiude tutte le valutazioni.
     * @param Request $request
     * @return mixed
     */
    public function close_all(Request $request){
        // Cancello il sondaggio
        DB::table('valutazione')->update(['id_stato' => 3]);
        // Ritorno alla lista
        return redirect()->route('valutazione.gestione.index')->with('success', 'Tutte le valutazioni sono state chiuse con successo!');
    }
}
