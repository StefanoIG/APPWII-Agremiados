@extends('adminlte::page')

@section('title', 'Mis Suscripciones')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mis Suscripciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subscriptions.plans') }}">Planes</a></li>
                    <li class="breadcrumb-item active">Mis Suscripciones</li>
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

    <!-- Suscripción Activa -->
    @php
        $activeSubscription = $subscriptions->where('status', 'active')->first();
    @endphp

    @if($activeSubscription)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-check-circle"></i> Suscripción Activa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>{{ $activeSubscription->plan->name }}</strong></h6>
                                <p class="text-muted">{{ $activeSubscription->plan->description }}</p>
                                <p><strong>Precio:</strong> ${{ number_format($activeSubscription->plan->price, 0) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha de Inicio:</strong> {{ $activeSubscription->start_date ? $activeSubscription->start_date->format('d/m/Y') : 'No disponible' }}</p>
                                <p><strong>Fecha de Fin:</strong> {{ $activeSubscription->end_date ? $activeSubscription->end_date->format('d/m/Y') : 'No disponible' }}</p>
                                @if($activeSubscription->end_date)
                                    @php
                                        $daysRemaining = intval(now()->diffInDays($activeSubscription->end_date, false));
                                    @endphp
                                    <p><strong>Días Restantes:</strong> 
                                        <span class="badge badge-{{ $daysRemaining > 30 ? 'success' : ($daysRemaining > 7 ? 'warning' : 'danger') }}">
                                            {{ $daysRemaining > 0 ? $daysRemaining . ' días' : 'Expirada' }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Sin Suscripción Activa</h5>
            <p>No tienes una suscripción activa actualmente.</p>
            <a href="{{ route('subscriptions.plans') }}" class="btn btn-primary">
                <i class="fas fa-credit-card"></i> Ver Planes Disponibles
            </a>
        </div>
    @endif

    <!-- Historial de Suscripciones -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-history"></i> Historial de Suscripciones</h5>
                </div>
                <div class="card-body">
                    @if($subscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Estado</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Precio</th>
                                        <th>Comprobantes</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptions as $subscription)
                                        <tr>
                                            <td>
                                                <strong>{{ $subscription->plan->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $subscription->plan->type }}</small>
                                            </td>
                                            <td>
                                                @switch($subscription->status)
                                                    @case('active')
                                                        <span class="badge badge-success">Activa</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge badge-warning">Pendiente</span>
                                                        @break
                                                    @case('expired')
                                                        <span class="badge badge-secondary">Expirada</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge badge-danger">Cancelada</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $subscription->start_date ? $subscription->start_date->format('d/m/Y') : 'Pendiente' }}</td>
                                            <td>{{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : 'Pendiente' }}</td>
                                            <td>${{ number_format($subscription->plan->price, 0) }}</td>
                                            <td>
                                                @php
                                                    $receiptsCount = $subscription->paymentReceipts()->count();
                                                    $approvedReceipts = $subscription->paymentReceipts()->where('status', 'approved')->count();
                                                    $pendingReceipts = $subscription->paymentReceipts()->where('status', 'pending')->count();
                                                @endphp
                                                
                                                @if($receiptsCount > 0)
                                                    <small class="d-block">
                                                        <i class="fas fa-check text-success"></i> {{ $approvedReceipts }} aprobados
                                                    </small>
                                                    @if($pendingReceipts > 0)
                                                        <small class="d-block">
                                                            <i class="fas fa-clock text-warning"></i> {{ $pendingReceipts }} pendientes
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Sin comprobantes</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->status === 'pending')
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#uploadReceiptModal" data-subscription-id="{{ $subscription->id }}">
                                                        <i class="fas fa-upload"></i> Subir Comprobante
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No tienes suscripciones registradas.</p>
                            <a href="{{ route('subscriptions.plans') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ver Planes Disponibles
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Subir Comprobante -->
<div class="modal fade" id="uploadReceiptModal" tabindex="-1" role="dialog" aria-labelledby="uploadReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadReceiptForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadReceiptModalLabel">
                        <i class="fas fa-upload"></i> Subir Comprobante de Pago
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="receipt_image">Comprobante de Pago <span class="text-danger">*</span></label>
                        <input type="file" class="form-control-file" id="receipt_image" name="receipt_image" required accept="image/*,.pdf">
                        <small class="form-text text-muted">
                            Formatos permitidos: JPG, PNG, PDF. Tamaño máximo: 2MB.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="amount">Monto Pagado <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_date">Fecha de Pago <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notas (Opcional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Información adicional sobre el pago..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Subir Comprobante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    $('#uploadReceiptModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var subscriptionId = button.data('subscription-id');
        var form = $('#uploadReceiptForm');
        form.attr('action', '/pagos/subir-comprobante/' + subscriptionId);
        
        // Set today as default payment date
        $('#payment_date').val(new Date().toISOString().split('T')[0]);
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@stop
