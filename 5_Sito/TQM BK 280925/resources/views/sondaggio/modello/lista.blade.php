@extends('layouts.master', ['group' => 'sondaggio', 'tab' => 'lista'])

@section('content')
<div class="container-fluid">
	@include('layouts.message')
	@if(!is_null($sondaggi) || !$sondaggi->isEmpty())
		<section class="box-typical scrollable">
			 <header class="box-typical-header">
				<div class="tbl-row">
					<div class="tbl-cell tbl-cell-title">
						<h3>Lista modelli sondaggi</h3>
					</div>

					<div class="tbl-cell tbl-cell-action-bordered">
						<a href="{{ route('sondaggio.modello.crea') }}" class="btn btn-primary">Crea modello sondaggio</a>
					</div>
				</div>
			 </header>
			 <div class="box-typical-body">
				<table id="sondaggi" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
					   <tr>
						   <th>Nome sondaggio</th>
						   <th>Usato</th>
						   <th>Creato</th>
						   <th>Azioni</th>
					   </tr>
				   </thead>
				   <tbody>
					   @foreach($sondaggi as $sondaggio)
					   <tr>
						   <td><a href="{{ route('sondaggio.modello.show', ['id' => $sondaggio->id]) }}">{{ $sondaggio->nome }}</a></td>
						   <td>{{ $sondaggio->usato }} volte</td>
						   <td>{{ Carbon\Carbon::parse($sondaggio->created_at)->formatLocalized('%d %B %Y') }}</td>
						   <td>
						   		<a href="{{ route('sondaggio.modello.show', ['id' => $sondaggio->id]) }}" class="btn btn-sm btn-primary dis-block">
								   <span class="glyphicon glyphicon-list-alt"></span>
							    </a>
							   	<form action="{{ route('sondaggio.modello.destroy') }}" method="POST" class="dis-block">
								   	{{ csrf_field() }}
								   	{{ method_field('DELETE') }}
							   		<input type="hidden" name="id" value="{{ $sondaggio->id }}"/>
									<button type="submit" class="btn btn-sm btn-danger btn-center">
										<span class="glyphicon glyphicon-trash"></span>
									</button>
							   </form>
						   </td>
						</tr>
						@endforeach
					</tbody>
				</table>
			 </div><!--.box-typical-body-->
		 </section><!--.box-typical-->
	@else
					<div class="box-typical box-typical-full-height">
						<div class="add-customers-screen tbl">
							<div class="add-customers-screen-in">
								<div class="add-customers-screen-user">
									<i class="glyphicon glyphicon-list-alt"></i>
								</div>
								<h2>Nessun sondaggio trovato!</h2>
								<p class="lead color-blue-grey-lighter">Creane uno nuovo adesso.<br/> <a href="{{ route('sondaggio.modello.crea') }}" class="btn btn-primary">Crea sondaggio</a> </p>
							</div>
						</div>
					</div>
	@endif
</div>
@endsection
