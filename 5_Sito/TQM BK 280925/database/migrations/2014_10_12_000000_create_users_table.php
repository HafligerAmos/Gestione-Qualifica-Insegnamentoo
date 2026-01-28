<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabella degli amministratori
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->comment('ID amministratore');
            $table->string('nome')->comment('Nome amministratore');
            $table->string('email')->unique()->comment('Email amministratore');
            $table->string('password')->comment('Password amministratore');
            $table->rememberToken();
            $table->timestamps();
        });
        // Tabella delle segretarie
        Schema::create('segretarie', function (Blueprint $table) {
            $table->increments('id')->comment('ID segretaria');
            $table->string('nome')->comment('Nome segretaria');
            $table->string('email')->unique()->comment('Email segretaria');
            $table->string('password')->comment('Password segretaria');
            $table->rememberToken();
            $table->timestamps();
        });
		// Tabella dei docenti (presi dai dati estrati dal GAGI)
        Schema::create('docenti', function (Blueprint $table) {
			$table->increments('id')->comment('ID Docente');
            $table->string('username')->unique()->comment('Username docente');
            $table->string('nome')->comment('Nome docente');
            $table->string('cognome')->comment('Cognome docente');
            $table->boolean('semestre_configurato')->default(0)->comment('Il semestre è stato configurato');
            $table->rememberToken();
        });

		// Tabella delle classi (presi dai dati estrati dal GAGI)
		Schema::create('classi', function (Blueprint $table) {
		    $table->increments('id')->comment('ID Classe');
		    $table->string('nome', 24)->comment('Nome classe');
		});

		// Tabella degli allievi (presi dai dati estrati dal GAGI)
		Schema::create('allievi', function (Blueprint $table) {
			$table->increments('id')->comment('ID Allievo');
		    $table->string('username')->unique()->comment('Username allievo');
		    $table->string('nome')->comment('Nome allievo');
		    $table->string('cognome')->comment('Cognome allievo');
		    $table->string('professione')->comment('Professione classe');
		    $table->date('nascita')->comment('Data di nascita');
		    $table->rememberToken();
		});

		// Tabella delle classi relazionate agli allievi
		Schema::create('allievi_classi', function (Blueprint $table) {
			$table->unsignedInteger('id_classe')->comment('ID Classe');
			$table->unsignedInteger('id_allievo')->comment('ID Allievo');
			$table->primary(['id_classe', 'id_allievo']);
		});
		// Tabella delle classi relazionate ai docenti FK
		Schema::table('allievi_classi', function (Blueprint $table) {
			$table->foreign('id_classe')
				  ->references('id')->on('classi')
				  ->onUpdate('cascade')
				  ->onDelete('cascade');
			$table->foreign('id_allievo')
				  ->references('id')->on('allievi')
				  ->onUpdate('cascade')
				  ->onDelete('cascade');
		});

        // Tabella per i semestri
        Schema::create('semestri', function (Blueprint $table) {
            $table->tinyIncrements('id')->comment('ID del semestre');
            $table->string('semestre')->unique()->comment('Semestre');
        });

        \App\Models\Semestre::static();

		// Tabella delle classi relazionate ai docenti
        Schema::create('docenti_classi', function (Blueprint $table) {
			$table->unsignedInteger('id_classe')->comment('ID Classe');
            $table->unsignedInteger('id_docente')->comment('ID Docente');
            $table->unsignedTinyInteger('id_semestre')->default(1)->comment('ID Semestre');
            $table->string('materia')->comment('Materia del docente per quella classe');
			$table->primary(['id_classe', 'id_docente', 'materia']);
        });
		// Tabella delle classi relazionate ai docenti FK
        Schema::table('docenti_classi', function (Blueprint $table) {
            $table->foreign('id_classe')
                  ->references('id')->on('classi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('id_docente')
                  ->references('id')->on('docenti')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('id_semestre')
                ->references('id')->on('semestri')
                ->onUpdate('cascade')
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
    }
}
