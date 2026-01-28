@if(session()->has('success'))
<div class="alert alert-success alert-fill alert-close alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
	{{ session()->get('success') }}
</div>
@endif
@if (is_array($errors) && !empty($errors))
	@foreach ($errors as $error)
		<div class="alert alert-danger alert-fill alert-close alert-dismissible fade show" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			{{ $error }}
		</div>
	@endforeach
@endif
@if (!is_array($errors) && $errors->any())
	@if(in_array('validation.required', $errors->toArray()))
		<div class="alert alert-danger alert-fill alert-close alert-dismissible fade show" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			Assicurati di aver completato tutti i campi della valutazione.
		</div>
	@else
	    @foreach($errors->all() as $error)
		<div class="alert alert-danger alert-fill alert-close alert-dismissible fade show" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			{{ $error }}
		</div>
	    @endforeach
	@endif
@endif
