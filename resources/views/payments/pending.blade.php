@extends('adminlte::page')

@section('title', 'Pagos Pendientes')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pagos Pendientes de Revisión</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Pagos Pendientes</li>
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

    <!-- Estadísticas rápidas -->
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingReceipts->count() }}</h3>
                    <p>Comprobantes Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>${{ number_format($pendingReceipts->sum('amount'), 0) }}</h3>
                    <p>Total en Revisión</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de comprobantes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Comprobantes por Revisar
                    </h3>
                </div>
                <div class="card-body">
                    @if($pendingReceipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Plan</th>
                                        <th>Monto</th>
                                        <th>Fecha Pago</th>
                                        <th>Subido</th>
                                        <th>Comprobante</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingReceipts as $receipt)
                                        <tr>
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
                                            <td>
                                                <span class="badge badge-primary">{{ $receipt->userSubscription->plan->name }}</span><br>
                                                <small class="text-muted">${{ number_format($receipt->userSubscription->plan->price, 0) }}</small>
                                            </td>
                                            <td>
                                                <strong>${{ number_format($receipt->amount, 0) }}</strong>
                                                @if($receipt->amount != $receipt->userSubscription->plan->price)
                                                    <br><small class="text-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Monto difiere
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $receipt->payment_date->format('d/m/Y') }}</td>
                                            <td>
                                                {{ $receipt->created_at->format('d/m/Y H:i') }}<br>
                                                <small class="text-muted">{{ $receipt->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('payments.view', $receipt) }}" 
                                                   class="btn btn-outline-info btn-sm" target="_blank">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            data-toggle="modal" data-target="#approveModal"
                                                            data-receipt-id="{{ $receipt->id }}"
                                                            data-user-name="{{ $receipt->userSubscription->user->name }}"
                                                            data-plan-name="{{ $receipt->userSubscription->plan->name }}"
                                                            data-amount="{{ $receipt->amount }}">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                    
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            data-toggle="modal" data-target="#rejectModal"
                                                            data-receipt-id="{{ $receipt->id }}"
                                                            data-user-name="{{ $receipt->userSubscription->user->name }}"
                                                            data-plan-name="{{ $receipt->userSubscription->plan->name }}"
                                                            data-amount="{{ $receipt->amount }}">
                                                        <i class="fas fa-times"></i> Rechazar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h4>¡Todo al día!</h4>
                            <p class="text-muted">No hay comprobantes de pago pendientes de revisión.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aprobar Pago -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
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
                        <strong>Usuario:</strong> <span id="approve-user-name"></span><br>
                        <strong>Plan:</strong> <span id="approve-plan-name"></span><br>
                        <strong>Monto:</strong> $<span id="approve-amount"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="approve_admin_notes">Notas (Opcional)</label>
                        <textarea class="form-control" id="approve_admin_notes" name="admin_notes" rows="3" 
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
            <form id="rejectForm" method="POST">
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
                        <strong>Usuario:</strong> <span id="reject-user-name"></span><br>
                        <strong>Plan:</strong> <span id="reject-plan-name"></span><br>
                        <strong>Monto:</strong> $<span id="reject-amount"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="reject_admin_notes">Motivo del Rechazo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_admin_notes" name="admin_notes" rows="4" required
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
    
    // Handle approve modal
    $('#approveModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var receiptId = button.data('receipt-id');
        var userName = button.data('user-name');
        var planName = button.data('plan-name');
        var amount = button.data('amount');
        
        var modal = $(this);
        modal.find('#approve-user-name').text(userName);
        modal.find('#approve-plan-name').text(planName);
        modal.find('#approve-amount').text(amount);
        
        // Set form action
        $('#approveForm').attr('action', '/secretaria/pago/' + receiptId + '/aprobar');
    });
    
    // Handle reject modal
    $('#rejectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var receiptId = button.data('receipt-id');
        var userName = button.data('user-name');
        var planName = button.data('plan-name');
        var amount = button.data('amount');
        
        var modal = $(this);
        modal.find('#reject-user-name').text(userName);
        modal.find('#reject-plan-name').text(planName);
        modal.find('#reject-amount').text(amount);
        
        // Set form action
        $('#rejectForm').attr('action', '/secretaria/pago/' + receiptId + '/rechazar');
    });
});
</script>
@stop
