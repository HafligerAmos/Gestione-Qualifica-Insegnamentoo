@extends('layouts.master', ['group' => 'pannello', 'tab' => 'segretarie.index'])

@section('content')
	<div class="container-fluid">
		@include('layouts.message')
		<section class="box-typical scrollable">
			<header class="box-typical-header">
				<div class="tbl-row">
					<div class="tbl-cell tbl-cell-title">
						<h3>Segretarie</h3>
					</div>
					<div class="tbl-cell tbl-cell-action-bordered">
						<a href="{{ route('pannello.segretarie.create') }}" class="btn btn-primary">Aggiungi segretaria</a>
					</div>
				</div>
			</header>
			<div class="box-typical-body">
				<table id="sondaggi" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
					<thead>
					<tr>
						<th>Nome Cognome</th>
						<th>Email</th>
						<th>Azioni</th>
					</tr>
					</thead>
					<tbody>
					@foreach($segretarie as $segretaria)
						<tr>
							<td>{{ $segretaria->nome }}</td>
							<td>{{ $segretaria->email }}</td>
							<td>
								<a href="{{ route('pannello.segretarie.edit', $segretaria->id) }}" class="btn btn-primary dis-block">
									<span class="glyphicon glyphicon-edit" style="margin-right:10px"></span> Modifica
								</a>
								<form action="{{ route('pannello.segretarie.destroy', $segretaria->id) }}" method="POST" class="dis-block">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}
									<input type="hidden" name="id" value="{{ $segretaria->id }}"/>
									<button type="submit" class="btn btn-danger btn-center">
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
@endsection
