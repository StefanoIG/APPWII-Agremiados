@php
    $noSidebarRoutes = ['login', 'register'];
@endphp

@extends('adminlte::page', ['layoutTopnav' => in_array(Route::currentRouteName(), $noSidebarRoutes)])


@section('title', 'Dashboard')

@section('content_header')
    @auth
        <h1>Dashboard</h1>
    @endauth
@stop

@section('content')
    @auth
        <p>Welcome to this beautiful admin panel.</p>
    @else
        <div class="container mt-5">
            @yield('auth-content')
        </div>
    @endauth
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop

@section('sidebar')
    @auth
        @if (Auth::check() && !in_array(Route::currentRouteName(), ['login', 'register']))
            {{-- DEBUG: Mostrar roles actuales --}}
            <div style="color:red; font-weight:bold;">Roles actuales: {{ Auth::user()->getRoleNames()->join(', ') }}</div>
            @if (Blade::check('role', 'admin|secretaria'))
                {{-- Fallback manual si la directiva falla --}}
                
            @endif
        @endif
    @endauth
@stop.i