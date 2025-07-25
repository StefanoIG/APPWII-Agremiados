@extends('adminlte::page')

@section('title', 'Gestión de Pagos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Pagos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Pagos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- Estadísticas rápidas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $payments->count() }}</h3>
                    <p>Total Pagos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $payments->where('status', 'completed')->count() }}</h3>
                    <p>Pagos Completados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $payments->where('status', 'pending')->count() }}</h3>
                    <p>Pagos Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $payments->where('status', 'failed')->count() }}</h3>
                    <p>Pagos Fallidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de pagos --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill"></i>
                        Lista de Pagos
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filterModal">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Plan</th>
                                        <th>Monto</th>
                                        <th>Método de Pago</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <strong>#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $payment->user_name }}</strong><br>
                                                    <small class="text-muted">{{ $payment->user_email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $payment->subscription_plan }}</span>
                                            </td>
                                            <td>
                                                <strong>${{ number_format($payment->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                @switch($payment->payment_method)
                                                    @case('credit_card')
                                                        <i class="fas fa-credit-card text-primary"></i> Tarjeta de Crédito
                                                        @break
                                                    @case('bank_transfer')
                                                        <i class="fas fa-university text-info"></i> Transferencia
                                                        @break
                                                    @default
                                                        <i class="fas fa-money-bill text-success"></i> Efectivo
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($payment->status)
                                                    @case('completed')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Completado
                                                        </span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock"></i> Pendiente
                                                        </span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times"></i> Fallido
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">Desconocido</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $payment->created_at->format('d/m/Y') }}<br>
                                                    {{ $payment->created_at->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                                       class="btn btn-info btn-sm" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($payment->status === 'pending')
                                                        <button type="button" class="btn btn-success btn-sm" 
                                                                title="Aprobar" onclick="approvePayment({{ $payment->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                title="Rechazar" onclick="rejectPayment({{ $payment->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay pagos registrados</h5>
                            <p class="text-muted">Los pagos aparecerán aquí una vez que los usuarios realicen sus suscripciones.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de filtros --}}
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filtrar Pagos</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="form-group">
                        <label for="status_filter">Estado</label>
                        <select class="form-control" id="status_filter">
                            <option value="">Todos los estados</option>
                            <option value="completed">Completados</option>
                            <option value="pending">Pendientes</option>
                            <option value="failed">Fallidos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from">Desde</label>
                        <input type="date" class="form-control" id="date_from">
                    </div>
                    <div class="form-group">
                        <label for="date_to">Hasta</label>
                        <input type="date" class="form-control" id="date_to">
                    </div>
                    <div class="form-group">
                        <label for="amount_min">Monto mínimo</label>
                        <input type="number" class="form-control" id="amount_min" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="amount_max">Monto máximo</label>
                        <input type="number" class="form-control" id="amount_max" step="0.01">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">Aplicar Filtros</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function approvePayment(id) {
    Swal.fire({
        title: '¿Aprobar este pago?',
        text: "Se marcará como completado",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la lógica para aprobar el pago
            Swal.fire('¡Aprobado!', 'El pago ha sido aprobado correctamente.', 'success');
        }
    });
}

function rejectPayment(id) {
    Swal.fire({
        title: '¿Rechazar este pago?',
        text: "Se marcará como fallido",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la lógica para rechazar el pago
            Swal.fire('¡Rechazado!', 'El pago ha sido rechazado.', 'success');
        }
    });
}

function applyFilters() {
    // Aquí iría la lógica para aplicar los filtros
    alert('Funcionalidad de filtros pendiente de implementar');
    $('#filterModal').modal('hide');
}
</script>
@stop
