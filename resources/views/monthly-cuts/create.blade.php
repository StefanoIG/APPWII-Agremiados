@extends('adminlte::page')

@section('title', 'Crear Corte Mensual')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">➕ Crear Nuevo Corte Mensual</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('monthly-cuts.index') }}">Cortes Mensuales</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">📋 Información del Corte Mensual</h3>
                </div>
                <form action="{{ route('monthly-cuts.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Importante:</strong> Al crear este corte, se generará automáticamente una deuda 
                            para todos los usuarios activos del sistema y se les enviará un correo de notificación.
                        </div>

                        <div class="form-group">
                            <label for="cut_name">
                                <i class="fas fa-tag"></i> Nombre del Corte *
                            </label>
                            <input type="text" class="form-control @error('cut_name') is-invalid @enderror" 
                                   id="cut_name" name="cut_name" value="{{ old('cut_name') }}" 
                                   placeholder="Ej: Enero 2025, Cuota Anual 2025" required>
                            @error('cut_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Nombre descriptivo para identificar este corte</small>
                        </div>

                        <div class="form-group">
                            <label for="cut_date">
                                <i class="fas fa-calendar"></i> Fecha de Corte *
                            </label>
                            <input type="date" class="form-control @error('cut_date') is-invalid @enderror" 
                                   id="cut_date" name="cut_date" value="{{ old('cut_date', date('Y-m-d')) }}" required>
                            @error('cut_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Fecha en que se genera el corte</small>
                        </div>

                        <div class="form-group">
                            <label for="amount">
                                <i class="fas fa-dollar-sign"></i> Monto *
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount', 22) }}" 
                                       min="0" step="0.01" required>
                                @error('amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Valor que se cobrará a cada usuario. Valor sugerido: $22
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-align-left"></i> Descripción (Opcional)
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Descripción adicional del corte o instrucciones especiales...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Esta descripción aparecerá en el correo enviado a los usuarios</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Atención:</strong> Los usuarios tendrán 30 días calendario desde la fecha de corte 
                            para realizar el pago. Después de este período, su acceso a las actividades será restringido.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Corte y Generar Deudas
                        </button>
                        <a href="{{ route('monthly-cuts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Generar nombre automático basado en fecha
    $('#cut_date').change(function() {
        let date = new Date($(this).val());
        if (date) {
            let month = date.toLocaleString('es-ES', { month: 'long' });
            let year = date.getFullYear();
            let cutName = month.charAt(0).toUpperCase() + month.slice(1) + ' ' + year;
            $('#cut_name').val(cutName);
        }
    });

    // Formatear monto
    $('#amount').on('input', function() {
        let value = $(this).val();
        if (value) {
            // Remover caracteres no numéricos excepto punto
            value = value.replace(/[^\d.]/g, '');
            $(this).val(value);
        }
    });
});
</script>
@stop
