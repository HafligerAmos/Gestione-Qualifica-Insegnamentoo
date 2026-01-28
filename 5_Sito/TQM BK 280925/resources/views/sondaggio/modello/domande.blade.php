@extends('layouts.master', ['group' => 'sondaggio', 'tab' => 'lista'])

@section('content')
<div class="container-fluid">
	@include('layouts.message')
		<section class="box-typical scrollable">
			 <header class="box-typical-header">
				<div class="tbl-row">
					<div class="tbl-cell tbl-cell-title">
						<h3>{{ $domande[0]->sondaggio->nome }}</h3>
					</div>
				</div>
			 </header>
			 <div class="box-typical-body">
				<table id="sondaggi" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
				   <thead>
					   <tr>
						   <th>Categoria</th>
						   <th>Definizione</th>
					   </tr>
				   </thead>
				   <tbody>
					   @foreach($domande as $domanda)
					   <tr>
						   <td>{{ $domanda->categoria->abb }}</td>
						   <td>{{ $domanda->definizione }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			 </div><!--.box-typical-body-->
		 </section><!--.box-typical-->
	 </div>
@endsection
