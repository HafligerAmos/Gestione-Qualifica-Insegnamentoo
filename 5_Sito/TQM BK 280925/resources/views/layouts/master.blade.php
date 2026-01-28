@php
	if($group === 'valutazione' || $group === 'sondaggio' || $group === 'pannello'){
	    $side_menu = 'with-side-menu-addl';
    } else {
        $side_menu = '';
    }
@endphp
<!DOCTYPE html>
<html>
	<head lang="{{ app()->getLocale() }}">
		@include('layouts.head')
	</head>
	<body class="with-side-menu-compact {{ $side_menu }} control-panel control-panel-compact">

		@include('layouts.header')

		<div class="page-content">
			@yield('content')
		</div>

		@include('layouts.footer')

		@include('layouts.foot')
	</body>
</html>
