@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'mie'])

@section('content')
<div class="container-fluid">
	@include('layouts.message')
		<section class="box-typical scrollable">
			<form method="POST" action="{{ route('valutazione.store') }}">
				{{ csrf_field() }}
				<input type="hidden" name="id" value="{{ $valutazione->id }}">

				<header class="box-typical-header">
					<div class="tbl-row">
						<div class="tbl-cell tbl-cell-title">
							<h3>{{ $sondaggio->nome }}</h3>
						</div>
						<div class="tbl-cell tbl-cell-action-bordered">
							<a href="{{ asset('assets/pdf/info_valutazioni.pdf') }}" target="_blank" class="btn btn-primary">Informazioni</a>
						</div>
						<div class="tbl-cell tbl-cell-action-bordered">
							<button type="submit" class="btn btn-primary">Salva</button>
						</div>
					</div>
				 </header>
				 <div class="box-typical-body">
					<table id="sondaggi" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
					   		<thead>
							   	<tr>
								   	<th>Categoria</th>
								   	<th>Definizione</th>
									<th>Valutazione</th>
							   </tr>
						   	</thead>
						   	<tbody>
							   	@foreach($sondaggio->domande as $domanda)
								@if(!$loop->last && $loop->index % 8 == 0)
								<tr>
									<th colspan="3" style="text-align:right">
										<ul class="smiles">
											<li><img src="/assets/img/sad.png"><img src="/assets/img/sad.png"> Quasi mai</li>
											<li><img src="/assets/img/sad.png"> Raramente</li>
											<li><img src="/assets/img/happy.png"> Spesso</li>
											<li><img src="/assets/img/happy.png"><img src="/assets/img/happy.png"> Molto spesso</li>
										</ul>
									</th>
								</tr>
								@endif
							   	<tr>
									<input type="hidden" name="D{{ $domanda->id }}" value="{{ $domanda->id }}"/>
								   	<td>{{ $domanda->categoria->abb }}</td>
								   	<td>{{ $domanda->definizione }}</td>
								   	<td>
										<div class="btn-group" data-toggle="buttons">
		   							   		@for ($i = 0; $i < $sondaggio->opzioni; $i++)
												<label class="btn btn-valutazione @if(intval(old('V'.$domanda->id)) === ($i+1)) active @endif">
													<input type="radio" name="V{{ $domanda->id }}" value="{{ $i+1 }}" @if(intval(old('V'.$domanda->id)) === ($i+1)) checked @endif autocomplete="off">{!! $smiles[$i] !!}
												</label>
		   							   		@endfor
										</div>
									</td>
								</tr>
								@endforeach
								<tr>
									<td colspan="3">
										<button type="submit" class="btn btn-primary" style="float:right;">Salva</button>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</section>
			 </div><!--.box-typical-body-->
		 </section><!--.box-typical-->
 	</div>

	<!-- Modal -->
	<div class="modal fade" id="description" role="dialog">
		<div class="modal-dialog" style="max-width: 90% !important; width: 90% !important">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Descrizione strumento di qualifica</h4>
				</div>
				<div class="modal-body" style="text-align:justified">
					<p>Questo strumento di qualifica è uno strumento pensato per migliorare il clima all'interno di una classe nella
					specifica lezione, quindi migliorarne il rendimento. Affinché abbia validità, è necessario sia applicato
					correttamente.</p>
					<p>Per avere un confronto che sia il più attendibile possibile è necessario che la qualifica sia effettuata da
					almeno 4 classi per ogni docente coinvolto.</p>
					<p>La qualifica serve a chiarire se il clima e le modalità di lavoro all'interno della classe sono favorevoli. I docenti
					rispondono alle domande in una sorta di autovalutazione della loro percezione sulle modalità di insegnamento.
					Non ricevono nessuna nota e la loro autovalutazione, sotto forma di grafico, deve essere confrontata con la
					qualifica espressa dalla classe (media della classe). I fattori che sono presi in considerazione sono:</p>
					<br>
					<b>1. Personalizzazione - Pe</b><br>
					<span class="tab">Possibilità del singolo allievo di comunicare con il docente.</span><br>
					<span class="tab">Intensità con cui il docente si dedica ad ogni singolo allievo.</span><br>
					<span class="tab">Possibilità dello studente di comunicare con il docente.</span><br><br>
					<b>2. Partecipazione - Pa</b><br>
					<span class="tab">Possibilità di contributo alla lezione.</span><br><br>
					<b>3. Indipendenza - In</b><br>
					<span class="tab">Possibilità di decisione.</span><br><br>
					<b>4. Ricerca di soluzioni - So</b><br>
					<span class="tab">Possibilità di sperimentare e di elaborare delle soluzioni.</span><br><br>
					<b>5. Differenziazione - Di</b><br>
					<span class="tab">Possibilità del singolo allievo di comunicare con il docente.</span><br><br>
					<b>6. Organizzazione - Or</b><br>
					<span class="tab">L'organizzazione della scuola permette di lavorare in un clima e in un ambiente piacevole.</span>
					<br><br>
					<p>Le risposte devono esprimere se quanto espresso nelle affermazioni accade quasi mai fino a molto spesso. È
					possibile esprimere una sola risposta per riga.</p>
					<p>Se lo scarto tra le risposte date dalla classe e quelle espresse dal docente è inferiore al 10% si ritiene
					che l’insegnamento è buono, se la differenza è situata tra il 10% e il 20% la discussione tra la classe e il
					singolo docente deve permettere di chiarire le differenze espresse e trovare le strategie necessarie per
					migliorare. Se la differenza supera il 20% occorre lavorare con più profondità e se lo si ritiene necessario
					chieder il supporto del docente che accompagna dal punto di vista pedagogico/didattico.</p>
					<p>Se 3 dei criteri superano il 20% di differenza la soglia di qualifica dell’insegnamento è giudicata critica, in
					questo caso l’accompagnamento pedagogico/didattico è necessario alfine di trovare quelle strategie e
					quei correttivi necessari al miglioramento delle proprie prestazioni.</p>
				</div>
				<div class="modal-footer">
					<a href="{{ route('valutazione.mie') }}" class="btn btn-default">Rifiuto</a>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Accetto e continuo</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('foot')
	<script>
        // La prima volta che apre la pagina gli spuntano le informazioni
        $("#description").modal({
			backdrop: 'static',
			keyboard: false,
		});
	</script>
@endsection
