@extends('adminlte::page')

@section('title', 'Gestión de Planes de Suscripción')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Planes de Suscripción</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Gestión de Planes</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
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

    <!-- Botón para crear nuevo plan -->
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPlanModal">
                <i class="fas fa-plus"></i> Crear Nuevo Plan
            </button>
        </div>
    </div>

    <!-- Tarjetas de Planes -->
    <div class="row">
        @foreach($plans as $plan)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 {{ $plan->is_active ? '' : 'border-danger' }}">
                    @if($plan->type === 'yearly')
                        <div class="card-header bg-success text-white text-center">
                            <i class="fas fa-star"></i> POPULAR
                        </div>
                    @elseif(!$plan->is_active)
                        <div class="card-header bg-danger text-white text-center">
                            <i class="fas fa-ban"></i> INACTIVO
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h4 class="card-title">{{ $plan->name }}</h4>
                        <div class="display-4 text-primary font-weight-bold mb-3">
                            ${{ number_format($plan->price, 0) }}
                        </div>
                        <div class="text-muted mb-3">
                            @if($plan->type === 'monthly')
                                por mes
                            @elseif($plan->type === 'yearly')
                                por año
                            @else
                                por {{ $plan->duration_months }} meses
                            @endif
                        </div>
                        
                        <p class="card-text">{{ $plan->description }}</p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <strong>Duración:</strong> {{ $plan->duration_months }} meses<br>
                                <strong>Tipo:</strong> {{ ucfirst($plan->type) }}<br>
                                <strong>Estado:</strong> 
                                @if($plan->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </small>
                        </div>

                        <!-- Estadísticas del plan -->
                        @php
                            $subscriptionsCount = $plan->userSubscriptions()->count();
                            $activeSubscriptions = $plan->userSubscriptions()->where('status', 'active')->count();
                        @endphp
                        <div class="mb-3">
                            <small class="text-info">
                                <i class="fas fa-users"></i> {{ $subscriptionsCount }} suscripciones totales<br>
                                <i class="fas fa-check-circle"></i> {{ $activeSubscriptions }} activas
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    data-toggle="modal" data-target="#editPlanModal" 
                                    data-plan-id="{{ $plan->id }}"
                                    data-plan-name="{{ $plan->name }}"
                                    data-plan-type="{{ $plan->type }}"
                                    data-plan-price="{{ $plan->price }}"
                                    data-plan-duration="{{ $plan->duration_months }}"
                                    data-plan-description="{{ $plan->description }}"
                                    data-plan-active="{{ $plan->is_active }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            
                            @if($plan->is_active)
                                <form method="POST" action="{{ Auth::user()->hasRole('admin') ? route('admin.plans.toggle', $plan) : route('secretaria.plans.toggle', $plan) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-warning btn-sm"
                                            onclick="return confirm('¿Desactivar este plan? Los usuarios no podrán suscribirse.')">
                                        <i class="fas fa-pause"></i> Desactivar
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ Auth::user()->hasRole('admin') ? route('admin.plans.toggle', $plan) : route('secretaria.plans.toggle', $plan) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-play"></i> Activar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($plans->count() === 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h4>No hay planes de suscripción</h4>
                        <p class="text-muted">Crea el primer plan para que los usuarios puedan suscribirse.</p>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPlanModal">
                            <i class="fas fa-plus"></i> Crear Primer Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Crear Plan -->
<div class="modal fade" id="createPlanModal" tabindex="-1" role="dialog" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ Auth::user()->hasRole('admin') ? route('admin.plans.store') : route('secretaria.plans.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createPlanModalLabel">
                        <i class="fas fa-plus"></i> Crear Nuevo Plan de Suscripción
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre del Plan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       placeholder="ej. Membresía Mensual">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Tipo de Plan <span class="text-danger">*</span></label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="monthly">Mensual</option>
                                    <option value="yearly">Anual</option>
                                    <option value="custom">Personalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Precio ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       step="0.01" min="0" required placeholder="25.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_months">Duración (meses) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration_months" name="duration_months" 
                                       min="1" required placeholder="1">
                                <small class="form-text text-muted">1 = Mensual, 12 = Anual</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Describe las características y beneficios de este plan..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="is_active">Plan activo</label>
                            <small class="form-text text-muted">Los planes inactivos no aparecen para los usuarios.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Plan -->
<div class="modal fade" id="editPlanModal" tabindex="-1" role="dialog" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="editPlanForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlanModalLabel">
                        <i class="fas fa-edit"></i> Editar Plan de Suscripción
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Nombre del Plan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_type">Tipo de Plan <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_type" name="type" required>
                                    <option value="monthly">Mensual</option>
                                    <option value="yearly">Anual</option>
                                    <option value="custom">Personalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_price">Precio ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_price" name="price" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_duration_months">Duración (meses) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_duration_months" name="duration_months" 
                                       min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active" value="1">
                            <label class="custom-control-label" for="edit_is_active">Plan activo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Plan
                    </button>
                </div>
            </form>
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
    transform: translateY(-2px);
}
.display-4 {
    font-size: 2.2rem;
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
    
    // Auto-fill duration based on type selection
    $('#type').change(function() {
        var type = $(this).val();
        if (type === 'monthly') {
            $('#duration_months').val(1);
        } else if (type === 'yearly') {
            $('#duration_months').val(12);
        }
    });
    
    // Handle edit modal
    $('#editPlanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var planId = button.data('plan-id');
        var planName = button.data('plan-name');
        var planType = button.data('plan-type');
        var planPrice = button.data('plan-price');
        var planDuration = button.data('plan-duration');
        var planDescription = button.data('plan-description');
        var planActive = button.data('plan-active');
        
        var modal = $(this);
        modal.find('#edit_name').val(planName);
        modal.find('#edit_type').val(planType);
        modal.find('#edit_price').val(planPrice);
        modal.find('#edit_duration_months').val(planDuration);
        modal.find('#edit_description').val(planDescription);
        modal.find('#edit_is_active').prop('checked', planActive);
        
        // Set form action
        var actionUrl = '{{ Auth::user()->hasRole("admin") ? "/admin/planes/" : "/secretaria/planes/" }}' + planId;
        $('#editPlanForm').attr('action', actionUrl);
    });
});
</script>
@stop
