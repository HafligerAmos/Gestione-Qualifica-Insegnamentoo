<?php

namespace App\Http\Controllers\Valutazione;

use \App\Http\Controllers\Controller;
use \App\Models\Categoria;
use \App\Models\Classi;
use \App\Models\Domanda;
use \App\Models\Docenti;
use \App\Models\DocentiClassi;
use \App\Models\Sondaggio;
use \App\Models\SondaggioDomande;
use \App\Models\Valutazione;
use \App\Models\ValutazioneRisposte;
use \App\Models\ValutazioneAllievi;
use \App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 *  Gestisce i sondaggi dell'amministratore, del docente o del PIF.
 *
 *  Autenticazione necessaria.
 */
class ValutazioneController extends Controller {

    /**
     * Mostra le valutazioni del docente/allievo autenticato.
     * @return mixed
     */
    public function index(){
        // Prendo tutti i sondaggi del docente
        $valutazioni = Valutazione::with(['sondaggio', 'classe', 'docente', 'anno', 'semestre', 'allievi_valutazioni']);
        // Se l'utente autenticato è un docente
        if(auth()->guard('docenti')->check()) {
            // Prendo le valutazioni del docente
            $valutazioni = $valutazioni->where('id_docente', auth()->guard(session('guard'))->user()->id)->get();
        } else if(auth()->guard('allievi')->check()) {
			// Salvo le classi
			$classi = auth()->guard(session('guard'))->user()->getClassi();
            // Se le classe è una sola
            if(sizeof($classi) === 1)
				// Prendo le valutazioni dell'allievo
            	$valutazioni = $valutazioni->where('id_classe', $classi[0])->get();
            // Se le classi sono 2
			else if(sizeof($classi) === 2)
				// Prendo le valutazioni dell'allievo
            	$valutazioni = $valutazioni->where('id_classe', $classi[0])->orWhere('id_classe', $classi[1])->get();
            // (Improbabile) Se le classi sono 3
			else if(sizeof($classi) === 3)
				// Prendo le valutazioni dell'allievo
            	$valutazioni = $valutazioni->where('id_classe', $classi[0])->orWhere('id_classe', $classi[1])->orWhere('id_classe', $classi[2])->get();
        }
        // Per ogni valutazione
        foreach($valutazioni as $valutazione){
            // Aggiungo la percentuale di completamento degli allievi
            $valutazione->percentage = number_format(floor($valutazione->allievi_completato / $valutazione->allievi_totali * 100));
        }
        //Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('valutazione.mie', compact('valutazioni'));
    }

