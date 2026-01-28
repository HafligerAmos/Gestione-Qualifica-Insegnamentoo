<?php

namespace App\Http\Controllers\Sondaggio;

use \App\Http\Controllers\Controller;
use \App\Models\Categoria;
use \App\Models\Classi;
use \App\Models\Domanda;
use \App\Models\Docenti;
use \App\Models\DocentiClassi;
use \App\Models\Sondaggio;
use \App\Models\SondaggioDati;
use \App\Models\SondaggioDomande;
use \App\Models\SondaggioRisposte;
use \App\Models\User;
use \Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 *  Gestisce dei modelli di sondaggio
 *
 *  Autenticazione necessaria come amministratore.
 */
class ModelloController extends Controller {
    /**
     * Mostra la lista di modello di sondaggio.
     * @return mixed
     */
    public function index(){
        // Prendo la lista dei sondaggi
        $sondaggi = Sondaggio::all();
        //Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('sondaggio.modello.lista', compact('sondaggi'));
    }

    /**
     * Mostra la pagina di creazione dei modelli di sondaggio.
     * @return mixed
     */
    public function create(){
        // Ritorno la view di creazione
        return view('sondaggio.modello.crea');
    }

    /**
     * Crea un nuovo modello di sondaggio.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        // Faccio la validazione ai dati del form
        Validator::make($request->all(), [
            'nome' => 'bail|required|max:64',
            'opzioni' => 'bail|required|numeric|integer|min:4|max:4' // Inserire min:2|max:6 per tornare alla selezione delle opzioni normali
        ])->validate();
        // Salvo le domande
        $domande = [];
        // Salvo un controllo per procedere
        $proceed = false;
		// Passo tutte le domande
		foreach($request->all() as $key => $value){
		    // D: Definizione
		    // C: Categoria
		    // Controllo il valore:
		    // - Lunghezza della stringa dell'input name uguale a 2 o 3 (massimo 99 domande)
		    // - La chiave dell'input name uguale a D o C
		    // - Se il valore dell'input name è numero
		    if((strlen($key) === 2 || strlen($key) === 3) && ($key[0] === 'D' || $key[0] === 'C') && is_numeric(intval($key[1])) ){
		        // Imposto che può procedere con la creazione del sondaggio
		        if(!$proceed) $proceed = true;
		        // Controllo l'inizio della chiave dell'input name
		        if($key[0] == 'C')
		            array_push($domande, [intval($key[1].(isset($key[2]) ? $key[2] : '')) => [$key[0] => intval($value)]]);
		        else
		            array_push($domande[intval($key[1].(isset($key[2]) ? $key[2] : ''))-1], [$key[0] => $value]);
		    }
		}

        // Controllo che è presente almeno una domanda
        if($proceed){
            // Crea il nuovo sondaggio
            $sondaggio_id = Sondaggio::insertGetId([
                'nome' => $request->input('nome'),
                'opzioni' => $request->input('opzioni'),
            ]);
            // Passo tutte le domande per inserirle nel database
            foreach($domande as $domanda){
                // Passo il contenuto della domanda per prendere
                // la definizione e l'id della categoria
                foreach($domanda as $domand){
                    // Se la chiave è la categoria
                    if(array_key_exists('C', $domand))
                        // Salvo l'id della categoria
                        $categoria_id = intval($domand['C']);
                    // Se la chiave è la definizione
                    else if(array_key_exists('D', $domand)){
                        // Salvo la definizione
                        $definizione = $domand['D'];
                    }
                }
                // Controllo se l'id della categoria e la definizione
                // sono salvati e se la categoria esiste.
                if(isset($categoria_id) && isset($definizione) && Categoria::exists($categoria_id)){
                    // Inserisco la domanda al sondaggio
                    SondaggioDomande::insert([
                        'id_sondaggio' => $sondaggio_id,
                        'id_categoria' => $categoria_id,
                        'definizione' => $definizione
                    ]);
                    // Distruggo le variabili settate
                    unset($categoria_id);
                    unset($definizione);
                }
            }
            // Ritorno alla lista dei modelli di sondaggio
            return redirect()->route('sondaggio.modello.lista')->with('success', 'Modello di sondaggio creato con successo!');
        } else {
            return redirect()->back()->withErrors(['Domande invalide. Ricontrollare il totale di domande inserite.']);
        }
    }

    /**
     * Mostra le domande del modello di sondaggio.
     * @param int $id
     * @return mixed
     */
    public function show(int $id){
        // Prendo la lista dei sondaggi
        $domande = SondaggioDomande::with(['sondaggio', 'categoria'])->where('id_sondaggio', $id)->get();
        //Se il result è vuoto, lo rimando alla lista dei sondaggi
        return view('sondaggio.modello.domande', compact('domande'));
    }

    /**
     * Elimina il modello di sondaggio.
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request){
        // Valido la richiesta
        $sondaggio = $request->validate([
            'id' => 'bail|required|numeric|integer|min:0|exists:sondaggio,id'
        ]);
        // Elimino il sondaggio con l'ID passato
        Sondaggio::destroy($sondaggio['id']);
        // Ritorno alla lista
        return redirect()->route('sondaggio.modello.lista')->with('success', 'Modello di sondaggio eliminato con successo!');
    }
}
