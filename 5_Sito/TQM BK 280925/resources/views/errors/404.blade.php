<!DOCTYPE html>
<html>
	<head lang="{{ app()->getLocale() }}">
		@include('layouts.head')
		<link rel="stylesheet" href="{{ asset('assets/css/separate/pages/error.min.css') }}">
	</head>
	<body>
		<div class="page-error-box">
		    <div class="error-code">404</div>
		    <div class="error-title">Pagina non trovata</div>
		    <a href="{{ route('home') }}" class="btn btn-rounded">Torna alla Home</a>
		</div>
	</body>
</html>
