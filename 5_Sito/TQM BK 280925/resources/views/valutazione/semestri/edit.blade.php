@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'gestione.semestri.index'])

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <section class="box-typical scrollable">
            <header class="box-typical-header">
                <div class="tbl-row">
                    <div class="tbl-cell tbl-cell-title">
                        <h3>{{ $docente->cognome.' '.$docente->nome }}</h3>
                    </div>
                </div>
            </header>
            <div class="box-typical-body">
                <form method="POST" action="{{ route('valutazione.gestione.semestri.update', $docente->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <table class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%" data-filter-control="true" data-toolbar="#table">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Classe</th>
                                <th>Semestri</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($classi as $classe)
                            <tr>
                                <td>{{ $classe->materia }}</td>
                                <td>{{ $classe->classe->nome }}</td>
                                <td>
                                    <div class="btn-group" data-toggle="buttons">
                                        @foreach($semestri as $semestre)
                                            <label class="btn btn-semestre @if(auth()->guard('docenti')->check() && auth()->guard('docenti')->user()->id === $docente->id && $docente->semestre_configurato) disabled @endif @if($classe->id_semestre === $semestre->id) active @endif">
                                                <input type="radio" name="{{ $classe->id_classe.';;'.$classe->materia }}" value="{{ $semestre->id }}" @if($classe->id_semestre === $semestre->id) checked @endif autocomplete="off"
													@if(auth()->guard('docenti')->check() && auth()->guard('docenti')->user()->id === $docente->id && $docente->semestre_configurato) disabled onClick="return;" @endif>{{ $semestre->semestre }}
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
							@if((auth()->guard('admin')->check()) || (auth()->guard('segretarie')->check()) || (auth()->guard('docenti')->check() && auth()->guard('docenti')->user()->id === $docente->id && !$docente->semestre_configurato))
                            <tr>
                                <td colspan="3">
                                    <button type="submit" class="btn btn-primary" style="float:right;">Salva</button>
                                </td>
                            </tr>
							@endif
                        </tbody>
                    </table>
                </form>
            </div>
        </section>
    </div>
@endsection
