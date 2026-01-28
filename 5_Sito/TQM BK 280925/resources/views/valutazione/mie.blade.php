@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'mie'])

@section('content')
	@if(!is_null($valutazioni))
		<div class="container-fluid">
			@include('layouts.message')
			<section class="box-typical scrollable">
				<header class="box-typical-header">
					<div class="tbl-row">
						<div class="tbl-cell tbl-cell-title">
							<h3>Le mie qualifiche</h3>
						</div>
					</div>
				</header>
				<div class="box-typical-body">
					<table id="sondaggi" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th>Nome sondaggio</th>
							@allievo
							<th>Docente</th>
							@endallievo
							<th>Classe</th>
							<th>Stato</th>
							@docente
							<th>Progresso</th>
							@enddocente
							<th>Data fine</th>
							<th>Azioni</th>
						</tr>
						</thead>
						<tbody>
						@foreach($valutazioni as $valutazione)
							<tr>
								<td>{{ $valutazione->sondaggio->nome }}</td>
								@allievo
								<td>{{ $valutazione->docente->nome.' '.$valutazione->docente->cognome }}</td>
								@endallievo
								<td>{{ $valutazione->classe->nome }}</td>
								<td><span class="label label-{{ $valutazione->stato->class }}">{{ $valutazione->stato->nome }}</span></td>
								@docente
								<td width="150">
									<div class="progress-with-amount" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $valutazione->percentage }}% ({{ $valutazione->allievi_completato }}/{{ $valutazione->allievi_totali }})">
										<div class="progress progress-xs {{ $valutazione->id_stato === 3 ? 'progress-danger' : 'progress-warning' }}">
											<div class="progress-bar" role="progressbar" style="width: {{ $valutazione->percentage }}%;"
												 aria-valuenow="{{ $valutazione->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
								</td>
								@enddocente
								<td>{{ Carbon\Carbon::parse($valutazione->fine)->formatLocalized('%d %B %Y') }}</td>
								<td>
									@if($valutazione->id_stato !== 3)
										@allievo
										@if(!$valutazione->allievi_valutazioni->where('id_allievo', auth()->guard('allievi')->user()->id)->isEmpty())
											<div class="btn-group btn-group-sm" style="float: none;">
												<a href="{{ route('valutazione.create', $valutazione->id) }}" class="btn btn-sm btn-primary" style="float: none;">
													<span class="glyphicon glyphicon-list-alt"></span>
												</a>
											</div>
										@endif
										@endallievo

										@docente
										@if(!$valutazione->docente_completato)
											<div class="btn-group btn-group-sm" style="float: none;">
												<a href="{{ route('valutazione.create', $valutazione->id) }}" class="btn btn-sm btn-primary" style="float: none;">
													<span class="glyphicon glyphicon-list-alt"></span>
												</a>
											</div>
										@endif
										@enddocente
									@else
										@docente
										<div class="btn-group btn-group-sm" style="float: none;">
											<a href="{{ route('valutazione.report', $valutazione->id) }}" class="btn btn-sm btn-success" style="float: none;">
												<span class="glyphicon glyphicon-eye-open"></span>
											</a>
										</div>
										@enddocente
									@endif
									{{--
                                        <div class="btn-group btn-group-sm" style="float: none;">
                                            <button type="button" class="btn btn-sm btn-primary" style="float: none;">
                                                <span class="glyphicon glyphicon-download"></span>
                                            </button>
                                        </div>
                                    --}}
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div><!--.box-typical-body-->
			</section><!--.box-typical-->
		</div>
	@else
		<div class="container-fluid">
			<div class="box-typical box-typical-full-height">
				<div class="add-customers-screen tbl">
					<div class="add-customers-screen-in">
						<div class="add-customers-screen-user">
							<i class="glyphicon glyphicon-list-alt"></i>
						</div>
						<h2>Nessun sondaggio trovato!</h2>
						<p class="lead color-blue-grey-lighter">Probabilmente non sei stato inserito in nessun sondaggio.<br/> Riprova più tardi.</p>
					</div>
				</div>
			</div>
		</div>
	@endif
@endsection
