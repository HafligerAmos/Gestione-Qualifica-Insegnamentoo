@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'gestione.archivio.index'])

@section('content')
    @if(!is_null($valutazioni) || !$valutazioni->isEmpty())
        <div class="container-fluid">
            @include('layouts.message')
            <section class="box-typical">
                <header class="box-typical-header">
                    <div class="tbl-row">
                        <div class="tbl-cell tbl-cell-title">
                            <h3>Archivio qualifiche</h3>
                        </div>
                    </div>
                </header>
                <div class="box-typical-body">
                    <table id="valutazioni" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nome sondaggio</th>
                            <th>Docente</th>
                            <th>Classe</th>
                            <th>Semestre</th>
                            <th>Anno</th>
                            <th>Progresso allievi</th>
                            <th>Azioni</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($valutazioni as $valutazione)
                            <tr>
                                <td>{{ $valutazione->nome_sondaggio }}</td>
                                <td>{{ $valutazione->nome_docente.' '.$valutazione->cognome_docente }}</td>
                                <td>{{ $valutazione->nome_classe }}</td>
                                <td>{{ $valutazione->semestre }}</td>
                                <td>{{ $valutazione->anno }}</td>
                                <td width="150">
                                    <div class="progress-with-amount" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $valutazione->percentage }}% ({{ $valutazione->allievi_completato }}/{{ $valutazione->allievi_totali }})">
                                        <div class="progress progress-xs {{ $valutazione->id_stato === 3 ? 'progress-danger' : 'progress-warning' }}">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $valutazione->percentage }}%;"
                                                 aria-valuenow="{{ $valutazione->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('valutazione.gestione.archivio.show', $valutazione->id) }}" class="btn btn-sm btn-primary dis-block">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
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
