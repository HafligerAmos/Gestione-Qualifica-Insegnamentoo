@extends('layouts.master', ['group' => 'sondaggio', 'tab' => 'inizia'])

@section('content')
<div class="container-fluid">
		@include('layouts.message')
			<header class="section-header">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h3>Inizia sondaggio</h3>
						</div>
					</div>
				</div>
			</header>
			<section class="card">
				<div class="card-block">
					<form action="{{ route('sondaggio.inizia') }}" method="POST">
						{{ csrf_field() }}

						<div class="row">
							<fieldset class="form-group col-md-8">
								<label class="form-label">Scegli sondaggio</label>
								<select name="sondaggio" class="form-control">
									@foreach($sondaggi as $sondaggio)
										<option value="{{ $sondaggio->id }}">{{ $sondaggio->nome }}</option>
									@endforeach
								</select>
							</fieldset>
							<fieldset class="form-group col-md-4">
								<label class="form-label">Numero classi</label>
								<select name="classi" class="form-control">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3" selected>3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</fieldset>
						</div>
						<div class="row">
							<fieldset class="form-group col-md-4">
								<label class="form-label">Scegli anno</label>
								<select name="anno" class="form-control">
									@foreach($anni as $anno)
										<option value="{{ $anno->id }}">{{ $anno->anno }}</option>
									@endforeach
								</select>
							</fieldset>
							<fieldset class="form-group col-md-4">
								<label class="form-label">Scegli semestre</label>
								<select name="semestre" class="form-control">
									@foreach($semestri as $semestre)
										<option value="{{ $semestre->id }}">{{ $semestre->semestre }}</option>
									@endforeach
								</select>
							</fieldset>
							<fieldset class="form-group col-md-4">
								<label class="form-label">Durata in settimane (max. 15)</label>
								<input name="durata" class="form-control" value="{{ is_null(old('durata')) ? '4' : old('durata') }}">
							</fieldset>
						</div>

						<div class="row" id="btns">
							<div class="col-md-12">
								<fieldset class="form-group pull-right">
									<button type="submit" class="btn btn-primary">Inizia sondaggio</button>
								</fieldset>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
@endsection
