<?php

namespace App\Http\Controllers\Pannello;

use App\Http\Controllers\Controller;
use App\Models\Segretaria;
use Illuminate\Http\Request;
use DB;
use Log;
use Validator;

/**
 *  Gestisce il pannello della segretaria.
 *
 *  Autenticazione necessaria.
 */
class SegretarieController extends Controller {

    /**
     *  Mostra la lista delle segretarie
     *  @return View view
     */
    public function index(){
        // Prendo tutti i sondaggi del docente
        $segretarie = Segretaria::all();
        // Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('pannello.segretarie.index', compact('segretarie'));
    }

    /**
     *  Mostra la pagina di creazione di una segretaria
     *  @return View view
     */
    public function create(){
        // Ritorno la view
        return view('pannello.segretarie.create');
    }

    /**
     * Aggiungo una nuovo segretaria
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        // Valido la richiesta
        $segretaria = $request->validate([
            'nome' => 'bail|required|string|min:0|max:191',
            'email' => 'bail|required|email|unique:segretarie,email',
            'password' => 'bail|required|string|min:0|max:100|confirmed',
        ]);
        // Cripto la password
        $segretaria['password'] = bcrypt($segretaria['password']);
        // Aggiungo la segretaria
        Segretaria::create($segretaria);
        // Ritorno alla lista degli segretarie
        return redirect()->route('pannello.segretarie.index')->with('success', 'Segretaria aggiunta con successo!');
    }

    /**
     *  Mostro la pagina di modifica della segretaria
     *  @return View view
     */
    public function edit(int $id){
        // Valido la richiesta
        if(is_null($segretaria = Segretaria::find($id)))
            // Ritorno indietro
            return redirect()->back();
        // Ritorno la view
        return view('pannello.segretarie.edit', compact('segretaria'));
    }

    /**
     * Aggiorno i dati della segretaria
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id){
        // Valido la richiesta
        if(is_null(Segretaria::find($id)))
            // Ritorno indietro
            return redirect()->back();
        // Valido i dati
        $dati = $request->validate([
            'nome' => 'bail|required|string|min:0|max:191',
            'email' => 'bail|required|email'.($request->email !== $request->old_email ? '|unique:segretarie,email' : ''),
            'password' => 'bail|nullable|string|min:0|max:100|confirmed',
        ]);
        // Aggiungo la segretaria
        $segretaria = Segretaria::find($id);
        $segretaria->nome = $dati['nome'];
        $segretaria->email = $dati['email'];
        // Se la password non è vuota
        if(!is_null($dati['password']))
            // Salvo la nuova password
            $segretaria->password = bcrypt($dati['password']);
        // Salvo i dati della segretaria
        $segretaria->save();
        // Ritorno alla lista degli segretarie
        return redirect()->route('pannello.segretarie.index')->with('success', 'Segretaria modificata con successo!');
    }

    /**
     * Elimino la segretaria.
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function destroy(Request $request, int $id){
        // Valido la richiesta
        if(is_null(Segretaria::find($id)))
            // Ritorno indietro
            return redirect()->back();
        // Elimino la segretaria
        Segretaria::destroy($id);
        // Ritorno alla lista
        return redirect()->route('pannello.segretarie.index')->with('success', 'Amministratore eliminato con successo!');
    }
}
