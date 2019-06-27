<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" type="image/png" href="{{ asset('img/logo16.png') }}" sizes="16x16">
	<link rel="icon" type="image/png" href="{{ asset('img/logo32.png') }}" sizes="32x32">
	<link rel="icon" type="image/png" href="{{ asset('img/logo64.png') }}" sizes="64x64">
	<link rel="icon" type="image/png" href="{{ asset('img/logo128.png') }}" sizes="128x128">
	<link rel="icon" type="image/png" href="{{ asset('img/logo256.png') }}" sizes="256x256">

	<title>Elyssif</title>

	<link rel="stylesheet" type="text/css" href="css/app.css">
</head>
<body>
	<section class="hero is-fullheight gradient" id="welcome">
		<div class="hero-body">
			<div class="container has-text-centered">
				<img src="{{ asset('img/logo_white_512.png') }}" class="logo mb-5">
				<h1 class="title">Elyssif</h1>
				<p class="subtitle">
					<b>E</b>lyssif <b>L</b>et's <b>Y</b>ou <b>S</b>ecurely <b>S</b>end <b>I</b>mportant <b>F</b>iles
				</p>
			</div>
		</div>
		<div class="hero-footer mb-5 has-text-centered">
			<div class="scroll-indicator"></div>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<div class="columns is-centered is-vcentered is-variable is-flex-mobile is-4-fullhd is-8-widescreen is-2-mobile">
				<div class="column is-narrow">
					<h2 class="title">File sharing with peace of mind</h2>
					<p>
						Securely send files to anyone without worrying about hackers nor data resale.
					</p>
				</div>
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/yoga.png') }}" class="img">
				</div>
			</div>
			<div class="columns is-centered is-vcentered is-variable is-flex-mobile is-4-fullhd is-8-widescreen is-2-mobile">
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/idea.png') }}" class="img">
				</div>
				<div class="column is-narrow">
					<h2 class="title">Simple and intuitive</h2>
					<p>
						No headaches, experience a user-friendly application.
					</p>
				</div>
			</div>
		</div>
	</section>
	<section class="hero is-light is-fullheight is-bold">
		<div class="hero-body">
			<div class="container has-text-centered">
				<img src="{{ asset('img/lock.png') }}" class="img mb-5">
				<h2 class="title">
					State-of-the-art cryptography
				</h2>
				<p>
					Encrypt any file, no matter its size.
				</p>
				<p>
					Only your recipient will be able to decrypt it. 
				</p>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<div class="columns is-centered is-vcentered is-variable is-flex-mobile is-4-fullhd is-8-widescreen is-2-mobile">
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/messaging-apps.png') }}" class="img is-flex">
				</div>
				<div class="column is-narrow">
					<h2 class="title">Interoperability</h2>
					<p>
						Elyssif works with absolutely any service.
					</p>
					<p>
						You can continue using your preferred messaging app or cloud storage.
					</p>
				</div>
			</div>
			<div class="columns is-centered is-vcentered is-variable is-flex-mobile is-4-fullhd is-8-widescreen is-2-mobile">
				<div class="column is-narrow">
					<h2 class="title">Sell your files</h2>
					<p>
						You can optionally require a <b>Bitcoin</b> payment to your recipient.
					</p>
					<p>
						Elyssif keeps fixed fees and pays you right when your recipient have decrypted your file.
					</p>
				</div>
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/Bitcoin.png') }}" class="img">
				</div>
			</div>
		</div>
	</section>
	<section class="hero gradient is-fullheight is-bold">
		<div class="hero-body">
			<div class="container has-text-centered">
				<h2 class="title font-large">
					Get started now!
				</h2>
				<a class="button is-primary font-large ">
					<span class="is-vcentered">Download</span>
					<img src="{{ asset('img/download.png') }}" class="ml-2">
				</a>
			</div>
		</div>
	</section>
</body>
</html>
