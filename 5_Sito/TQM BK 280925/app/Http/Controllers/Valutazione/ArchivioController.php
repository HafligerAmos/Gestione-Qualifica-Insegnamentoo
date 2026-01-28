<?php

namespace App\Http\Controllers\Valutazione;

use App\Http\Controllers\Controller;
use App\Models\Archivio;
use App\Models\ArchivioRisposte;
use App\Models\Categoria;
use App\Models\Classi;
use App\Models\Domanda;
use App\Models\Docenti;
use App\Models\DocentiClassi;
use App\Models\Sondaggio;
use App\Models\SondaggioDomande;
use App\Models\Valutazione;
use App\Models\ValutazioneRisposte;
use App\Models\ValutazioneAllievi;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 *  Gestisce i sondaggi dell'amministratore, del docente o del PIF.
 *
 *  Autenticazione necessaria.
 */
class ArchivioController extends Controller {

    /**
     * Archivia tutte le valutazioni correnti.
     * @return mixed
     */
    public function archive_all(){
        // Prendo tutte le valutazioni
        $valutazioni = Valutazione::with(['sondaggio', 'classe', 'docente', 'anno', 'semestre', 'domande', 'risposte.domanda.categoria'])->get();
        // Prendo tutte le categorie
        $all_categorie = Categoria::all();
        // Per ogni valutazione
        foreach($valutazioni as $valutazione){
            // Prendo i dati del docente con la classe
            $docente_classe = DocentiClassi::where([
                'id_classe' => $valutazione->id_classe,
                'id_docente' => $valutazione->id_docente,
            ])->first();
            // Aggiungo la valutazione all'archivio
            $id_archivio = Archivio::insertGetId([
                'nome_sondaggio' => $valutazione->sondaggio->nome,
                'opzioni' => $valutazione->sondaggio->opzioni,
                'usato' => $valutazione->sondaggio->usato,
                'created_at' => $valutazione->sondaggio->created_at,
                'nome_docente' => $valutazione->docente->nome,
                'cognome_docente' => $valutazione->docente->cognome,
                'materia' => $docente_classe->materia,
                'professione' => $valutazione->allievi->first()->professione,
                'nome_classe' => $valutazione->classe->nome,
                'semestre' => $valutazione->semestre->semestre,
                'anno' => $valutazione->anno->anno,
                'allievi_completato' => $valutazione->allievi_completato,
                'allievi_totali' => $valutazione->allievi_totali,
            ]);
            // Prendo le categorie delle valutazione
            $categorie = ($valutazione->domande)->intersectByKeys($all_categorie)->pluck('categoria');
            // Per ogni categoria del sondaggio
            foreach($categorie as $categoria){
                // Inserisco le risposte per ogni categoria
                ArchivioRisposte::insert([
                    'id_archivio' => $id_archivio,
                    'nome_categoria' => $categoria->nome,
                    'docente' => $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('risposta'),
                    'media_pif' => $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('media_pif'),
                ]);
            }
            // Elimino la valutazione
            Valutazione::find($valutazione->id)->delete();
            // Elimino gli allievi legati a quella valutazione (facoltativo per via delle FK)
            ValutazioneAllievi::where('id_valutazione', $valutazione->id)->delete();
            // Elimino le risposte legate alle valutazioni (facoltativo per via delle FK)
            ValutazioneRisposte::where('id_valutazione', $valutazione->id)->delete();
        }
        // Ritorno alla pagina dell'archivio
        return redirect()->route('valutazione.gestione.archivio.index')->with('success', 'Valutazioni archiviate con successo!');
    }

    /**
    *  Mostra la lista delle valutazioni archiviate.
    *  @return View view
    */
    public function index(){
        // Prendo tutti i sondaggi del docente
        $valutazioni = Archivio::all();
        // Per ogni valutazione
        foreach($valutazioni as $valutazione){
            if($valutazione->allievi_totali != 0)
                // Aggiungo la percentuale di completamento degli allievi
                $valutazione->percentage = number_format(round($valutazione->allievi_completato / $valutazione->allievi_totali * 100, 1));
            else
                // Aggiungo la percentuale di completamento degli allievi
                $valutazione->percentage = 0;
        }
        // Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('valutazione.archivio.index', compact('valutazioni'));
    }

    /**
     * Mostra il report della valutazione archiviata.
     * @param int $id
     * @return mixed
     */
    public function show(int $id){
        // Valido la richiesta
        $validazione = Validator::make(compact('id'), [
            'id' => 'bail|required|numeric|integer|min:0|exists:archivio,id'
        ]);
        // Se fallisce la validazione
        if($validazione->fails() || auth()->guard('allievi')->check() || auth()->guard('docenti')->check())
            // Ritorno un errore
            return redirect()->back()->withErrors(['La valutazione non esiste in archivio o non hai i diritti necessari!']);
        // Prendo i dati della valutazione
        $valutazione = Archivio::with('risposte')->find($id);
        // Se non ci sono risposte
        if($valutazione->risposte->count() === 0)
            // Ritorno indietro
            return redirect()->back();
        // Ritorno la view
        return view('valutazione.archivio.show', compact('valutazione'));
    }

}
