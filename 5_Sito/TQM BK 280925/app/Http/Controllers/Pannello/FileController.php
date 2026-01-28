<?php

namespace App\Http\Controllers\Pannello;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Valutazione\ArchivioController;
use \App\Models\User;
use \App\Models\Allievi;
use \App\Models\AllieviClassi;
use \App\Models\Docenti;
use \App\Models\Classi;
use \App\Models\DocentiClassi;
use \App\Models\Valutazione;
use \App\Models\ValutazioneAllievi;
use \App\Models\ValutazioneRisposte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;
use Log;
use Validator;

/**
 *  Gestisce i file del GAGI.
 *
 *  Autenticazione necessaria come amministratore.
 */
class FileController extends Controller {

    /**
     *  Mostra la pagina di importazione
     *  @return View view
     */
    public function create(){
        // Se il result è vuoto, ritorno la view con l'avviso che è vuoto
        return view('pannello.file');
    }

	/**
     *  Fix x allievi.
     *  @return View view
     */
    public function fixallievi(Request $request){
        // Valido la richiesta
        $request->validate([
            'report' => 'required|mimes:csv,txt',
        ]);
        // Se l'input è un file
        if(Input::hasFile('report')){
            $report = Input::file('report');
            // Inserisco il file del report
            Storage::disk('local')->put($report->getClientOriginalName(), file_get_contents($report->getRealPath()));
            // Svuoto le tabelle
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            AllieviClassi::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            // Prendo l'URL il file
            $file_data = '../storage/app/'.$report->getClientOriginalName();
			// Apro il collegamento al file
            $handle = fopen($file_data, 'r+');
			// Per ogni riga del csv
            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
				// Inserisco la riga nell'array del CSV
                $csv[] = $row;
            }
            // Salvo i nomi delle colonne
            $columns = $csv[0];
			// Rimuovo le colonne dai dati
            unset($csv[0]);
            // Passo tutte le righe
            foreach($csv as $data){
                // Salvo i dati in variabili per una gestione facilitata
                $cognome = $data[0];
                $nome = $data[1];
                $nascita = $data[2];
                $professione = $data[3];
                $materia = $data[5];
                // Se la materia e il docente sono presenti, oppure se la materia non è Applicazione, qualsiasi AIT, Progetti individuali e Condotta
                if (($data[6] !== '' || !empty($data[6])) &&
                    ($data[7] !== '' || !empty($data[7])) &&
                    ($data[8] !== '' || !empty($data[8])) &&
                    $data[5] !== 'Applicazione' &&
                    substr($data[5], 0, 3) !== 'AIT' &&
                    $data[5] !== 'Progetti individuali' &&
                    $data[5] !== 'Condotta'){
                    // ------------------------------- //
                    // ------------ CLASSI ----------- //
                    // ------------------------------- //
                    // Se non esiste la classe, se no passo il modello
                    $classe = Classi::where('nome', $data[6])->first();
                    // Salvo l'ID della classe
                    $classe_id = $classe->id;
                    // ------------------------------- //
                    // ----------- ALLIEVO ----------- //
                    // ------------------------------- //
                    // Genero l'username dell'utente $cognome,$nome,$data_nascita
                    $username = $this->generaUsername($cognome, $nome);
                    // Se non esiste l'allievo, se no passo il modello
                    $allievo = Allievi::where([
						'username' => $username,
					])->first();
					if(!is_null($allievo)){
	                    // Salvo l'ID dell'allievo
	                    $allievo_id = $allievo->id;
	                    // Se non esiste la docente, se no passo il modello
	                    if(is_null(AllieviClassi::where([
	                        'id_classe' => $classe_id,
	                        'id_allievo' => $allievo_id,
	                    ])->first())){
	                        // Inserisco il docente nella classe
	                        AllieviClassi::insert([
								'id_classe' => $classe_id,
		                        'id_allievo' => $allievo_id,
	                        ]);
	                    }
					} else {
						Log::info($data);
					}
                }
            }
            // Elimino il file di report
            Storage::disk('local')->delete($report->getClientOriginalName());
            // Se il result è vuoto, ritorno la view con l'avviso che è vuoto
            return redirect()->route('pannello.fix')->with('success', 'Fix eseguito con successo!');
        }
        // Ritorno alla pagina dei file dando un errore
        return redirect()->route('pannello.fix')->withErrors(['File invalido caricato!']);
    }

	/**
     *  Fix x allievi.
     *  @return View view
     */
    public function fixtotaleallievi(Request $request){
		$valutazioni = Valutazione::all();
		foreach($valutazioni as $valutazione){
			$totale_allievi = AllieviClassi::where('id_classe', $valutazione->id_classe)->count();
			if($valutazione->allievi_totali !== $totale_allievi){
				Valutazione::where('id', $valutazione->id)->update([
					'allievi_totali' => $totale_allievi
				]);
				Log::info('Allievi sistemati per id valutazione: '.$valutazione->id);
			}
		}
		// Se il result è vuoto, ritorno la view con l'avviso che è vuoto
		return redirect()->route('pannello.fix')->with('success', 'Allievi sistemati con successo!');
	}

	/**
     *  Fix x classi senza valutazioni da parte degli allievi.
     *  @return View view
     */
    public function fixclassi(Request $request){
		$valutazioni = Valutazione::all();
		foreach($valutazioni as $valutazione){
			$valutazione_allievi = ValutazioneAllievi::where('id_valutazione', $valutazione->id)->get();
			if($valutazione_allievi->isEmpty() && $valutazione->allievi_completato === 0){
				$allievi = AllieviClassi::getAllievi($valutazione->id_classe);
				foreach($allievi as $id_allievo){
					ValutazioneAllievi::insert([
						'id_valutazione' => $valutazione->id,
						'id_allievo' => $id_allievo
					]);
				}
				Log::info('Classe sistemata per id valutazione: '.$valutazione->id);
			}
		}
		// Se il result è vuoto, ritorno la view con l'avviso che è vuoto
		return redirect()->route('pannello.fix')->with('success', 'Classi sistemate con successo!');
	}

    /**
     *  Carica il report ed importa i dati nel DB.
     *  @return View view
     */
    public function store(Request $request){
        // Valido la richiesta
        $request->validate([
            'report' => 'required|mimes:csv,txt',
        ]);
        // Se l'input è un file
        if(Input::hasFile('report')){
            $report = Input::file('report');
            // Inserisco il file del report
            Storage::disk('local')->put($report->getClientOriginalName(), file_get_contents($report->getRealPath()));
			// Chiamo nella classe ArchivioController il metodo archive_all
			(new ArchivioController)->archive_all();
            // Svuoto le tabelle
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DocentiClassi::truncate();
            Allievi::truncate();
            Docenti::truncate();
            Classi::truncate();
            Valutazione::truncate();
            ValutazioneRisposte::truncate();
            ValutazioneAllievi::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            // Prendo l'URL il file
            $file_data = '../storage/app/'.$report->getClientOriginalName();
			// Apro il collegamento al file
            $handle = fopen($file_data, 'r+');
			// Per ogni riga del csv
            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
				// Inserisco la riga nell'array del CSV
                $csv[] = $row;
            }
            // Salvo i nomi delle colonne
            $columns = $csv[0];
			// Rimuovo le colonne dai dati
            unset($csv[0]);
            // Passo tutte le righe
            foreach($csv as $data){
                // Salvo i dati in variabili per una gestione facilitata
                $cognome = $data[0];
                $nome = $data[1];
                $nascita = $data[2];
                $professione = $data[3];
                $materia = $data[5];
                // Se la materia e il docente sono presenti, oppure se la materia non è Applicazione, qualsiasi AIT, Progetti individuali e Condotta
                if (($data[6] !== '' || !empty($data[6])) &&
                    ($data[7] !== '' || !empty($data[7])) &&
                    ($data[8] !== '' || !empty($data[8])) &&
                    $data[5] !== 'Applicazione' &&
                    substr($data[5], 0, 3) !== 'AIT' &&
                    $data[5] !== 'Progetti individuali' &&
                    $data[5] !== 'Condotta'){
                    // ------------------------------- //
                    // ------------ CLASSI ----------- //
                    // ------------------------------- //
                    // Se non esiste la classe, se no passo il modello
                    if(is_null($classe = Classi::where('nome', $data[6])->first())){
                        // Inserisco la classe
                        $classe = Classi::insertGetId([
                            'nome' => $data[6],
                        ]);
                    }
                    // Salvo l'ID della classe
                    $classe_id = (is_numeric($classe) ? $classe : $classe->id);
                    // ------------------------------- //
                    // ----------- ALLIEVO ----------- //
                    // ------------------------------- //
                    // Genero l'username dell'utente $cognome,$nome,$data_nascita
                    $username = $this->generaUsername($cognome, $nome);
                    // Se non esiste l'allievo, se no passo il modello
                    if(is_null($allievo = Allievi::where([
						'username' => $username,
						'nascita' => Carbon::parse($nascita),
					])->first())){
                        // Inserisco l'allievo nel database prendendo l'ID
                        $allievo = Allievi::insertGetId([
                            'username' => $username,
                            'cognome' => $cognome,
                            'nome' => $nome,
                            'professione' => $professione,
                            'nascita' => Carbon::parse($nascita),
                        ]);
                    }
                    // Salvo l'ID dell'allievo
                    $allievo_id = (is_numeric($allievo) ? $allievo : $allievo->id);
					// Se non esiste l'allievo relazionato alla classe
					if(is_null(AllieviClassi::where([
						'id_classe' => $classe_id,
						'id_allievo' => $allievo_id,
					])->first())){
						// Inserisco l'allievo relazionato alla classe
						AllieviClassi::insert([
							'id_classe' => $classe_id,
							'id_allievo' => $allievo_id,
						]);
					}
                    // ------------------------------- //
                    // ----------- DOCENTI ----------- //
                    // ------------------------------- //
                    // Prendo l'username del docente
                    $username = $this->generaUsername($data[7], $data[8]);
                    // Se non esiste il docente, se no passo il modello
                    if(is_null($docente = Docenti::where('username', $username)->first())){
                        // Inserisco il docente
                        $docente = Docenti::insertGetId([
                            'username' => $username,
                            'cognome' => $data[7],
                            'nome' => $data[8],
                        ]);
                    }
                    // Salvo l'ID della docente
                    $docente_id = (is_numeric($docente) ? $docente : $docente->id);
                    // Se non esiste la docente, se no passo il modello
                    if(is_null(DocentiClassi::where([
                        'id_classe' => $classe_id,
                        'id_docente' => $docente_id,
                        'materia' => $materia,
                    ])->first())){
                        // Inserisco il docente nella classe
                        DocentiClassi::insert([
                            'id_classe' => $classe_id,
                            'id_docente' => $docente_id,
                            'materia' => $materia,
                        ]);
                    }
                    // Se presente un secondo docente
                    if($data[9] !== "" && $data[10] !== ""){
                        // Prendo l'username del docente
                        $username = $this->generaUsername($data[9], $data[10]);
                        // Se non esiste il docente, se no passo il modello
                        if(is_null($docente2 = Docenti::where('username', $username)->first())){
                            // Inserisco il docente
                            $docente2 = Docenti::insertGetId([
                                'username' => $username,
                                'cognome' => $data[9],
                                'nome' => $data[10],
                            ]);
                        }
                        // Salvo l'ID della docente
                        $docente2_id = (is_numeric($docente2) ? $docente2 : $docente2->id);
                        // Se non esiste la docente, se no passo il modello
                        if(is_null(DocentiClassi::where([
                            'id_classe' => $classe_id,
                            'id_docente' => $docente2_id,
                            'materia' => $materia,
                        ])->first())){
                            // Inserisco il docente nella classe
                            DocentiClassi::insert([
                                'id_classe' => $classe_id,
                                'id_docente' => $docente2_id,
                                'materia' => $materia,
                            ]);
                        }

                        // Se presente un terzo docente
                        if($data[11] !== "" && $data[12] !== ""){
                            // Prendo l'username del docente
                            $username = $this->generaUsername($data[11], $data[12]);
                            // Se non esiste il docente, se no passo il modello
                            if(is_null($docente3 = Docenti::where('username', $username)->first())){
                                // Inserisco il docente
                                $docente3 = Docenti::insertGetId([
                                    'username' => $username,
                                    'cognome' => $data[11],
                                    'nome' => $data[12],
                                ]);
                            }
                            // Salvo l'ID della docente
                            $docente3_id = (is_numeric($docente3) ? $docente3 : $docente3->id);
                            // Se non esiste la docente, se no passo il modello
                            if(is_null(DocentiClassi::where([
                                'id_classe' => $classe_id,
                                'id_docente' => $docente3_id,
                                'materia' => $materia,
                            ])->first())){
                                // Inserisco il docente nella classe
                                DocentiClassi::insert([
                                    'id_classe' => $classe_id,
                                    'id_docente' => $docente3_id,
                                    'materia' => $materia,
                                ]);
                            }
                        }
                    }
                }
            }
            // Elimino il file di report
            Storage::disk('local')->delete($report->getClientOriginalName());
            // Se il result è vuoto, ritorno la view con l'avviso che è vuoto
            return redirect()->route('pannello.file')->with('success', 'Importazione eseguita con successo!');
        }
        // Ritorno alla pagina dei file dando un errore
        return redirect()->route('pannello.file')->withErrors(['File invalido caricato!']);
    }

	/**
	 *  Genera l'username per l'LDAP
	 *  @param  string $cognome Cognome
	 *  @param  string $nome    Nome
	 *  @return string          Username
	 */
    public function generaUsername ($cognome,$nome) { //data nascita formato calendario: GG.MM.AAAA // , $data_nascita
        //genera lo username e controlla che non esista gi‡, prima nel DB poi in AD
        //!max 20 char per compatibilit‡ con SAM
        //tutto minuscolo
        $cognome = strtolower($cognome);
        //cognome, solo il primo (se non corto)
        //separo sugli spazi, sostituendo i trattini con spazi
        $cognome_c = str_replace(array('-'), ' ', $cognome);
        $cognomi = explode(' ',$cognome_c);
        //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
        $cognomi=$this->ricomponi($cognomi);
        $cognome=$cognomi[0]; //parte prima del primo spazio

        $nome = strtolower($nome);
        //nome, solo il primo (se non corto)
        //separo sugli spazi, sostituendo i trattini con spazi
        $nome_c = str_replace(array('-'), ' ', $nome);
        $nomi = explode(' ',$nome_c);
        //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
        $nomi=$this->ricomponi($nomi);
        $nome=$nomi[0]; //parte prima del primo spazio
        //rimuovo le lettere particolari (accenti, umlaut), win2003 le considera come le altre nello username
        $trans = [
			'‡' => 'a',
	        '·' => 'a',
	        '‚' => 'a',
	        '‰' => 'ae',
	        'Ë' => 'e',
	        'È' => 'e',
	        'ê' => 'e',
	        'Í' => 'e',
	        'Î' => 'e',
	        'Ï' => 'i',
	        'Ì' => 'i',
	        'ì' => 'i',
	        'Ó' => 'i',
	        'Ô' => 'i',
	        'Ú' => 'o',
	        'Û' => 'o',
	        'Ù' => 'o',
	        'ˆ' => 'oe',
	        '˘' => 'u',
	        '˙' => 'u',
	        '˚' => 'u',
	        '¸' => 'ue',
			'`' => '',
	        'Á' => 'c',
	        '„' => 'a',
			'á' => 'a',
			'ö' => 'oe',
			'ò' => 'o',
			'ó' => 'o',
			'ç' => 'c',
			'ã' => 'a',
			'ñ' => 'n',
			'à' => 'a',
			'ü' => 'u',
		];

        $cognome = strtr($cognome, $trans);
        $nome = strtr($nome, $trans);

        // preparazione dati: nome e cognome senza spazi,underscore,trattini ecc.
        $cognome_a = str_replace(array(' ','-','_','/',"'",'"'), '', $cognome);
        $nome_a = str_replace(array(' ','-','_','/',"'",'"'), '', $nome);
        // provo lunghezza massima
        $lmax = 20;
        $tentativo = $cognome_a.'.'.$nome_a;
        if (strlen($tentativo) > $lmax) { //troppo lungo
            //*** provo ad accorciare il cognome se composto da trattini, prendo la parte prima del primo trattino
            //separo sui trattini
            $cognomi = explode('-',$cognome);
            //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
            $cognomi=$this->ricomponi($cognomi);
            $cognome_a=$cognomi[0]; //parte prima del primo trattino
            //tolgo gli spazi ecc.
            $cognome_a = str_replace(array(' ','-','_','/',"'",'"'), '', $cognome_a);
            //secondo tentativo
            $tentativo = $cognome_a.'.'.$nome_a;
            if (strlen($tentativo) > $lmax) { //ancora troppo lungo
                //*** provo ad accorciare il nome se composto da trattini, prendo la parte prima del primo trattino
                //separo sui trattini
                $nomi = explode('-',$nome);
                //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
                $nomi=$this->ricomponi($nomi);
                $nome_a=$nomi[0]; //parte prima del primo trattino
                //tolgo gli spazi ecc.
                $nome_a = str_replace(array(' ','-','_','/',"'",'"'), '', $nome_a);
                //terzo tentativo
                $tentativo = $cognome_a.'.'.$nome_a;
                if (strlen($tentativo) > $lmax) { //troppo lungo
                    //*** provo ad accorciare il cognome se composto da spazi, prendo la parte prima del primo spazio
                    //separo sugli spazi, sostituendo i trattini con spazi
                    $cognome_b = str_replace(array('-'), ' ', $cognome);
                    $cognomi = explode(' ',$cognome_b);
                    //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
                    $cognomi=$this->ricomponi($cognomi);
                    $cognome_a=$cognomi[0]; //parte prima del primo spazio
                    //tolgo gli spazi ecc.
                    $cognome_a = str_replace(array(' ','-','_','/',"'",'"'), '', $cognome_a);
                    //quarto tentativo
                    $tentativo = $cognome_a.'.'.$nome_a;
                    if (strlen($tentativo) > $lmax) { //troppo lungo
                        //*** provo ad accorciare il nome se composto da spazi, prendo la parte prima del primo spazio
                        //separo sugli spazi, sostituendo i trattini con spazi
                        $nome_b = str_replace(array('-'), ' ', $nome);
                        $nomi = explode(' ',$nome_b);
                        //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
                        $nomi=$this->ricomponi($nomi);
                        $nome_a=$nomi[0]; //parte prima del primo spazio
                        //tolgo gli spazi ecc.
                        $nome_a = str_replace(array(' ','-','_','/',"'",'"'), '', $nome_a);
                        //quinto tentativo
                        $tentativo = $cognome_a.'.'.$nome_a;
                        if (strlen($tentativo) > $lmax) { //troppo lungo
                            //tronco a 19 (20 meno il punto) accorciando il cognome
                            //lungh max nome = lmax - 1
                            $nome_a = substr($nome_a,0,$lmax -1); //altrimenti non riesco ad accorciare il cognome
                            $cognome_a = substr($cognome_a,0,$lmax - 1 - strlen($nome_a));
                        }
                    }
                }
            }
        }
        //recupero i valori che vanno bene
        $cognome = $cognome_a;
        $nome = $nome_a;
        //primo tentativo se esiste gi‡: nome.cognome tutto in minuscolo e senza spazi, trattini ecc.
        $username = $nome.'.'.$cognome;
        // Ritorno l'username
        return $username;
        //secondo tentativo: nome.cognomeAA  AA=ultime due cifre anno nascita
		//terzo tentativo: nome.cognomeMMAA MM mese nascita, AA anno nascita
		//quarto tentativo: nome.cognomeGGMMAA GG giorno nascita, MM mese nascita, AA anno nascita
		//quinto tentativo (disperato), aggiungo la lettera a
		//sesto tentativo (disperatoooo), aggiungo la lettera b
		//settimo e ultimo tentativo (disperatoooo), aggiungo la lettera c  e esco
    }

    //se la parte e' corta (<=3) la ricompongo con la seguente (prefissi tipo de, del, von ecc)
    public function ricomponi($s) {
        $ris = array();
        //se un elemento dell'array Ë pi˘ corto o uguale di 3 lo ricompongo con il seguente
        //eccezioni: della
        for($i=0;$i<count($s);$i++) {
            if (isset($s[$i+1])){ //esiste il seguente
                if ((strlen($s[$i]) <=3) or (strtolower($s[$i]) == 'della')) { //se pi˘ corto o uguale a 3
                    //lo combino con il seguente
                    $ris[]=$s[$i].$s[$i+1];
                    //salto il controllo del seguente
                    $i++;
                } else { //non e' piu corto, lo prendo
                    $ris[]=$s[$i];
                }
            } else { //ultimo, lo prendo
                $ris[] = $s[$i];
            }
        }
        return $ris;
    }
}
