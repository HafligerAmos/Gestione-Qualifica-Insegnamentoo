<?php

namespace App\Http\Controllers\Pannello;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use DB;
use Log;
use Validator;

/**
 *  Gestisce il pannello dell'amministratore.
 *
 *  Autenticazione necessaria.
 */
class AdminController extends Controller {

	/**
	 *  Mostra la lista degli amministratori
	 *  @return View view
	 */
    public function index(){
		// Prendo tutti i sondaggi del docente
		$admins = Admin::all();
		// Se il result è vuoto, ritorno la view con l'avviso che è vuoto
		return view('pannello.amministratori.index', compact('admins'));
	}

    /**
     *  Mostra la pagina di creazione di un amministratore
     *  @return View view
     */
    public function create(){
        // Ritorno la view
        return view('pannello.amministratori.create');
    }

    /**
     * Aggiungo un nuovo amministratore
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        // Valido la richiesta
        $admin = $request->validate([
            'nome' => 'bail|required|string|min:0|max:191',
            'email' => 'bail|required|email|unique:admins,email',
            'password' => 'bail|required|string|min:0|max:100|confirmed',
        ]);
        // Cripto la password
        $admin['password'] = bcrypt($admin['password']);
        // Aggiungo l'amministratore
        Admin::create($admin);
        // Ritorno alla lista degli amministratori
        return redirect()->route('pannello.amministratori.index')->with('success', 'Amministratore aggiunto con successo!');
    }

    /**
     *  Mostro la pagina di modifica dell'amministratore
     *  @return View view
     */
    public function edit(int $id){
        // Valido la richiesta
        if(is_null($admin = Admin::find($id)))
            // Ritorno indietro
            return redirect()->back();
        // Ritorno la view
        return view('pannello.amministratori.edit', compact('admin'));
    }

    /**
     * Aggiorno i dati dell'amministratore
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id){
        // Valido la richiesta
        if(is_null(Admin::find($id)))
            // Ritorno indietro
            return redirect()->back();
        // Valido i dati
        $dati = $request->validate([
            'nome' => 'bail|required|string|min:0|max:191',
            'email' => 'bail|required|email'.($request->email !== $request->old_email ? '|unique:admins,email' : ''),
            'password' => 'bail|nullable|string|min:0|max:100|confirmed',
        ]);
        // Aggiungo l'amministratore
        $admin = Admin::find($id);
        $admin->nome = $dati['nome'];
        $admin->email = $dati['email'];
        // Se la password non è vuota
        if(!is_null($dati['password']))
            // Salvo la nuova password
            $admin->password = bcrypt($dati['password']);
        // Salvo i dati dell'amministratore
        $admin->save();
        // Ritorno alla lista degli amministratori
        return redirect()->route('pannello.amministratori.index')->with('success', 'Amministratore modificato con successo!');
    }

    /**
     * Elimino l'amministratore.
     * @param Request $request
     * @param int $id
     * @return mixed
     */
	public function destroy(Request $request, int $id){
        // Valido la richiesta
        if(is_null(Admin::find($id)))
            // Ritorno indietro
            return redirect()->back();
		// Elimino l'amministratore
		Admin::destroy($id);
		// Ritorno alla lista
		return redirect()->route('pannello.amministratori.index')->with('success', 'Amministratore eliminato con successo!');
	}
}
