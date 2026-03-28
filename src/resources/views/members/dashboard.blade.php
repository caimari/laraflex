@extends('laraflex::layouts.app')

@section('content')
    <div class="container">
        @auth
            <!-- Contenido visible solo para usuarios autenticados -->
            <h2>Bienvenido, {{ auth()->user()->name }}!</h2>
            <p>Contenido exclusivo para usuarios autenticados.</p>
        @else
            <!-- Contenido para usuarios no autenticados -->
            <h2>Inicia sesión para ver el contenido exclusivo</h2>
            <p>Por favor, inicia sesión para acceder al contenido exclusivo.</p>
        @endauth
    </div>
@endsection
