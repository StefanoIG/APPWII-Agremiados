@extends('adminlte::page')

@section('title', 'Acceso Denegado')

@section('content_header')
    <h1>Error 403</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-danger">403</h2>
        <div class="error-content">
            <h3><i class="fas fa-ban text-danger"></i> ¡Acceso Denegado!</h3>
            <p>
                No tienes permisos suficientes para acceder a esta página.
                Si crees que esto es un error, contacta al administrador del sistema.
            </p>
            <p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Regresar al Dashboard
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver Atrás
                </a>
            </p>
        </div>
    </div>
@stop

@section('css')
    <style>
        .error-page {
            width: 600px;
            margin: 20px auto 0 auto;
        }
        
        .error-page > .headline {
            float: left;
            font-size: 100px;
            font-weight: 300;
        }
        
        .error-page > .error-content {
            margin-left: 190px;
        }
        
        .error-page > .error-content > h3 {
            font-weight: 300;
            font-size: 25px;
        }
        
        @media (max-width: 767px) {
            .error-page {
                width: 100%;
                margin: 20px 0 0 0;
            }
            
            .error-page > .headline {
                float: none;
                text-align: center;
            }
            
            .error-page > .error-content {
                margin-left: 0;
            }
        }
    </style>
@stop
