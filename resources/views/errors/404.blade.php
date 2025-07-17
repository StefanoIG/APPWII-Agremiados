@extends('adminlte::page')

@section('title', 'Error 404')

@section('content_header')
    <h1>Error 404</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-warning">404</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Oops! Página no encontrada.</h3>
            <p>
                No pudimos encontrar la página que estás buscando.
                Mientras tanto, puedes <a href="{{ route('home') }}">regresar al dashboard</a> o intentar usar la búsqueda.
            </p>
            <form class="search-form" action="#" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar">
                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-warning">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
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
