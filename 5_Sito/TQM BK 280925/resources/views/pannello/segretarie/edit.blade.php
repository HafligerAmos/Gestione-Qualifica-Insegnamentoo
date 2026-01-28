@extends('layouts.master', ['group' => 'pannello', 'tab' => 'segretarie.index'])

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h3>Modifica segretaria</h3>
                    </div>
                </div>
            </div>
        </header>
        <section class="card">
            <div class="card-block">
                <form action="{{ route('pannello.segretarie.update', $segretaria->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input type="hidden" name="old_email" value="{{ $segretaria->email }}">

                    <div class="row">
                        <fieldset class="form-group col-md-6">
                            <label class="form-label">Nome Cognome</label>
                            <input name="nome" class="form-control" value="{{ $segretaria->nome }}">
                        </fieldset>
                        <fieldset class="form-group col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $segretaria->email }}">
                        </fieldset>
                    </div>
                    <div class="row">
                        <fieldset class="form-group col-md-6">
                            <label class="form-label">Password </label>
                            <input type="password" name="password" class="form-control" value="">
                            <small class="text-muted">Lasciare vuoto se non si vuole applicare una modifica.</small>
                        </fieldset>
                        <fieldset class="form-group col-md-6">
                            <label class="form-label">Conferma password</label>
                            <input type="password" name="password_confirmation" class="form-control" value="">
                        </fieldset>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group pull-right">
                                <button type="submit" class="btn btn-primary">Modifica</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection