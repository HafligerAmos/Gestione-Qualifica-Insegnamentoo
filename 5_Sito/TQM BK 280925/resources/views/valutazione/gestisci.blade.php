@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'gestisci.index'])

@section('content')
    @if(!is_null($valutazioni) || !$valutazioni->isEmpty())
        <div class="container-fluid">
            @include('layouts.message')
            <section class="box-typical">
                <header class="box-typical-header">
                    <div class="tbl-row">
                        <div class="tbl-cell tbl-cell-title">
                            <h3>Gestisci qualifiche</h3>
                        </div>
                        @if($valutazioni->where('id_stato', '<>', 3)->count() >= 1)
                        <div class="tbl-cell tbl-cell-action">
                            <button type="button" data-toggle="modal" data-target="#close" class="btn btn-danger">Chiudi tutto</button>
                        </div>
                        @else
                        <div class="tbl-cell tbl-cell-action">
                            <button type="button" data-toggle="modal" data-target="#archive" class="btn btn-primary">Archivia tutto</button>
                        </div>
                        @endif
                    </div>
                </header>
                <div class="box-typical-body">
                    <table id="valutazioni" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
	                        <tr>
	                            <th>Nome sondaggio</th>
	                            <th>Docente</th>
	                            <th>Stato</th>
	                            <th>Classe</th>
	                            <th>Semestre</th>
	                            <th>Progresso allievi</th>
	                            <th>Scadenza</th>
	                            <th>Azioni</th>
	                        </tr>
                        </thead>
                        <tbody>
                        @foreach($valutazioni as $valutazione)
                            <tr>
                                <td>{{ $valutazione->sondaggio->nome }}</td>
                                <td>{{ $valutazione->docente->nome.' '.$valutazione->docente->cognome }}</td>
                                <td><span class="label label-{{ $valutazione->stato->class }}">{{ $valutazione->stato->nome }}</span></td>
                                <td>{{ $valutazione->classe->nome }}</td>
                                <td>{{ $valutazione->semestre->semestre }}</td>
                                <td width="150">
                                    <div class="progress-with-amount" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $valutazione->percentage }}% ({{ $valutazione->allievi_completato }}/{{ $valutazione->allievi_totali }})">
                                        <div class="progress progress-xs {{ $valutazione->id_stato === 3 ? 'progress-danger' : 'progress-warning' }}">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $valutazione->percentage }}%;"
                                                 aria-valuenow="{{ $valutazione->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ Carbon\Carbon::parse($valutazione->fine)->formatLocalized('%d %B %Y') }}</td>
                                <td>
                                    @if($valutazione->stato->id === 3)
                                        <a href="{{ route('valutazione.report', $valutazione->id) }}" class="btn btn-sm btn-primary dis-block">
                                            <span class="glyphicon glyphicon-eye-open"></span>
                                        </a>
                                    @endif
                                    @if($valutazione->stato->id === 3)
                                    <form action="{{ route('valutazione.gestione.open') }}" method="POST" class="dis-block">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                        <input type="hidden" name="id" value="{{ $valutazione->id }}"/>
                                        <button type="submit" class="btn btn-sm btn-success btn-center">
                                            <span class="glyphicon glyphicon-ok-sign"></span>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('valutazione.gestione.close') }}" method="POST" class="dis-block">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                        <input type="hidden" name="id" value="{{ $valutazione->id }}"/>
                                        <button type="submit" class="btn btn-sm btn-warning btn-center">
                                            <span class="glyphicon glyphicon-remove-sign"></span>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('valutazione.gestione.destroy') }}" method="POST" class="dis-block">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" name="id" value="{{ $valutazione->id }}"/>
                                        <button type="submit" class="btn btn-sm btn-danger btn-center">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        @if($valutazioni->where('id_stato', '<>', 3)->count() >= 1)
        <div class="modal fade" id="close" role="dialog">
            <div class="modal-dialog" style="max-width: 50% !important; width: 50% !important">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Valutazioni</h4>
                    </div>
                    <div class="modal-body" style="text-align:justified">
                        <p>Siete sicuri di voler chiudere tutte le qualifiche? <b>Questa azione è irreversibile.</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</a>
                        <form method="POST" action="{{ route('valutazione.gestione.close_all') }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <button type="submit" class="btn btn-danger">Confermo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="modal fade" id="archive" role="dialog">
            <div class="modal-dialog" style="max-width: 50% !important; width: 50% !important">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Archivia</h4>
                    </div>
                    <div class="modal-body" style="text-align:justified">
                        <p>Siete sicuri di voler archiviare tutte le qualifiche? <b>Questa azione è irreversibile.</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</a>
                            <form method="POST" action="{{ route('valutazione.gestione.archive_all') }}">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <button type="submit" class="btn btn-danger">Confermo</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="container-fluid">
            <div class="box-typical box-typical-full-height">
                <div class="add-customers-screen tbl">
                    <div class="add-customers-screen-in">
                        <div class="add-customers-screen-user">
                            <i class="glyphicon glyphicon-list-alt"></i>
                        </div>
                        <h2>Nessuna valutazione trovata!</h2>
                        <p class="lead color-blue-grey-lighter">Probabilmente non è ancora partito nessun sondaggio.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('foot')
    <script src="{{ asset('assets/js/lib/datatables-net/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $('#valutazioni').DataTable({
                scrollX:        true,
                scrollCollapse: true,
                paging:         true,
                responsive: true,
                language: {
                    "sEmptyTable":     "Nessun dato presente nella tabella",
                    "sInfo":           "Vista da _START_ a _END_ di _TOTAL_ elementi",
                    "sInfoEmpty":      "Vista da 0 a 0 di 0 elementi",
                    "sInfoFiltered":   "(filtrati da _MAX_ elementi totali)",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ".",
                    "sLengthMenu":     "Visualizza _MENU_ elementi",
                    "sLoadingRecords": "Caricamento...",
                    "sProcessing":     "Elaborazione...",
                    "sSearch":         "Cerca:",
                    "sZeroRecords":    "La ricerca non ha portato alcun risultato.",
                    "oPaginate": {
                        "sFirst":      "Inizio",
                        "sPrevious":   "Precedente",
                        "sNext":       "Successivo",
                        "sLast":       "Fine"
                    },
                    "oAria": {
                        "sSortAscending":  ": attiva per ordinare la colonna in ordine crescente",
                        "sSortDescending": ": attiva per ordinare la colonna in ordine decrescente"
                    }
                }
            }).draw();
        });
    </script>
@endsection
