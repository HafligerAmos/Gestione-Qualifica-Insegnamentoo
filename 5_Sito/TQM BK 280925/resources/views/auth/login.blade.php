@extends('layouts.auth', ['page' => 'auth'])

@section('content')
<form class="sign-box" method="POST" action="{{ route('login') }}">
		{{ csrf_field() }}
	    <div class="sign-avatar">
	        <img src="{{ asset('assets/img/avatar-sign.png') }}" alt="">
	    </div>
	    <header class="sign-title">Accedi</header>
		@include('layouts.message')
	    <div class="form-group">
	        <input class="form-control" id="name" type="text" name="name" placeholder="nome.cognome" value="{{ old('name') }}" required autofocus />
	    </div>
	    <div class="form-group">
	        <input class="form-control" id="password" type="password" name="password" placeholder="Password" required />
	    </div>
	    <div class="form-group">
	        <div class="checkbox float-left">
	            <input type="checkbox" name="remember" id="signed-in" {{ old('remember') ? 'checked' : '' }}>
	            <label for="signed-in">Ricordami</label>
	        </div>
	    </div>
	    <button type="submit" class="btn btn-rounded">Accedi</button>
	</form>
@endsection
