<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('clear', function () {
	return Artisan::call('view:clear');
});
Route::get('migrate', function(){
    if(false)
	    return Artisan::call('migrate', [
	        '--path' => 'database/migrations/temp'
        ]);
    else
        return 'Not enabled';
});

// Pagina home
Route::get('/', 'PagesController@home')->name('home');
// Pagina di informazioni sul sito
Route::get('info', 'PagesController@info')->name('info');
// Pagina del manuale sulle funzionalità del sito
//Route::get('manuale', 'PagesController@manuale')->name('manuale');

// Valutazioni sondaggi
Route::prefix('qualifica')->name('valutazione.')->namespace('Valutazione')->group(function() {
    // Valutazioni
    Route::middleware('role:docenti,allievi')->group(function(){
        // Mostra la lista delle valutazioni del docente/allievo autenticato
        Route::get('/', 'ValutazioneController@index')->name('mie');
        // Mostra le domande della valutazione del docente/allievo autenticato
        Route::get('{id}', 'ValutazioneController@create')->where(['id' => '[0-9]+'])->name('create');
        // Memorizzo la valutazione del docente/allievo
        Route::post('/', 'ValutazioneController@store')->name('store');
    });

    // Valutazioni
    Route::middleware('role:docenti,admin,segretarie')->group(function() {
		// Valutazioni visibili solo agli amministratori
		Route::middleware('role:admin,segretarie')->group(function(){
			// Mostra il report della valutazione
			Route::get('{id}/report/media', 'ValutazioneController@report_media')->where(['id' => '[0-9]+'])->name('report_media');
		});
		// Mostra il report della valutazione
		Route::get('{id}/report', 'ValutazioneController@report')->where(['id' => '[0-9]+'])->name('report');
		// Mostra il report della valutazione
		Route::get('{id}/report/stampa', 'ValutazioneController@stampa')->where(['id' => '[0-9]+'])->name('stampa');
        // Mostra i risultati della valutazione
        Route::get('{id}/risultati', 'ValutazioneController@show')->where(['id' => '[0-9]+'])->name('show');
    });

    // Gestione valutazioni
    Route::prefix('gestione')->name('gestione.')->group(function() {
        Route::middleware('role:admin,segretarie')->group(function(){
            // Mostra la lista delle valutazioni dei docenti
            Route::get('/', 'GestioneController@index')->name('index');
            // Chiude la valutazione
            Route::patch('close', 'GestioneController@close')->name('close');
            // Riapre la valutazione
            Route::patch('open', 'GestioneController@open')->name('open');
            // Elimina la valutazione
            Route::delete('/', 'GestioneController@destroy')->name('destroy');
            // Chiude tutte le valutazioni
            Route::patch('close/all', 'GestioneController@close_all')->name('close_all');
            // Archivia tutte le valutazioni
            Route::patch('archive/all', 'ArchivioController@archive_all')->name('archive_all');
        });

        // Gestione semestri docenti
        Route::middleware('role:docenti,admin,segretarie')->prefix('semestri')->name('semestri.')->group(function() {
            // Mostra la lista dei docenti per i semestri
            Route::get('/', 'SemestriController@index')->name('index');
            // Mostra i semestri del docente
            Route::get('{id}', 'SemestriController@show')->where(['id' => '[0-9]+'])->name('show');
            // Salvo i semestri del docente
            Route::patch('{id}', 'SemestriController@update')->where(['id' => '[0-9]+'])->name('update');
            // Attiva tutti i docenti
            Route::patch('activate', 'SemestriController@activate')->name('activate');
			// Disattiva tutti i docenti
            Route::patch('deactivate', 'SemestriController@deactivate')->name('deactivate');
        });

        // Gestione archivio
        Route::middleware('role:admin,segretarie')->prefix('archivio')->name('archivio.')->group(function() {
            // Mostra la lista delle valutazioni dei docenti archiviate
            Route::get('/', 'ArchivioController@index')->name('index');
            // Mostra i risultati della valutazione archiviate
            Route::get('{id}', 'ArchivioController@show')->where(['id' => '[0-9]+'])->name('show');
        });
    });
});

