@extends('layouts.master', ['group' => 'sondaggio', 'tab' => 'modello.crea'])

@section('content')
<div class="container-fluid">
		@include('layouts.message')
			<header class="section-header">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h3>Crea modello sondaggio</h3>
						</div>
					</div>
				</div>
			</header>
			<section class="card">
				<div class="card-block">
					<form action="{{ route('sondaggio.modello.store') }}" method="POST">
						{{ csrf_field() }}

						<div class="row">
							<fieldset class="form-group col-md-8">
								<label class="form-label">Nome sondaggio</label>
								<input name="nome" type="text" class="form-control" required>
							</fieldset>
							<fieldset class="form-group col-md-4">
								<label class="form-label">Numero di opzioni</label>
								<select name="opzioni" class="form-control">
									{{--<option value="2">2</option>
									<option value="3">3</option>--}}
									<option value="4" selected>4</option>
									{{--<option value="5">5</option>
									<option value="6">6</option>--}}
								</select>
							</fieldset>
						</div>

						<div class="row" id="input_row">
							<fieldset class="form-group col-md-2">
								<label class="form-label">Categoria</label>
								<select name="C1" class="form-control">
									<option value="1">Pe</option>
									<option value="2">Pa</option>
									<option value="3">In</option>
									<option value="4">So</option>
									<option value="5">Di</option>
									<option value="6">Or</option>
								</select>
							</fieldset>
							<fieldset class="form-group col-md-10">
								<label class="form-label">Definizione</label>
								<input name="D1" type="text" class="form-control">
							</fieldset>
						</div>

						<div class="row" id="btns">
							<div class="col-md-12">
								<fieldset class="form-group pull-right">
									<a onClick="aggiungi()" class="btn btn-success">Aggiungi domanda</a>
									<button type="submit" class="btn btn-primary">Crea modello</button>
								</fieldset>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
@endsection

@section('foot')
	<script>
        function aggiungi(){
            var name_id = $("div[id*='input_row']").length + $("div[id*='def_row']").length + 1;
            $('#btns').before('<div class="row" id="def_row"><fieldset class="form-group col-md-2"><select name="C'+name_id+'" class="form-control"><option value="1">Pe</option><option value="2">Pa</option><option value="3">In</option><option value="4">So</option><option value="5">Di</option><option value="6">Or</option></select></fieldset><fieldset class="form-group col-md-10"><input name="D'+name_id+'" type="text" class="form-control"></fieldset></div>');
        }
	</script>
@endsection
