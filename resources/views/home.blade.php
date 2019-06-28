@extends('root')
@section('title', 'Elyssif')
@section('body')
	<section class="hero is-fullheight gradient" id="welcome">
		<div class="hero-body">
			<div class="container has-text-centered">
				<img src="{{ asset('img/logo_white_512.png') }}" class="logo mb-5 animate-slide-in">
				<h1 class="title animate-slide-in animate-delay">Elyssif</h1>
				<p class="subtitle animate-slide-in animate-delay_long">
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
			<div class="columns is-centered is-vcentered is-variable is-4-fullhd is-8-widescreen is-2-mobile">
				<div class="column is-narrow has-text-centered-mobile">
					<h2 class="title animate-slide-in-right">File sharing with peace of mind</h2>
					<p class="animate-slide-in-right animate-delay">
						Securely send files to anyone without worrying about hackers nor data resale.
					</p>
				</div>
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/yoga.png') }}" class="img animate-slide-in-left">
				</div>
			</div>
			<div class="columns is-centered is-vcentered is-variable is-4-fullhd is-8-widescreen is-2-mobile">
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/idea.png') }}" class="img animate-slide-in-right animate-delay">
				</div>
				<div class="column is-narrow has-text-centered-mobile">
					<h2 class="title animate-slide-in-left animate-delay">Simple and intuitive</h2>
					<p class="animate-slide-in-left animate-delay_long">
						No headaches, experience a user-friendly application.
					</p>
				</div>
			</div>
		</div>
	</section>
	<section class="hero is-light is-fullheight is-bold">
		<div class="hero-body">
			<div class="container has-text-centered">
				<img src="{{ asset('img/lock.png') }}" class="img mb-5 animate-slide-in-down">
				<h2 class="title animate-slide-in">
					State-of-the-art cryptography
				</h2>
				<p class="animate-slide-in animate-delay">
					Encrypt any file, no matter its size.
				</p>
				<p class="animate-slide-in animate-delay_long">
					Only your recipient will be able to decrypt it. 
				</p>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<div class="columns is-centered is-vcentered is-variable is-4-fullhd is-8-widescreen is-2-mobile has-text-centered-mobile">
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/messaging-apps.png') }}" class="img animate-slide-in-right">
				</div>
				<div class="column is-narrow">
					<h2 class="title animate-slide-in-left">Interoperability</h2>
					<p class="animate-slide-in-left animate-delay">
						Elyssif works with absolutely any service.
					</p>
					<p class="animate-slide-in-left animate-delay">
						You can continue using your preferred messaging app or cloud storage.
					</p>
				</div>
			</div>
			<div class="columns is-centered is-vcentered is-variable is-4-fullhd is-8-widescreen is-2-mobile has-text-centered-mobile">
				<div class="column is-narrow">
					<h2 class="title animate-slide-in-right animate-delay">Sell your files</h2>
					<p class="animate-slide-in-right animate-delay_long">
						You can optionally require a <b>Bitcoin</b> payment to your recipient.
					</p>
					<p class="animate-slide-in-right animate-delay_long">
						Elyssif keeps fixed fees and pays you right when your recipient have decrypted your file.
					</p>
				</div>
				<div class="column is-narrow has-text-centered">
					<img src="{{ asset('img/Bitcoin.png') }}" class="img animate-slide-in-left animate-delay">
				</div>
			</div>
		</div>
	</section>
	<section class="hero gradient is-fullheight is-bold">
		<div class="hero-body">
			<div class="container has-text-centered">
				<h2 class="title font-large animate-slide-in">
					Get started now!
				</h2>
				<a class="button is-primary font-large animate-slide-in animate-delay">
					<span class="is-vcentered">Download</span>
					<img src="{{ asset('img/download.png') }}" class="ml-2">
				</a>
			</div>
		</div>
	</section>
@endsection