// Sondaggi e modelli
Route::prefix('sondaggio')->name('sondaggio.')->namespace('Sondaggio')->middleware('role:admin,segretarie')->group(function(){
    // Pagina Home
    Route::get('/', function(){
        // Ridireziono ai sondaggi dell'utente
        return redirect()->route('sondaggio.modello.lista');
    })->name('home');

    // Mostra la pagina di inizio per i sondaggi
    Route::get('inizia', 'SondaggioController@index')->name('inizia');
    // Fa partire il sondaggio, memorizzando nel database
    // tutte le valutazioni per i docenti.
    Route::post('inizia', 'SondaggioController@store');

    // Modelli di sondaggio
    Route::prefix('modello')->name('modello.')->group(function() {
        // Mostra la lista di modello di sondaggio
        Route::get('/', 'ModelloController@index')->name('lista');
        // Mostra la pagina di creazione dei modelli di sondaggio
        Route::get('crea', 'ModelloController@create')->name('crea');
        // Memorizza un nuovo modello di sondaggio
        Route::post('/', 'ModelloController@store')->name('store');
        // Mostra le domande del modello di sondaggio
        Route::get('{id}', 'ModelloController@show')->where(['id' => '[0-9]+'])->name('show');
        // Elimina il modello di sondaggio
        Route::delete('/', 'ModelloController@destroy')->name('destroy');
    });

});

// ----- PANNELLO GESTIONALE ----- \\
Route::prefix('pannello')->name('pannello.')->namespace('Pannello')->middleware('role:admin,segretarie')->group(function(){
    Route::get('/', function(){
        return redirect()->route('pannello.file');
    })->name('home');

    // Profilo
    Route::prefix('profilo')->name('profilo.')->group(function() {
        // Pagina del profilo
        Route::get('/', 'ProfiloController@edit')->name('edit');
        Route::patch('/', 'ProfiloController@update')->name('update');
    });

    // Pagina di statistiche
    Route::get('dashboard', 'PagesController@dashboard')->name('dashboard');

    // Pagina per gestire gli amministratori
    Route::get('amministratori', 'AdminController@index')->name('amministratori.index');
    Route::get('amministratori/aggiungi', 'AdminController@create')->name('amministratori.create');
    Route::post('amministratori', 'AdminController@store')->name('amministratori.store');
    Route::get('amministratori/{id}', 'AdminController@edit')->name('amministratori.edit');
    Route::patch('amministratori/{id}', 'AdminController@update')->name('amministratori.update');
    Route::delete('amministratori/{id}', 'AdminController@destroy')->name('amministratori.destroy');

    // Pagina per gestire le segretarie
    Route::get('segretarie', 'SegretarieController@index')->name('segretarie.index');
    Route::get('segretarie/aggiungi', 'SegretarieController@create')->name('segretarie.create');
    Route::post('segretarie', 'SegretarieController@store')->name('segretarie.store');
    Route::get('segretarie/{id}', 'SegretarieController@edit')->name('segretarie.edit');
    Route::patch('segretarie/{id}', 'SegretarieController@update')->name('segretarie.update');
    Route::delete('segretarie/{id}', 'SegretarieController@destroy')->name('segretarie.destroy');

    // Pagina per gestire il caricamento dei file GAGI
    Route::get('file', 'FileController@create')->name('file');
    Route::put('file', 'FileController@store')->name('file.store');
    Route::get('fix', function(){
		return view('pannello.fix');
	})->name('fix');
    Route::put('fix', 'FileController@fixallievi')->name('file.fix');
    Route::put('fixtotaleallievi', 'FileController@fixtotaleallievi')->name('allievitotali.fix');
    Route::put('fixclassi', 'FileController@fixclassi')->name('classi.fix');
});
// -------------------------------- \\


// Routes di autenticazione
Route::get('accedi', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('accedi', 'Auth\LoginController@login');
Route::get('accedi/{code}', 'Auth\LoginController@restore');
Route::post('esci', 'Auth\LoginController@logout')->name('logout');
