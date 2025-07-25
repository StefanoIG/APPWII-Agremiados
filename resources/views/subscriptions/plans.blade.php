@extends('adminlte::page')

@section('title', 'Planes de Suscripción')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Planes de Suscripción</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Planes de Suscripción</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Estado de Suscripción Actual -->
    @php
        $currentSubscription = Auth::user()->activeSubscription();
    @endphp
    
    @if($currentSubscription)
        <div class="alert alert-success">
            <h5><i class="fas fa-check-circle"></i> ¡Ya tienes una suscripción activa!</h5>
            <p>Tu suscripción actual: <strong>{{ $currentSubscription->plan->name }}</strong></p>
            <p>Válida hasta: <strong>{{ $currentSubscription->end_date->format('d/m/Y') }}</strong></p>
            <a href="{{ route('subscriptions.my') }}" class="btn btn-outline-success">
                <i class="fas fa-eye"></i> Ver mis suscripciones
            </a>
        </div>
    @else
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Sin Suscripción Activa</h5>
            <p>No tienes una suscripción activa. Para acceder a todas las funcionalidades, selecciona un plan a continuación.</p>
        </div>
    @endif

    <!-- Planes Disponibles -->
    <div class="row">
        @foreach($plans as $plan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 {{ $plan->type === 'yearly' ? 'border-success' : '' }}">
                    @if($plan->type === 'yearly')
                        <div class="card-header bg-success text-white text-center">
                            <i class="fas fa-star"></i> ¡MÁS POPULAR!
                        </div>
                    @endif
                    
                    <div class="card-body text-center">
                        <h4 class="card-title">{{ $plan->name }}</h4>
                        <div class="display-4 text-primary font-weight-bold mb-3">
                            ${{ number_format($plan->price, 0) }}
                        </div>
                        <div class="text-muted mb-3">
                            @if($plan->type === 'monthly')
                                por mes
                            @elseif($plan->type === 'yearly')
                                por año
                                <small class="d-block text-success">
                                    <i class="fas fa-piggy-bank"></i> ¡Ahorra ${{ number_format((25 * 12) - $plan->price, 0) }}!
                                </small>
                            @else
                                por {{ $plan->duration_months }} meses
                            @endif
                        </div>
                        
                        <p class="card-text">{{ $plan->description }}</p>
                        
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Acceso a todas las disciplinas</li>
                            <li><i class="fas fa-check text-success"></i> Participación en equipos</li>
                            <li><i class="fas fa-check text-success"></i> Eventos especiales</li>
                            <li><i class="fas fa-check text-success"></i> Descuentos en torneos</li>
                            @if($plan->type === 'yearly')
                                <li><i class="fas fa-check text-success"></i> Prioridad en inscripciones</li>
                                <li><i class="fas fa-check text-success"></i> Soporte prioritario</li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="card-footer">
                        @if($currentSubscription && $currentSubscription->subscription_plan_id === $plan->id)
                            <button class="btn btn-secondary btn-block" disabled>
                                <i class="fas fa-check"></i> Plan Actual
                            </button>
                        @elseif($currentSubscription)
                            <button class="btn btn-outline-primary btn-block" disabled>
                                <i class="fas fa-lock"></i> Suscripción Activa
                            </button>
                        @else
                            <form action="{{ route('subscriptions.subscribe', $plan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-credit-card"></i> Suscribirse
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Información Adicional -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Información sobre Suscripciones</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-credit-card"></i> Proceso de Pago</h6>
                            <ol>
                                <li>Selecciona tu plan preferido</li>
                                <li>Realiza el pago por transferencia bancaria</li>
                                <li>Sube el comprobante de pago</li>
                                <li>Espera la confirmación (1-2 días hábiles)</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-university"></i> Datos Bancarios</h6>
                            <p><strong>Banco:</strong> Banco Nacional</p>
                            <p><strong>Cuenta:</strong> 123-456789-0</p>
                            <p><strong>Titular:</strong> Asociación de Agremiados</p>
                            <p><strong>Tipo:</strong> Cuenta Corriente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.display-4 {
    font-size: 2.5rem;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@stop
