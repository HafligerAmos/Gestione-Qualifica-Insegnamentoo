@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'mie'])
@section('content')
	<div class="container-fluid">
		@include('layouts.message')
		<section class="box-typical scrollable">
			<header class="box-typical-header">
				<div class="tbl-row">
					<div class="tbl-cell tbl-cell-title">
						<h3>Report</h3>
					</div>
					@if($valutazione->risposte->count() > 0)
					<div class="tbl-cell tbl-cell-action-bordered">
						<a href="{{ route('valutazione.stampa', $valutazione->id) }}" class="btn btn-primary">Stampa</a>
					</div>
					<div class="tbl-cell tbl-cell-action-bordered">
						<a href="{{ route('valutazione.show', $valutazione->id) }}" class="btn btn-primary">Risultati</a>
					</div>
					@endif
					{{--
                    <div class="tbl-cell tbl-cell-action-bordered">
                        <a href="{{ route('valutazione.report_media', $valutazione->id) }}" class="btn btn-primary">Report media</a>
                    </div>
                    --}}
				</div>
			</header>
			<div class="box-typical-body" id="report">
				@if($valutazione->risposte->count() > 0)
				<section>
					<table id="docente" class="row-border order-column display table table-striped" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th>Cognome Nome</th>
							<th>Materia insegnata</th>
							<th>Professione</th>
							<th>Classe interessata</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>{{ $valutazione->docente->cognome.' '.$valutazione->docente->nome }}</td>
							<td>{{ $docente_classe->materia }}</td>
							<td>@foreach($valutazione->allievi->groupBy('professione')->toArray() as $professione => $allievi) {{ $professione }}, @endforeach</td>
							<td>{{ $valutazione->classe->nome }}</td>
						</tr>
						</tbody>
					</table>
				</section>
				<p style="padding-left:10px;font-weight:bold;color">Riassuntivo in forma tabellare</p>
				<section>
					<table id="media" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th></th>
							@foreach($categorie as $categoria)
								<th>{{ $categoria->abb }}</th>
							@endforeach
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>MEDIA ALLIEVI</td>
							@foreach($categorie as $categoria)
								<td>{{ number_format((float)($valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('media_pif') / ($valutazione->allievi_completato > 0 ? $valutazione->allievi_completato : 1)), 2, '.', '') }}</td>
							@endforeach
						</tr>
						<tr>
							<td>DOCENTE</td>
							@foreach($categorie as $categoria)
								<td>{{ $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('risposta') }}</td>
							@endforeach
						</tr>
						</tbody>
					</table>
				</section>
				<p style="padding-left:10px;font-weight:bold">Legenda</p>
				<p style="padding-left:10px">@foreach($categorie as $categoria){{ $categoria->abb }} = {{ $categoria->nome }}&nbsp;&nbsp;&nbsp;@endforeach</p>
				<p style="padding-left:10px;font-weight:bold">Riassuntivo in forma grafica</p>
				<section style="width:100%; margin-bottom:30px">
					<canvas id="graph" width="350" height="350" style="max-width:600px !important; max-height:600px !important;margin:auto"></canvas>
				</section>
				@else
				<div class="add-customers-screen tbl">
					<div class="add-customers-screen-in">
						<h2>Nessuna risposta ricevuta!</h2>
						<p class="lead color-blue-grey-lighter">Questa valutazione non ha ricevuto nessuna risposta nè dal docente nè dagli allievi.</p>
					</div>
				</div>
				@endif
			</div><!--.box-typical-body-->
		</section><!--.box-typical-->
	</div>
@endsection

@if($valutazione->risposte->count() > 0)
@section('foot')
	<script type="text/javascript" src="{{ asset('assets/js/lib/chartjs/chart.min.js') }}"></script>
	<script>
        new Chart($("#graph"), {
            type: 'radar',
            data: {
                labels: [@foreach($categorie as $categoria)"{{ $categoria->abb }}",@endforeach],
                datasets: [
                    {
                        label: 'DOCENTE',
                        data: [@foreach($categorie as $categoria){{ $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('risposta') }},@endforeach],
                        borderColor: "rgb(255, 99, 132)",
                        fill: true,
                        backgroundColor:"rgba(255, 99, 132, 0.2)",
                        borderColor: "rgb(255, 99, 132)",
                        pointBackgroundColor:"rgb(255, 99, 132)",
                        pointBorderColor:"rgb(255, 99, 132)",
                        pointHoverBackgroundColor:"#fff",
                        pointHoverBorderColor:"rgb(255, 99, 132)",
                        borderWidth: 3
                    },
                    {
                        label: 'MEDIA ALLIEVI',
                        data: [@foreach($categorie as $categoria){{ number_format((float)($valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('media_pif') / ($valutazione->allievi_completato > 0 ? $valutazione->allievi_completato : 1)), 2, '.', '') }},@endforeach],
                        fill: true,
                        backgroundColor:"rgba(54, 162, 235, 0.1)",
                        borderColor: "rgb(54, 162, 235)",
                        pointBackgroundColor:"rgb(54, 162, 235)",
                        pointBorderColor:"rgb(54, 162, 235)",
                        pointHoverBackgroundColor:"#fff",
                        pointHoverBorderColor:"rgb(54, 162, 235)",
                        pointBorderWidth: 4
                    },
                ]
            },
            options:{
                elements:{
                    line:{
                        tension:0,
                        borderWidth:3
                    }
                },
                scale: {
                    ticks: {
                        beginAtZero: true,
                        max: {{ $valutazione->sondaggio->opzioni * $valutazione->risposte->groupBy('domanda.categoria.id')->max()->count() }},
                    },
					pointLabels: {
			      		fontSize: 16
				    }
                }
            },
        });
	</script>
@endsection
@endif