    /**
     * Mostra la pagina di valutazione per un sondaggio.
     * @param int $id
     * @return mixed
     */
    public function create(int $id){
        // Controllo l'id della valutazione
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|min:0|exists:valutazione,id'
        ]);
        // Se la validazione dell'id fallisce
        if($validazione->fails())
            // Ritorno alla pagina delle sue valutazioni poiché quella richiesta non esiste
            return redirect()->route('valutazione.mie')->withErrors(['Valutazione inesistente!']);
        // Prendo la valutazione in base all'ID
        $valutazione = Valutazione::with('sondaggio')->find($id);
		// Se è un allievo
		if(auth()->guard('allievi')->check()){
			// Salvo le classi e un boolean di controllo
			$classi = auth()->guard(session('guard'))->user()->getClassi();
			$classeCorretta = false;
			// Passo tutte le classi dell'allievo
			foreach($classi as $classe){
				// Se la classe della valutazione corrisponde con quella dell'allievo
				if($valutazione->id_classe === $classe)
					// Il controllo è corretto
					$classeCorretta = true;
			}
		}
        // Se è un docente
        if((auth()->guard('docenti')->check() && $valutazione->id_docente === auth()->guard('docenti')->user()->id && !$valutazione->docente_completato) ||
           (auth()->guard('allievi')->check() && !is_null(ValutazioneAllievi::where(['id_valutazione' => $valutazione->id, 'id_allievo' => auth()->guard('allievi')->user()->id])->first()) && $classeCorretta )){
            // Prendo la lista delle domande del sondaggio
            $sondaggio = Sondaggio::with('domande')->find($valutazione->id_sondaggio);
            // Prendo gli smile necessari
            $smiles = Valutazione::smile($sondaggio->opzioni);
            //Se il result è vuoto, lo rimando alla lista dei sondaggi
            return view('valutazione.valuta', compact('valutazione', 'sondaggio', 'smiles', 'id'));
        } else {
            // Ritorno alla pagina delle sue valutazioni
            return redirect()->route('valutazione.mie')->withErrors(['Valutazione inesistente!']);
        }
    }

    /**
     * Salvo le valutazioni del docente/allievo.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // Controllo l'id della valutazione
        $validazione = $request->validate([
            'id' => 'bail|required|numeric|integer|min:0|exists:valutazione,id'
        ]);
        // Prendo la valutazione in base all'ID
        $valutazione = Valutazione::with(['sondaggio'])->find($validazione['id']);
        // Prendo la lista delle domande del sondaggio
        $sondaggio = Sondaggio::with('domande')->find($valutazione->id_sondaggio);
        // Passo tutte le definizioni
        foreach ($sondaggio->domande as $domanda) {
            $vals['V' . $domanda->id] = 'bail|required|numeric|integer|min:1|max:' . $sondaggio->opzioni;
            $vals['D' . $domanda->id] = 'bail|required|numeric|integer|min:1|exists:sondaggio_domande,id';
        }
        // Valido la richiesta
        $risposte = $request->validate($vals);
        // Se l'utente autenticato è un docente
        if (auth()->guard('docenti')->check()) {
            // Se esiste l'allievo nelle valutazioni
            if (!Valutazione::find($valutazione->id)->docente_completato) {
				// Passo tutte le domande
				foreach ($sondaggio->domande as $domanda) {
				    // Se la valutazione delle risposte non è gia presente aggiungo le righe delle risposte
				    if (is_null(ValutazioneRisposte::where([
				        'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
				        'id_domanda' => $domanda->id, // ID della domanda
				    ])->first())) {
				        // Salvo le risposte della valutazione del docente
				        ValutazioneRisposte::insert([
				            'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
				            'id_domanda' => $domanda->id, // ID della domanda
				            'risposta' => intval($risposte['V' . $domanda->id]), // Risposta data dal docente
				        ]);
				        // Se la valutazione delle risposte è gia presente aggiorno solamente i dati
				    } else {
				        // Salvo le risposte della valutazione del docente
				        ValutazioneRisposte::where([
				            'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
				            'id_domanda' => $domanda->id, // ID della domanda
				        ])->update([
				            'risposta' => intval($risposte['V' . $domanda->id]), // Risposta data dal docente
				        ]);
				    }
				}
                // Salvo che il docente ha fatto il sondaggio
                Valutazione::find($valutazione->id)->update([
                    'docente_completato' => 1,
                ]);
            } else {
                // Ritorno indietro
                return redirect()->back();
            }
        } else if (auth()->guard('allievi')->check()) {
            // Salvo che il docente ha fatto il sondaggio
            $valutazione_allievo = ValutazioneAllievi::where([
                'id_valutazione' => $valutazione->id,
                'id_allievo' => auth()->guard('allievi')->user()->id,
            ])->first();
            // Se esiste l'allievo nelle valutazioni
            if (!is_null($valutazione_allievo)) {
                // Passo tutte le domande
                foreach ($sondaggio->domande as $domanda) {
					// Se la valutazione delle risposte non è gia presente aggiungo le righe delle risposte
					if (is_null(ValutazioneRisposte::where([
					    'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
					    'id_domanda' => $domanda->id, // ID della domanda
					])->first())) {
					    // Salvo la valutazione del docente
					    ValutazioneRisposte::insert([
					        'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
					        'id_domanda' => $domanda->id, // ID della domanda
					        'media_pif' => intval($risposte['V' . $domanda->id]),
					    ]);
					    // Se la valutazione delle risposte è gia presente aggiorno solamente i dati
					} else {
					    // Salvo la valutazione del docente
					    ValutazioneRisposte::where([
					        'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
					        'id_domanda' => $domanda->id, // ID della domanda
					    ])->increment('media_pif', intval($risposte['V' . $domanda->id]));
					}
					// Se la media corrente non è 0
					if(intval((ValutazioneRisposte::where([
						'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
						'id_domanda' => $domanda->id
					])->first())->media_pif) === 0)
	                    // Faccio la media
	                    ValutazioneRisposte::where([
	                        'id_valutazione' => $valutazione->id, // ID del sondaggio relazionato al docente
	                        'id_domanda' => $domanda->id, // ID della domanda
	                    ])->update([
	                        'media_pif' => DB::raw('media_pif / 2'),
	                    ]);
                }
                // Tolgo l'allievo dalle valutazioni
                ValutazioneAllievi::where([
                    'id_valutazione' => $validazione['id'],
                    'id_allievo' => auth()->guard('allievi')->user()->id,
                ])->delete();
                // Incremento il totale di allievi che hanno completato la valutazione
                Valutazione::find($valutazione->id)->increment('allievi_completato');
            } else {
                // Ritorno indietro
                return redirect()->back();
            }
        } else {
            // Ritorno indietro
            return redirect()->back();
        }
        // Salvo la valutazione aggiornata
        $updated_valutazione = Valutazione::find($valutazione->id);
        // Se sia gli allievi che il docente lo hanno completato
        if ($updated_valutazione->allievi_completato === $updated_valutazione->allievi_totali &&
            $updated_valutazione->docente_completato){
            // Chiudo la valutazione
            Valutazione::find($valutazione->id)->update([
                'id_stato' => 3,
            ]);
        } else {
            // Cambio lo stato a "In Corso"
            Valutazione::find($valutazione->id)->update([
                'id_stato' => 2,
            ]);
        }
        // Ritorno ai sondaggi del docente / allievo
        return redirect()->route('valutazione.mie')->with('success', 'Valutazione completata con successo');
    }

    /**
     * Mostra i risultati della valutazione.
     * @param int $id
     * @return mixed
     */
    public function show(int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|exists:valutazione,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails())
            // Ritorno un errore
            return redirect()->back()->withErrors(['La valutazione non esiste!']);
        // Prendo la valutazione
        $valutazione = Valutazione::with(['sondaggio', 'docente', 'risposte.domanda.categoria'])->find($id);
        // Prendo gli smile
        $smiles = Valutazione::smile($valutazione->sondaggio->opzioni);
        //Se il result è vuoto, lo rimando alla lista dei sondaggi
        return view('valutazione.risultati', compact('valutazione', 'smiles'));
    }

    /**
     * Mostra il report della valutazione.
     * @param int $id
     * @return mixed
     */
    public function report(int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|min:0|exists:valutazione,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails())
            // Ritorno un errore
            return redirect()->back()->withErrors(['La valutazione non esiste o non hai i diritti necessari!']);
        // Prendo i dati della valutazione
        $valutazione = Valutazione::with(['classe', 'docente', 'allievi', 'semestre', 'anno', 'sondaggio', 'domande', 'risposte.domanda.categoria'])->find($id);
        // Prendo le categorie delle valutazione
        $categorie = ($valutazione->domande)->intersectByKeys(Categoria::all())->pluck('categoria');
        // Prendo i dati del docente con la classe
        $docente_classe = DocentiClassi::where([
            'id_classe' => $valutazione->id_classe,
            'id_docente' => $valutazione->id_docente,
        ])->first();
        // Ritorno la view
        return view('valutazione.report', compact('valutazione', 'categorie', 'docente_classe'));
    }

    /**
     * Stampa il report della valutazione.
     * @param int $id
     * @return mixed
     */
    public function stampa(int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|min:0|exists:valutazione,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails())
            // Ritorno un errore
            return redirect()->back()->withErrors(['La valutazione non esiste o non hai i diritti necessari!']);
        // Prendo i dati della valutazione
        $valutazione = Valutazione::with(['classe', 'docente', 'semestre', 'allievi', 'anno', 'sondaggio', 'domande', 'risposte.domanda.categoria'])->find($id);
		// Se non ci sono risposte
        if($valutazione->risposte->count() === 0)
            // Ritorno indietro
            return redirect()->back();
        // Prendo le categorie delle valutazione
        $categorie = ($valutazione->domande)->intersectByKeys(Categoria::all())->pluck('categoria');
        // Prendo i dati del docente con la classe
        $docente_classe = DocentiClassi::where([
            'id_classe' => $valutazione->id_classe,
            'id_docente' => $valutazione->id_docente,
        ])->first();
        // Ritorno la view
        return view('valutazione.stampa', compact('valutazione', 'categorie', 'docente_classe'));
    }

    /**
     * Mostra il report di media del docente della valutazione.
     * @param int $id
     * @return mixed
     */
    public function report_media(int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|min:0|exists:valutazione,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails())
            // Ritorno un errore
            return redirect()->back()->withErrors(['La valutazione non esiste o non hai i diritti necessari!']);
        // Prendo l'ID del docente
        $docente = (Valutazione::with('docente')->find($id))->docente;
        // Prendo i dati della valutazione
        $valutazioni = Valutazione::with(['classe', 'allievi', 'semestre', 'anno', 'sondaggio', 'risposte.domanda.categoria'])->where('id_docente', $docente->id)->get();
        // Salvo il counter
        $count = 0;
        // Per ogni valutazione
        foreach($valutazioni as $valutazione){
            // Se non ci sono risposte
            if($valutazione->risposte->count() === 0)
                // Aumento il contatore
                $count++;
            // Prendo le categorie delle valutazione
            $valutazione->categorie = ($valutazione->domande)->intersectByKeys(Categoria::all())->pluck('categoria');
            // Prendo i dati del docente con la classe
            $valutazione->docente_classe = DocentiClassi::where([
                'id_classe' => $valutazione->id_classe,
                'id_docente' => $valutazione->id_docente,
            ])->first();
        }
        // Se il contatore è uguale al totale di valutazioni
        if($valutazioni->count() === $count)
            // Ritorno indietro
            return redirect()->back();
        // Ritorno la view
        return view('valutazione.report_media', compact('valutazioni', 'docente'));
    }

}
