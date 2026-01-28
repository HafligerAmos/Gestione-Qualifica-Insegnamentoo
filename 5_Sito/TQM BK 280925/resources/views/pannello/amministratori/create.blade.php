@extends('layouts.master', ['group' => 'pannello', 'tab' => 'inizia'])

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h3>Aggiungi amministratore</h3>
                    </div>
                </div>
            </div>
        </header>
        <section class="card">
            <div class="card-block">
                <form action="{{ route('pannello.amministratori.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="row">
                        <fieldset class="form-group col-md-8">
                            <label class="form-label">Nome Cognome</label>
                            <input name="nome" class="form-control" value="{{ old('nome') }}">
                        </fieldset>
                    </div>
                    <div class="row">
                        <fieldset class="form-group col-md-8">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </fieldset>
                    </div>
                    <div class="row">
                        <fieldset class="form-group col-md-8">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" value="">
                        </fieldset>
                    </div>
                    <div class="row">
                        <fieldset class="form-group col-md-8">
                            <label class="form-label">Conferma password</label>
                            <input type="password" name="password_confirmation" class="form-control" value="">
                        </fieldset>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group pull-right">
                                <button type="submit" class="btn btn-primary">Aggiungi amministratore</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection