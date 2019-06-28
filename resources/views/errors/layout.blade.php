@extends('root')
@section('body')
<section class="hero is-fullheight gradient" id="welcome">
    <div class="hero-body">
        <div class="container has-text-centered">
            <img src="{{ asset('img/logo_white_512.png') }}" class="logo mb-5 animate-slide-in">
            <h1 class="title animate-slide-in animate-delay">@yield('code')</h1>
            <p class="subtitle animate-slide-in animate-delay_long">
                @yield('message')
            </p>
        </div>
    </div>
</section>
@endsection