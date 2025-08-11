@extends('adminlte::page')

@section('title', 'Gestión de Deudas de Usuarios')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Gestión de Deudas de Usuarios</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Gestión de Deudas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['total_pending'] }}</h3>
                    <p>Deudas Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['pending_approval'] }}</h3>
                    <p>Pendientes de Aprobación</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['overdue'] }}</h3>
                    <p>Deudas Vencidas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['paid'] }}</h3>
                    <p>Deudas Pagadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Deudas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>
                        Todas las Deudas de Usuarios
                    </h3>
                    <div class="card-tools">
                        <!-- Filtros -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="filterByStatus('all')">
                                Todas
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="filterByStatus('pending')">
                                Pendientes
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="filterByStatus('pending_approval')">
                                Por Aprobar
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="filterByStatus('overdue')">
                                Vencidas
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="filterByStatus('paid')">
                                Pagadas
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Periodo</th>
                                <th>Monto</th>
                                <th>Fecha Vencimiento</th>
                                <th>Estado</th>
                                <th>Fecha Pago</th>
                                <th>Método Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debts as $debt)
                                <tr data-status="{{ $debt->status }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($debt->user->profile_photo)
                                                <img src="{{ asset($debt->user->profile_photo) }}" 
                                                     alt="{{ $debt->user->name }}" 
                                                     class="img-circle elevation-1 mr-2" 
                                                     style="width: 30px; height: 30px;">
                                            @else
                                                <div class="bg-primary rounded-circle mr-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px; font-size: 12px; color: white;">
                                                    {{ strtoupper(substr($debt->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $debt->user->name }}</strong><br>
                                                <small class="text-muted">{{ $debt->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $debt->monthlyCut->period }}</strong><br>
                                        <small class="text-muted">{{ $debt->monthlyCut->description }}</small>
                                    </td>
                                    <td>
                                        <span class="h5 text-primary">${{ number_format($debt->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        {{ $debt->due_date->format('d/m/Y') }}<br>
                                        @if($debt->due_date->isPast() && $debt->status !== 'paid')
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Vencida hace {{ $debt->due_date->diffForHumans() }}
                                            </small>
                                        @else
                                            <small class="text-muted">
                                                {{ $debt->due_date->diffForHumans() }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($debt->status)
                                            @case('pending')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Pendiente
                                                </span>
                                                @break
                                            @case('pending_approval')
                                                <span class="badge badge-info">
                                                    <i class="fas fa-hourglass-half"></i> Por Aprobar
                                                </span>
                                                @break
                                            @case('paid')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Pagada
                                                </span>
                                                @break
                                            @case('overdue')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Vencida
                                                </span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ ucfirst($debt->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($debt->paid_at)
                                            {{ $debt->paid_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($debt->payment_method)
                                            <span class="badge badge-secondary">{{ ucfirst($debt->payment_method) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($debt->status === 'pending_approval')
                                                <!-- Botones para aprobar/rechazar pagos -->
                                                @if($debt->payment_receipt || $debt->receipt_url)
                                                    <a href="{{ $debt->receipt_url ?? asset('storage/' . $debt->payment_receipt) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Ver Comprobante">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                
                                                <form action="{{ route('user-debts.approve', $debt) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Aprobar Pago"
                                                            onclick="return confirm('¿Confirmar aprobación del pago?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('user-debts.reject', $debt) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Rechazar Pago"
                                                            onclick="return confirm('¿Rechazar este pago? El usuario deberá subir un nuevo comprobante.')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($debt->status === 'paid')
                                                @if($debt->payment_receipt || $debt->receipt_url)
                                                    <a href="{{ $debt->receipt_url ?? asset('storage/' . $debt->payment_receipt) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Ver Comprobante">
                                                        <i class="fas fa-eye"></i> Ver Comprobante
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-minus"></i> Sin acciones
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                        <p class="text-muted">No hay deudas registradas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($debts->hasPages())
                    <div class="card-footer">
                        {{ $debts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
function filterByStatus(status) {
    const rows = document.querySelectorAll('tbody tr[data-status]');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Actualizar botones activos
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

// Mostrar alertas con toast
@if(session('success'))
    toastr.success('{{ session('success') }}');
@endif

@if(session('error'))
    toastr.error('{{ session('error') }}');
@endif
</script>
@stop

@section('css')
<style>
.small-box .icon {
    top: 10px;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.badge {
    font-size: 0.9em;
}

.img-circle {
    border-radius: 50%;
}
</style>
@stop
