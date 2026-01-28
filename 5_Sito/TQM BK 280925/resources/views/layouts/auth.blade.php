<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		@include('layouts.head')
		<link rel="stylesheet" href="{{ asset('assets/css/separate/pages/login.min.css') }}">
	</head>

	<body>

        <div class="container-fluid" style="margin-top: calc(50vh - 190px);">
			@yield('content')
        </div>

		@include('layouts.foot')
	</body>
</html>
