<?php

namespace App\Http\Controllers\Pannello;

use App\Http\Controllers\Controller;
use App\Models\Segretaria;
use App\Models\Admin;
use Illuminate\Http\Request;
use DB;
use Validator;

/**
 *  Gestisce il profilo.
 *
 *  Autenticazione necessaria.
 */
class ProfiloController extends Controller {

    /**
     *  Mostro la pagina di modifica del profilo
     *  @return View view
     */
    public function edit(){
        // Se la sessione contiene il guard
        if(session()->has('guard'))
            // Prendo i dati del profilo
            $profilo = auth()->guard(session('guard'))->user();
        else if(auth()->guard('admin')->check())
            // Prendo i dati del profilo
            $profilo = auth()->guard('admin')->user();
        else if(auth()->guard('segretarie')->check())
            // Prendo i dati del profilo
            $profilo = auth()->guard('segretarie')->user();
        else
            // Ritorno indietro
            return redirect()->back();
        // Ritorno la view
        return view('pannello.profilo.edit', compact('profilo'));
    }

    /**
     * Aggiorno i dati del profilo
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request){
        // Se è un amministratore
        if(auth()->guard('admin')->check()){
			// Salvo l'ID
			$id = auth()->guard('admin')->user()->id;
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
        } else if(auth()->guard('segretarie')->check()){
            // Salvo l'ID
            $id = auth()->guard('segretarie')->user()->id;
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
        } else
            // Ritorno indietro
            return redirect()->back();
        // Ritorno al profilo
        return redirect()->route('pannello.profilo.edit')->with('success', 'Profilo modificato con successo!');
    }
}
