@extends('layouts.master', ['group' => 'pannello', 'tab' => 'amministratori.index'])

@section('content')
	<div class="container-fluid">
		@include('layouts.message')
		<section class="box-typical scrollable">
			<header class="box-typical-header">
				<div class="tbl-row">
					<div class="tbl-cell tbl-cell-title">
						<h3>Amministratori</h3>
					</div>
					<div class="tbl-cell tbl-cell-action-bordered">
						<a href="{{ route('pannello.amministratori.create') }}" class="btn btn-primary">Aggiungi amministratore</a>
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
					@foreach($admins as $admin)
						<tr>
							<td>{{ $admin->nome }}</td>
							<td>{{ $admin->email }}</td>
							<td>
								<a href="{{ ($admin->id === auth()->guard('admin')->user()->id ? route('pannello.profilo.edit') : route('pannello.amministratori.edit', $admin->id)) }}" class="btn btn-primary dis-block">
									<span class="glyphicon glyphicon-edit" style="margin-right:10px"></span> Modifica
								</a>
								@if($admin->id !== auth()->guard(session('guard'))->user()->id && $admins->count() > 2)
									<form action="{{ route('pannello.amministratori.destroy', $admin->id) }}" method="POST" class="dis-block">
										{{ csrf_field() }}
										{{ method_field('DELETE') }}
										<input type="hidden" name="id" value="{{ $admin->id }}"/>
										<button type="submit" class="btn btn-danger btn-center">
											<span class="glyphicon glyphicon-trash"></span>
										</button>
									</form>
								@else
									<button class="btn btn-danger btn-center" disabled>
										<span class="glyphicon glyphicon-trash"></span>
									</button>
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</section>
	</div>
@endsection
