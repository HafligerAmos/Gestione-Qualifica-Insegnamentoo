@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'risultati'])

@section('content')
	<div class="container-fluid">
		@include('layouts.message')
		<section class="box-typical scrollable">
			<header class="box-typical-header">
				<div class="tbl-row">
					<div class="tbl-cell tbl-cell-title">
                        <h3><span class="circle" style="background:rgb(255, 99, 132);"></span> {{ $valutazione->docente->cognome.' '.$valutazione->docente->nome }} @if(!auth()->guard('allievi')->check() && !auth()->guard('docenti')->check()), <span class="circle" style="background:rgb(54, 162, 235);"></span> Media allievi @endif </h3>
					</div>
                    @if($valutazione->risposte->count() > 0 && !auth()->guard('allievi')->check() && !auth()->guard('docenti')->check())
					<div class="tbl-cell tbl-cell-action-bordered">
						<a href="{{ route('valutazione.report', $valutazione->id) }}" class="btn btn-primary">Report</a>
					</div>
                    @endif
				</div>
			</header>
			<div class="box-typical-body">
				@if($valutazione->risposte->count() > 0)
				<table id="sondaggi" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Definizione</th>
                            <th>Valutazione</th>
                        </tr>
					</thead>
					<tbody>
					@foreach($valutazione->risposte as $risposta)
						<tr>
							<td>{{ $risposta->domanda->categoria->abb }}</td>
							<td>{{ $risposta->domanda->definizione }}</td>
							<td>
								<div class="btn-group">
									@for ($i = 0; $i < $valutazione->sondaggio->opzioni; $i++)
										<label class="btn btn-valutazione disabled"
                                            @if(intval(floor($risposta->media_pif)) === intval($risposta->risposta) && intval($risposta->risposta) === ($i+1) && !auth()->guard('allievi')->check() && !auth()->guard('docenti')->check()) style="background: linear-gradient(to left, rgb(255, 99, 132), rgb(54, 162, 235)) !important;"
											@elseif(intval($risposta->risposta) === ($i+1)) style="background: rgb(255, 99, 132) !important;"
                                            @elseif(intval(floor($risposta->media_pif)) === ($i+1) && !auth()->guard('allievi')->check() && !auth()->guard('docenti')->check()) style="background: rgb(54, 162, 235) !important;" @endif>
											<input class="hidden" disabled onClick="return;">{!! $smiles[$i] !!}
										</label>
									@endfor
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
                @else
                    <div class="add-customers-screen tbl">
                        <div class="add-customers-screen-in">
                            <h2>Nessuna risposta ricevuta!</h2>
                            <p class="lead color-blue-grey-lighter">Questa valutazione non ha ricevuto nessuna risposta nè dal docente nè dagli allievi.</p>
                        </div>
                    </div>
                @endif
		</section>
	</div><!--.box-typical-body-->
	</section><!--.box-typical-->
	</div>
@endsection
