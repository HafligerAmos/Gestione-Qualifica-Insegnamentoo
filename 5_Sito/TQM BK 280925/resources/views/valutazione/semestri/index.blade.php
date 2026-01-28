@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'gestisci.semestri.index'])

@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <section class="box-typical">
            <header class="box-typical-header">
                <div class="tbl-row">
                    <div class="tbl-cell tbl-cell-title">
                        <h3>Gestisci semestri</h3>
                    </div>
					<div class="tbl-cell tbl-cell-action">
						<button type="button" data-toggle="modal" data-target="#activate" class="btn btn-success">Attiva tutti</button>
					</div>
					<div class="tbl-cell tbl-cell-action">
						<button type="button" data-toggle="modal" data-target="#deactivate" class="btn btn-danger">Disattiva tutti</button>
					</div>
                </div>
            </header>
            <div class="box-typical-body">
                <table id="semestri" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%" data-filter-control="true" data-toolbar="#table">
                    <thead>
                        <tr>
                            <th>Docente</th>
                            <th>Classi</th>
                            <th>Attivato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($docenti as $docente)
                        <tr>
                            <td><a href="{{ route('valutazione.gestione.semestri.show', $docente->id) }}">{{ $docente->cognome.' '.$docente->nome }}</a></td>
                            <td>{{ $classi->where('id_docente', $docente->id)->count() }}</td>
                            <td><span class="circle big" style="margin-left:calc(50% - 7.5px);background:{{ $docente->semestre_configurato ? '#46c35f' : '#ff561c' }}; box-shadow: 0 0px 10px 0 {{ $docente->semestre_configurato ? '#46c35f' : '#ff561c' }};"></span></td>
                            <td>
                                <a href="{{ route('valutazione.gestione.semestri.show', $docente->id) }}" class="btn btn-sm btn-primary dis-block">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
	<div class="modal fade" id="activate" role="dialog">
		<div class="modal-dialog" style="max-width: 50% !important; width: 50% !important">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Gestione semestri</h4>
				</div>
				<div class="modal-body" style="text-align:justified">
					<p>Siete sicuri di voler attivare tutti i docenti? <b>Questa azione è irreversibile.</b></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</a>
					<form method="POST" action="{{ route('valutazione.gestione.semestri.activate') }}">
						{{ csrf_field() }}
						{{ method_field('PATCH') }}
						<button type="submit" class="btn btn-danger">Confermo</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="deactivate" role="dialog">
		<div class="modal-dialog" style="max-width: 50% !important; width: 50% !important">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Gestione semestri</h4>
				</div>
				<div class="modal-body" style="text-align:justified">
					<p>Siete sicuri di voler disattivare tutti i docenti? <b>Questa azione è irreversibile.</b></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</a>
					<form method="POST" action="{{ route('valutazione.gestione.semestri.deactivate') }}">
						{{ csrf_field() }}
						{{ method_field('PATCH') }}
						<button type="submit" class="btn btn-danger">Confermo</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('foot')
    <script src="{{ asset('assets/js/lib/datatables-net/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $('#semestri').DataTable({
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
