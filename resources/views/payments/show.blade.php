@extends('adminlte::page')

@section('title', 'Ver Comprobante')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Comprobante de Pago</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('payments.pending') }}">Pagos Pendientes</a></li>
                    <li class="breadcrumb-item active">Ver Comprobante</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Información del Comprobante -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-receipt"></i> Detalles del Comprobante
                    </h3>
                    <div class="card-tools">
                        @if($receipt->status === 'pending')
                            <span class="badge badge-warning">Pendiente</span>
                        @elseif($receipt->status === 'approved')
                            <span class="badge badge-success">Aprobado</span>
                        @else
                            <span class="badge badge-danger">Rechazado</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Usuario:</th>
                                    <td>
                                        <div class="user-panel d-flex">
                                            <div class="image">
                                                <img src="{{ $receipt->userSubscription->user->adminlte_image() }}" 
                                                     class="img-circle elevation-2" alt="User Image"
                                                     style="width: 32px; height: 32px;">
                                            </div>
                                            <div class="info">
                                                <strong>{{ $receipt->userSubscription->user->name }}</strong><br>
                                                <small class="text-muted">{{ $receipt->userSubscription->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Plan de Suscripción:</th>
                                    <td>
                                        <span class="badge badge-primary">{{ $receipt->userSubscription->plan->name }}</span><br>
                                        <small class="text-muted">
                                            {{ $receipt->userSubscription->plan->duration_type === 'monthly' ? 'Mensual' : 'Anual' }}
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Precio del Plan:</th>
                                    <td><strong>${{ number_format($receipt->userSubscription->plan->price, 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Monto Pagado:</th>
                                    <td>
                                        <strong>${{ number_format($receipt->amount, 0) }}</strong>
                                        @if($receipt->amount != $receipt->userSubscription->plan->price)
                                            <br><small class="text-warning">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                Diferencia: ${{ number_format(abs($receipt->amount - $receipt->userSubscription->plan->price), 0) }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Fecha de Pago:</th>
                                    <td>{{ $receipt->payment_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Método de Pago:</th>
                                    <td>{{ $receipt->payment_method ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <th>Subido el:</th>
                                    <td>
                                        {{ $receipt->created_at->format('d/m/Y H:i') }}<br>
                                        <small class="text-muted">{{ $receipt->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @if($receipt->reviewed_at)
                                <tr>
                                    <th>Revisado el:</th>
                                    <td>
                                        {{ $receipt->reviewed_at->format('d/m/Y H:i') }}<br>
                                        <small class="text-muted">por {{ $receipt->reviewedBy->name ?? 'Sistema' }}</small>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($receipt->user_notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-comment"></i> Notas del Usuario:</h6>
                            <div class="alert alert-light">
                                {{ $receipt->user_notes }}
                            </div>
                        </div>
                    @endif

                    @if($receipt->admin_notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-user-shield"></i> Notas de Revisión:</h6>
                            <div class="alert alert-{{ $receipt->status === 'approved' ? 'success' : 'danger' }}">
                                {{ $receipt->admin_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comprobante Visual -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-image"></i> Comprobante
                    </h3>
                    <div class="card-tools">
                        <a href="{{ asset('storage/' . $receipt->receipt_path) }}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Abrir en nueva ventana
                        </a>
                    </div>
                </div>
                <div class="card-body text-center">
                    @php
                        $extension = pathinfo($receipt->receipt_path, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                    @endphp

                    @if($isImage)
                        <img src="{{ asset('storage/' . $receipt->receipt_path) }}" 
                             class="img-fluid" alt="Comprobante de pago"
                             style="max-height: 600px; border: 1px solid #ddd; border-radius: 4px;">
                    @else
                        <div class="py-5">
                            <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                            <h5>Archivo PDF</h5>
                            <p class="text-muted">{{ basename($receipt->receipt_path) }}</p>
                            <a href="{{ asset('storage/' . $receipt->receipt_path) }}" 
                               class="btn btn-primary" target="_blank">
                                <i class="fas fa-download"></i> Descargar/Ver PDF
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="col-md-4">
            @if($receipt->status === 'pending')
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i> Acciones de Revisión
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Este comprobante está pendiente de revisión.</p>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-block mb-2" 
                                    data-toggle="modal" data-target="#approveModal">
                                <i class="fas fa-check"></i> Aprobar Pago
                            </button>
                            
                            <button type="button" class="btn btn-danger btn-block" 
                                    data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> Rechazar Pago
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-{{ $receipt->status === 'approved' ? 'success' : 'danger' }}">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-{{ $receipt->status === 'approved' ? 'check-circle' : 'times-circle' }}"></i>
                            Estado: {{ ucfirst($receipt->status) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($receipt->status === 'approved')
                            <p class="text-success">
                                <i class="fas fa-check"></i> Este comprobante ha sido aprobado.
                            </p>
                            @if($receipt->userSubscription->status === 'active')
                                <p class="text-muted">
                                    La suscripción del usuario está activa hasta: 
                                    <strong>{{ $receipt->userSubscription->end_date->format('d/m/Y') }}</strong>
                                </p>
                            @endif
                        @else
                            <p class="text-danger">
                                <i class="fas fa-times"></i> Este comprobante ha sido rechazado.
                            </p>
                            <p class="text-muted">El usuario puede subir un nuevo comprobante.</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Información de la Suscripción -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Información de Suscripción
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <span class="badge badge-{{ $receipt->userSubscription->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($receipt->userSubscription->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($receipt->userSubscription->start_date)
                        <tr>
                            <th>Inicio:</th>
                            <td>{{ $receipt->userSubscription->start_date->format('d/m/Y') }}</td>
                        </tr>
                        @endif
                        @if($receipt->userSubscription->end_date)
                        <tr>
                            <th>Vencimiento:</th>
                            <td>{{ $receipt->userSubscription->end_date->format('d/m/Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Navegación -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('payments.pending') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver a Pagos Pendientes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aprobar Pago -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('payments.approve', $receipt) }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">
                        <i class="fas fa-check"></i> Aprobar Pago
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Usuario:</strong> {{ $receipt->userSubscription->user->name }}<br>
                        <strong>Plan:</strong> {{ $receipt->userSubscription->plan->name }}<br>
                        <strong>Monto:</strong> ${{ number_format($receipt->amount, 0) }}
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_notes">Notas (Opcional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Comentarios sobre la aprobación..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <strong>¿Confirmar aprobación?</strong><br>
                        Al aprobar este pago, la suscripción del usuario será activada automáticamente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprobar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rechazar Pago -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('payments.reject', $receipt) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times"></i> Rechazar Pago
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Usuario:</strong> {{ $receipt->userSubscription->user->name }}<br>
                        <strong>Plan:</strong> {{ $receipt->userSubscription->plan->name }}<br>
                        <strong>Monto:</strong> ${{ number_format($receipt->amount, 0) }}
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_notes_reject">Motivo del Rechazo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="admin_notes_reject" name="admin_notes" rows="4" required
                                  placeholder="Explica el motivo del rechazo para que el usuario pueda corregir el problema..."></textarea>
                        <small class="form-text text-muted">
                            Este mensaje será enviado al usuario por email.
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <strong>¿Confirmar rechazo?</strong><br>
                        El usuario será notificado por email y podrá subir un nuevo comprobante.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Rechazar Pago
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
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@stop
