<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="A secure file sharing software.">

	<link rel="icon" type="image/png" href="{{ asset('img/logo16.png') }}" sizes="16x16">
	<link rel="icon" type="image/png" href="{{ asset('img/logo32.png') }}" sizes="32x32">
	<link rel="icon" type="image/png" href="{{ asset('img/logo64.png') }}" sizes="64x64">
	<link rel="icon" type="image/png" href="{{ asset('img/logo128.png') }}" sizes="128x128">
	<link rel="icon" type="image/png" href="{{ asset('img/logo256.png') }}" sizes="256x256">

	<title>@yield('title')</title>

	<link rel="stylesheet" type="text/css" href="css/app.css">
</head>
<body>
	@yield('body')
	<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
