<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>{{ $title??'SMS2.0' }}</title>
	<link rel="stylesheet" href="{{ mix('css/vendor.css') }}">
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
	<link rel="shortcut icon" href="/favicon.ico"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="env-{{ env('APP_ENV') }}">
<header>
	<nav>
		<ul>
			<li><a href="#">xxx</a></li>
		</ul>
	</nav>	
</header>
<main>
	@if(Session::has('message.error') && !empty($mes = session('message.error')))
		<p class="">{{ $mes }}</p>
	@elseif(Session::has('message') && !empty($mes = session('message')))
		<p class="">{{ $mes }}</p>
	@endif
	@yield('content')
</main>
<footer>
	<nav>
		<ul>
			<li><a href="#">xxx</a></li>
		</ul>
	</nav>
</footer>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
@yield('footer-script')
</body>
</html>