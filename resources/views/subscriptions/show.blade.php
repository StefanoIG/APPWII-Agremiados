@extends('adminlte::page')

@section('title', 'Mi Suscripción')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mi Suscripción</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subscriptions.plans') }}">Planes</a></li>
                    <li class="breadcrumb-item active">Mi Suscripción</li>
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

    <div class="row">
        <!-- Información de la Suscripción -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star"></i> Detalles de tu Suscripción
                    </h3>
                    <div class="card-tools">
                        @if($subscription->status === 'pending')
                            <span class="badge badge-warning">Pendiente de Pago</span>
                        @elseif($subscription->status === 'active')
                            <span class="badge badge-success">Activa</span>
                        @elseif($subscription->status === 'expired')
                            <span class="badge badge-danger">Expirada</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($subscription->status) }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Plan:</th>
                                    <td><strong>{{ $subscription->plan->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        @if($subscription->plan->duration_type === 'monthly')
                                            <span class="badge badge-info">Mensual</span>
                                        @else
                                            <span class="badge badge-success">Anual</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Precio:</th>
                                    <td><strong>${{ number_format($subscription->plan->price, 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Duración:</th>
                                    <td>{{ $subscription->plan->duration_months }} mes(es)</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Fecha de Inicio:</th>
                                    <td>{{ $subscription->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Vencimiento:</th>
                                    <td>{{ $subscription->end_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        @if($subscription->status === 'pending')
                                            <span class="text-warning">Pendiente de pago</span>
                                        @elseif($subscription->status === 'active')
                                            <span class="text-success">Activa</span>
                                        @else
                                            <span class="text-muted">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Creada:</th>
                                    <td>{{ $subscription->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($subscription->plan->description)
                        <div class="mt-3">
                            <h6>Descripción del Plan:</h6>
                            <p class="text-muted">{{ $subscription->plan->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial de Pagos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-receipt"></i> Historial de Pagos
                    </h3>
                </div>
                <div class="card-body">
                    @if($subscription->paymentReceipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha Subida</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Fecha Revisión</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscription->paymentReceipts as $receipt)
                                        <tr>
                                            <td>{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                                            <td>${{ number_format($receipt->amount, 0) }}</td>
                                            <td>
                                                @if($receipt->status === 'pending')
                                                    <span class="badge badge-warning">Pendiente</span>
                                                @elseif($receipt->status === 'approved')
                                                    <span class="badge badge-success">Aprobado</span>
                                                @else
                                                    <span class="badge badge-danger">Rechazado</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $receipt->reviewed_at ? $receipt->reviewed_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td>
                                                @if($receipt->receipt_path)
                                                    <a href="{{ asset('storage/' . $receipt->receipt_path) }}" 
                                                       class="btn btn-sm btn-outline-info" target="_blank">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5>Sin Comprobantes</h5>
                            <p class="text-muted">Aún no has subido ningún comprobante de pago.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="col-md-4">
            @if($subscription->status === 'pending')
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle"></i> Pago Requerido
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Tu suscripción está pendiente de pago. Sigue estos pasos:</p>
                        
                        <ol class="mb-3">
                            <li>Realiza la transferencia bancaria</li>
                            <li>Sube tu comprobante de pago</li>
                            <li>Espera la confirmación</li>
                        </ol>

                        <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#bankDetailsModal">
                            <i class="fas fa-university"></i> Ver Datos Bancarios
                        </button>

                        <button type="button" class="btn btn-success btn-block mt-2" data-toggle="modal" data-target="#uploadReceiptModal">
                            <i class="fas fa-upload"></i> Subir Comprobante
                        </button>
                    </div>
                </div>
            @else
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-check-circle"></i> Estado: {{ ucfirst($subscription->status) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($subscription->status === 'active')
                            <p class="text-success">¡Tu suscripción está activa!</p>
                            <p>Puedes disfrutar de todos los beneficios hasta el {{ $subscription->end_date->format('d/m/Y') }}.</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Información Adicional -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Información
                    </h3>
                </div>
                <div class="card-body">
                    <h6>Beneficios del Plan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Acceso a todas las disciplinas</li>
                        <li><i class="fas fa-check text-success"></i> Participación en equipos</li>
                        <li><i class="fas fa-check text-success"></i> Eventos especiales</li>
                        <li><i class="fas fa-check text-success"></i> Descuentos en torneos</li>
                    </ul>

                    <hr>

                    <h6>¿Necesitas ayuda?</h6>
                    <p class="small text-muted">
                        Si tienes problemas con tu pago o necesitas asistencia, 
                        puedes contactar a nuestro equipo de soporte.
                    </p>
                </div>
            </div>

            <!-- Navegación -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('subscriptions.plans') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver a Planes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Datos Bancarios -->
<div class="modal fade" id="bankDetailsModal" tabindex="-1" role="dialog" aria-labelledby="bankDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bankDetailsModalLabel">
                    <i class="fas fa-university"></i> Datos Bancarios para Transferencia
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Información del Pago</h6>
                    <strong>Plan:</strong> {{ $subscription->plan->name }}<br>
                    <strong>Monto a Pagar:</strong> ${{ number_format($subscription->plan->price, 0) }}
                </div>

                <h6><i class="fas fa-university"></i> Datos Bancarios:</h6>
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Banco:</th>
                        <td><strong>Banco Nacional</strong></td>
                    </tr>
                    <tr>
                        <th>Tipo de Cuenta:</th>
                        <td>Cuenta Corriente</td>
                    </tr>
                    <tr>
                        <th>Número de Cuenta:</th>
                        <td><code>123-456789-0</code></td>
                    </tr>
                    <tr>
                        <th>Titular:</th>
                        <td>Asociación de Agremiados</td>
                    </tr>
                    <tr>
                        <th>RUT/Cédula:</th>
                        <td>12.345.678-9</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>pagos@agremiados.com</td>
                    </tr>
                </table>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Importante:</h6>
                    <ul class="mb-0">
                        <li>El monto debe ser exactamente <strong>${{ number_format($subscription->plan->price, 0) }}</strong></li>
                        <li>Incluye tu nombre en el concepto de la transferencia</li>
                        <li>Guarda el comprobante para subirlo después del pago</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" data-toggle="modal" data-target="#uploadReceiptModal">
                    <i class="fas fa-upload"></i> Ya Pagué - Subir Comprobante
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subir Comprobante -->
<div class="modal fade" id="uploadReceiptModal" tabindex="-1" role="dialog" aria-labelledby="uploadReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('payments.upload', $subscription) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="uploadReceiptModalLabel">
                        <i class="fas fa-upload"></i> Subir Comprobante de Pago
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Plan:</strong> {{ $subscription->plan->name }}<br>
                        <strong>Monto:</strong> ${{ number_format($subscription->plan->price, 0) }}
                    </div>

                    <div class="form-group">
                        <label for="receipt_file">Comprobante de Pago <span class="text-danger">*</span></label>
                        <input type="file" class="form-control-file" id="receipt_file" name="receipt_file" 
                               accept="image/*,.pdf" required>
                        <small class="form-text text-muted">
                            Formatos aceptados: JPG, PNG, PDF. Tamaño máximo: 5MB.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="amount">Monto Pagado <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   value="{{ $subscription->plan->price }}" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_date">Fecha de Pago <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" 
                               value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Método de Pago</label>
                        <select class="form-control" id="payment_method" name="payment_method">
                            <option value="transfer">Transferencia Bancaria</option>
                            <option value="deposit">Depósito Bancario</option>
                            <option value="online">Pago en Línea</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="user_notes">Notas Adicionales (Opcional)</label>
                        <textarea class="form-control" id="user_notes" name="user_notes" rows="3" 
                                  placeholder="Cualquier información adicional sobre el pago..."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-clock"></i> Tiempo de Procesamiento</h6>
                        <p class="mb-0">
                            Tu comprobante será revisado en un plazo de <strong>1-2 días hábiles</strong>. 
                            Recibirás una notificación por email con el resultado.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Subir Comprobante
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
    transition: all 0.3s ease;
}
.table th {
    border-top: none;
}
.modal-lg {
    max-width: 600px;
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

    // Preview uploaded file
    $('#receipt_file').change(function() {
        const file = this.files[0];
        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            if (fileSize > 5) {
                alert('El archivo es muy grande. El tamaño máximo es 5MB.');
                $(this).val('');
                return;
            }
        }
    });

    // Prevenir doble click en el formulario de subida de comprobante
    $('#uploadReceiptModal form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Verificar si ya se está procesando
        if (submitBtn.hasClass('disabled') || submitBtn.prop('disabled')) {
            e.preventDefault();
            return false;
        }

        // Deshabilitar el botón y cambiar texto
        submitBtn.prop('disabled', true)
                 .addClass('disabled')
                 .html('<i class="fas fa-spinner fa-spin"></i> Subiendo...');
        
        // Deshabilitar también el botón de cancelar
        $(this).find('button[data-dismiss="modal"]').prop('disabled', true);
        
        // Re-habilitar después de 10 segundos en caso de error
        setTimeout(function() {
            submitBtn.prop('disabled', false)
                     .removeClass('disabled')
                     .html('<i class="fas fa-upload"></i> Subir Comprobante');
            $('#uploadReceiptModal form button[data-dismiss="modal"]').prop('disabled', false);
        }, 10000);
    });
});
</script>
@stop
