<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\EnDecrypt;
use \App\Models\Allievi;
use \App\Models\Docenti;
use \App\Models\Admin;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest:docenti', 'guest:allievi', 'guest:admin', 'guest:segretarie'])->except(['logout', 'restore']);
    }

    /**
     * Mostro il form di login
     * @return mixed
     */
    public function showLoginForm(){
        return view('auth.login');
    }

    /**
     * Eseguo il login tramite l'LDAP.
     * @param Request $request
     * @return mixed
     */
	public function login(Request $request){
		// Validates the request
		$utente = $request->validate([
			'name' => 'bail|required|string|min:0',
			'password' => 'bail|required|string|min:0',
		]);

		// -- AUTH BYPASS --
		$bypass = false;
		$bypass_user = 'x.x';
		if($bypass && $utente['name'] === $bypass_user){
			// Se è un docente che esiste nel db e quindi ha l'accesso consentito
            if(!is_null($docente = Docenti::where('username', $utente['name'])->first())){
                // Eseguo l'autenticazione con il guard dei docenti
                Auth::guard('docenti')->login($docente);
                // Salvo nella sessione il guard corrente
                session()->put('guard', 'docenti');
            // Se è un allievo che esiste nel db e quindi ha l'accesso consentito
            } else if(!is_null($allievo = Allievi::where('username', $utente['name'])->first())){
                // Eseguo l'autenticazione con il guard degli allievi
                Auth::guard('allievi')->login($allievo);
                // Salvo nella sessione il guard corrente
                session()->put('guard', 'allievi');
			}
			// Ritorno ai sondaggi
    		return redirect()->route('home');
		}
		// -----------------

        // If the username contains a dot,
        // proceeds to perform a request to the LDAP
        if(strpos($utente['name'], '@') === false) {
            $ed = new EnDecrypt;
            // Salvo l'indirizzo del server LDAP
            $host = "http://212.117.109.242:1935/autenticami_esterno_2016.php?";
            // Salvo l'username passato dal form di login
            $username = $ed->Encrypt_Text($utente['name']);
            // Salvo la password passata dal form di login
            $password = $ed->Encrypt_Text($utente['password']);
            // URL parameters
            $parameters = http_build_query([
                'u' => $username,
                'p' => $password,
                'chi' => 'cpt'
            ], '', '&');
            // Initialize CURL
            $ch = curl_init();
            // Set options on curl request
            curl_setopt($ch, CURLOPT_URL, $host.$parameters);
            curl_setopt($ch, CURLOPT_HEADER, 0); // get the header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            // Execute CURL request
            $response = curl_exec($ch);
            // Close CURL connection
            curl_close($ch);
            // If the response hasn't returned a valid response
            if(is_null($response) || $response === "")
                // Returns to the login with a error
                return redirect()->route('login')->withErrors(['L\'accesso al server scolastico con le credenziali inserite è fallito!']);
            // Expldes response data
            $response = explode('&', $response);
            // Initialize an array for value key pair
            $data = [];
            // For each response element
            foreach($response as $value){
                $kv = explode('=', $value);
                $data[$kv[0]] = $kv[1];
            }
            // Checks for authentication
            // - If the user is not a user
            // - If the username sent is different from the received one
            if(
                (!isset($data['appartenenza']) || ($data['appartenenza'] !== "DOCENTE" && $data['appartenenza'] !== "STUDENTE") || strcmp($data['username'], $utente['name']) !== 0)
            )
               // Ritorno indietro dando l'errore dell'accesso invalido
               return redirect()->back()->withErrors(['L\'accesso è invalido']);
    		// Se è un docente che esiste nel db e quindi ha l'accesso consentito
            if(!is_null($docente = Docenti::where('username', $utente['name'])->first())){
                // Eseguo l'autenticazione con il guard dei docenti
                Auth::guard('docenti')->login($docente);
                // Salvo nella sessione il guard corrente
                session()->put('guard', 'docenti');
            // Se è un allievo che esiste nel db e quindi ha l'accesso consentito
            } else if(!is_null($allievo = Allievi::where('username', $utente['name'])->first())){
                // Eseguo l'autenticazione con il guard degli allievi
                Auth::guard('allievi')->login($allievo);
                // Salvo nella sessione il guard corrente
                session()->put('guard', 'allievi');
            } else
                // Ritorno indietro dando l'errore dell'accesso invalido
                return redirect()->back()->withErrors(['L\'accesso è invalido']);
    		// Riporto alla pagina dei sondaggi
    		return redirect()->route('valutazione.mie');
        } else {
            // Valido se l'utente che vuole accedere è un amministratore
            $admin_validation = Validator::make($request->all(), [
                'name' => 'bail|required|string|email|exists:admins,email',
            ]);
            // Valido se l'utente che vuole accedere è una segretaria
            $segretaria_validation = Validator::make($request->all(), [
                'name' => 'bail|required|string|email|exists:segretarie,email',
            ]);
            // Se non è un amministratore nè una segretaria
            if($admin_validation->fails() && $segretaria_validation->fails())
                // Ritorno indietro dando l'errore dell'accesso invalido
                return redirect()->back()->withErrors(['L\'accesso è invalido']);
            // Faccio un tentativo di autenticazione
            // Se è un amministratore
            if(!$admin_validation->fails() &&
                Auth::guard('admin')->attempt(['email' => $utente['name'], 'password' => $utente['password']])){
                // Salvo nella sessione il guard corrente
                session()->put('guard', 'admin');
            // Se è una segretaria
            } else if(!$segretaria_validation->fails() &&
                Auth::guard('segretarie')->attempt(['email' => $utente['name'], 'password' => $utente['password']])){
                // Salvo nella sessione il guard corrente
                session()->put('guard', 'segretarie');
            }
             else
                // Ritorno indietro dando l'errore dell'accesso invalido
                return redirect()->back()->withErrors(['L\'accesso è invalido']);

    		// Ritorno ai sondaggi
    		return redirect()->route('home');
        }
	}

    /**
     * Cancello la sessione dell'utente.
     * @return mixed
     */
	public function logout(){
        // Faccio il logout
        Auth::guard(session('guard'))->logout();
        // Torno alla home
        return redirect()->route('home');
    }

    /**
     * Ripristina un utente amministratore in caso
     * che non ci fosse più un accesso al pannello.
     * @param string $code
     * @return mixed
     */
    public function restore(string $code){
        // Se il codice è uguale a quello configurato nel file .env
        if($code === env('ADMIN_RESTORE_CODE')){
            // Creo il nuovo amministratore
            if(is_null(Admin::where('email', env('ADMIN_RESTORE_EMAIL'))->first())){
                // Creo l'amministratore
                Admin::create([
                    'email' => env('ADMIN_RESTORE_EMAIL'),
                    'nome' => env('ADMIN_RESTORE_NAME'),
                    'password' => bcrypt(env('ADMIN_RESTORE_PASSWORD')),
                ]);
                // Ritorno al login
                return redirect()->route('login');
            }
        }
        // Ritorno alla home
        return redirect()->route('home');
    }
}
