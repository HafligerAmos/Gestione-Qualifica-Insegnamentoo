@extends('layouts.master', ['group' => 'pannello', 'tab' => 'file'])

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h3>FIX ALLIEVI</h3>
                    </div>
                </div>
            </div>
        </header>
        <section class="card">
            <div class="card-block">
                <form action="{{ route('pannello.file.fix') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <fieldset class="form-group col-md-12">
                            <label class="form-label">Report GAGI</label>
                            <input class="btn btn-default" type="file" name="report" />
                        </fieldset>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group pull-right">
                                <button type="submit" class="btn btn-primary">Importa dati</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
				<form action="{{ route('pannello.allievitotali.fix') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group pull-right">
                                <button type="submit" class="btn btn-primary">Sistema totale allievi</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
				<form action="{{ route('pannello.classi.fix') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group pull-right">
                                <button type="submit" class="btn btn-primary">Sistema classi</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
