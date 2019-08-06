{{--
   Elyssif-API
   Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <https://www.gnu.org/licenses/>.
--}}
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
