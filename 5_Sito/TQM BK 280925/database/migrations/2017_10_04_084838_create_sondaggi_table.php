<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSondaggiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Tabella per i sondaggi
        Schema::create('sondaggio', function (Blueprint $table) {
            $table->increments('id')->comment('ID del sondaggio');
            $table->string('nome', 64)->comment('Nome del sondaggio');
            $table->unsignedTinyInteger('opzioni')->default(4)->comment('Numero di opzioni valide');
            $table->unsignedMediumInteger('usato')->default(0)->comment('Totale di volte usato');
            $table->timestamp('created_at')->comment('Data di creazione');
        });

		// Tabella per le categorie
		Schema::create('categoria', function (Blueprint $table) {
            $table->increments('id')->comment('ID della categoria');
			$table->string('nome')->comment('Nome della categoria');
			$table->string('abb')->comment('Abbreviazione della categoria');
		});

		// Tabella per le domande
        Schema::create('sondaggio_domande', function (Blueprint $table) {
            $table->increments('id')->comment('ID della domanda');
            $table->unsignedInteger('id_sondaggio')->comment('ID del sondaggio');
            $table->unsignedInteger('id_categoria')->comment('ID della categoria');
            $table->text('definizione')->comment('Definizione della domanda');
        });
		// Domande FK
		Schema::table('sondaggio_domande', function (Blueprint $table) {
			// Sondaggi
			$table->foreign('id_sondaggio')
				->references('id')->on('sondaggio')
				->onDelete('cascade');
			// Categorie
			$table->foreign('id_categoria')
			 	->references('id')->on('categoria')
				->onDelete('cascade');
		});

		// Tabella per le stato
		Schema::create('stato', function (Blueprint $table) {
            $table->TinyIncrements('id')->comment('ID dello stato');
			$table->string('nome', 16)->comment('Nome dello stato');
			$table->string('class', 16)->comment('Classe CSS');
		});

        // Tabella per gli anni scolastici
        Schema::create('anni', function (Blueprint $table) {
            $table->tinyIncrements('id')->comment('ID dell\'anno');
            $table->string('anno')->unique()->comment('Anno (e.g 2017-2018)');
        });

		// Tabella per i dati del sondaggio
        Schema::create('valutazione', function (Blueprint $table) {
            $table->increments('id')->comment('ID Valutazione della classe del docente');
            $table->unsignedInteger('id_sondaggio')->comment('ID del sondaggio');
            $table->unsignedInteger('id_docente')->comment('ID Docente');
            $table->unsignedInteger('id_classe')->comment('ID Classe');
            $table->unsignedTinyInteger('id_anno')->comment('ID Anno');
            $table->unsignedTinyInteger('id_semestre')->comment('ID Semestre');
            $table->unsignedTinyInteger('id_stato')->comment('ID Stato');
            $table->boolean('docente_completato')->default(0)->comment('Booleano se il docente lo ha completato');
            $table->unsignedTinyInteger('allievi_completato')->comment('Totale degli allievi che lo hanno completato');
            $table->unsignedTinyInteger('allievi_totali')->comment('Totale degli allievi nella classe');
			$table->datetime('fine')->comment('Data di fine');
			$table->unique(['id_sondaggio', 'id_docente', 'id_classe', 'id_anno', 'id_semestre'], 'valutazioni_unique_key');
        });
		// Dati del sondaggio FK
		Schema::table('valutazione', function (Blueprint $table) {
			// Sondaggi
			$table->foreign('id_sondaggio')
				  ->references('id')->on('sondaggio')
				  ->onDelete('cascade');
  			// Docenti
  			$table->foreign('id_docente')
  				  ->references('id')->on('docenti')
  				  ->onDelete('cascade');
  			// Classi
  			$table->foreign('id_classe')
  				  ->references('id')->on('classi')
  				  ->onDelete('cascade');
            // Anni
            $table->foreign('id_anno')
                ->references('id')->on('anni')
                ->onDelete('cascade');
            // Semestri
            $table->foreign('id_semestre')
                ->references('id')->on('semestri')
                ->onDelete('cascade');
			// Stato
			$table->foreign('id_stato')
				  ->references('id')->on('stato')
				  ->onDelete('cascade');
		});

        // Tabella per le risposte dei docenti e dei PIF alle domande dei sondaggi
        Schema::create('valutazione_risposte', function (Blueprint $table) {
            $table->unsignedInteger('id_valutazione')->comment('ID del sondaggio relazionato al docente');
            $table->unsignedInteger('id_domanda')->comment('ID della domanda');
            $table->unsignedTinyInteger('risposta')->default(0)->comment('Valore della risposta alla domanda');
            $table->unsignedDecimal('media_pif', 4, 2)->default(0)->comment('Media delle risposte dei PIF per quella domanda');
            $table->primary(['id_valutazione', 'id_docente', 'id_domanda'], 'valutazione_risposte_primary_key');
        });
        // Risposte dei sondaggi FK
        Schema::table('valutazione_risposte', function (Blueprint $table) {
            // Sondaggio
            $table->foreign('id_valutazione')
                ->references('id')->on('valutazione')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // Domande
            $table->foreign('id_domanda')
                ->references('id')->on('sondaggio_domande')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        // Tabella per gli allievi che devono fare la valutazione
        Schema::create('valutazione_allievi', function (Blueprint $table) {
            $table->unsignedInteger('id_valutazione')->comment('ID del sondaggio relazionato al docente');
            $table->unsignedInteger('id_allievo')->comment('ID allievo');
            $table->primary(['id_valutazione', 'id_allievo'], 'valutazione_allievi_primary_key');
        });

        // Tabella per gli allievi che devono fare la valutazione FK
        Schema::table('valutazione_allievi', function (Blueprint $table) {
            // Sondaggio
            $table->foreign('id_valutazione')
                ->references('id')->on('valutazione')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // Docenti
            $table->foreign('id_allievo')
                ->references('id')->on('allievi')
                ->onDelete('cascade');
        });

		\App\Models\Stato::static();
		\App\Models\Categoria::static();
		\App\Models\Anno::static();

        // Tabella per le valutazioni archiviate
        Schema::create('archivio', function (Blueprint $table) {
            $table->increments('id')->comment('ID della valutazione nell\'archivio');
            // Dati sondaggio
            $table->string('nome_sondaggio', 64)->comment('Nome del sondaggio');
            $table->unsignedTinyInteger('opzioni')->comment('Numero di opzioni valide');
            $table->unsignedMediumInteger('usato')->comment('Totale di volte usato');
            $table->timestamp('created_at')->comment('Data di creazione');
            // Dati valutazione
            $table->string('nome_docente')->comment('Nome docente');
            $table->string('cognome_docente')->comment('Cognome docente');
            $table->string('materia')->comment('Materia del docente per quella classe');
            $table->string('professione')->comment('Professione classe');
            $table->string('nome_classe', 24)->comment('Nome classe');
            $table->string('semestre')->comment('Semestre');
            $table->string('anno')->comment('Anno (e.g 2017-2018)');
            $table->unsignedTinyInteger('allievi_completato')->comment('Totale degli allievi che lo hanno completato');
            $table->unsignedTinyInteger('allievi_totali')->comment('Totale degli allievi nella classe');
        });
        // Tabella per le risposte delle valutazioni archiviate
        Schema::create('archivio_risposte', function (Blueprint $table) {
            $table->unsignedInteger('id_archivio')->comment('ID della valutazione nell\'archivio');
            $table->string('nome_categoria')->comment('Nome della categoria');
            $table->unsignedTinyInteger('docente')->default(0)->comment('Somma basata sulle categorie del docente');
            $table->unsignedDecimal('media_pif', 4, 2)->default(0)->comment('Media delle risposte dei PIF basata sulla categoria');
            $table->primary(['id_archivio', 'nome_categoria']);
        });
        // Tabella per le risposte delle valutazioni archiviate FK
        Schema::table('archivio_risposte', function (Blueprint $table) {
            $table->foreign('id_archivio')
                ->references('id')->on('archivio')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		//
    }
}
