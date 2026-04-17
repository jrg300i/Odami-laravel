@extends('layouts.app')

@section('title', 'Configuración de Backup - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-cog me-2"></i>Configuración de Backup
            </h1>
            <a href="{{ route('backups.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sliders-h me-2"></i>Parámetros de Backup
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('backups.configuracion') }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clock me-2"></i>Backup Automático
                        </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="backup_automatico" 
                                   id="backup_automatico"
                                   value="1"
                                   {{ old('backup_automatico', $configuracion['automatico']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="backup_automatico">
                                Habilitar copias de seguridad automáticas
                            </label>
                        </div>
                        <div class="form-text">
                            Los backups automáticos se ejecutarán según la frecuencia programada sin intervención manual.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="frecuencia_backup" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-2"></i>Frecuencia de Backup
                        </label>
                        <select name="frecuencia_backup" id="frecuencia_backup" class="form-select">
                            <option value="daily" {{ old('frecuencia_backup', $configuracion['frecuencia']) == 'daily' ? 'selected' : '' }}>
                                Diario
                            </option>
                            <option value="weekly" {{ old('frecuencia_backup', $configuracion['frecuencia']) == 'weekly' ? 'selected' : '' }}>
                                Semanal
                            </option>
                            <option value="monthly" {{ old('frecuencia_backup', $configuracion['frecuencia']) == 'monthly' ? 'selected' : '' }}>
                                Mensual
                            </option>
                        </select>
                        <div class="form-text">
                            Seleccione cada cuánto tiempo se ejecutará el backup automático.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="dias_retencion" class="form-label fw-bold">
                            <i class="fas fa-history me-2"></i>Días de Retención
                        </label>
                        <div class="input-group" style="max-width: 200px;">
                            <input type="number" 
                                   class="form-control @error('dias_retencion') is-invalid @enderror" 
                                   name="dias_retencion" 
                                   id="dias_retencion" 
                                   value="{{ old('dias_retencion', $configuracion['retencion']) }}"
                                   min="1" 
                                   max="365"
                                   required>
                            <span class="input-group-text">días</span>
                        </div>
                        @error('dias_retencion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Los backups anteriores a este período serán eliminados automáticamente.
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-undo me-2"></i>Restablecer
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold text-muted mb-2">Configuración Actual:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-{{ $configuracion['automatico'] ? 'success' : 'secondary' }} me-2"></i>
                            Backup automático: {{ $configuracion['automatico'] ? 'Activado' : 'Desactivado' }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar text-info me-2"></i>
                            Frecuencia: <span class="text-capitalize">{{ $configuracion['frecuencia'] }}</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Retención: {{ $configuracion['retencion'] }} días
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Recomendaciones
                </h5>
            </div>
            <div class="card-body">
                <ul class="mb-0 small">
                    <li class="mb-2">Realice backups diarios para sistemas con alta actividad.</li>
                    <li class="mb-2">Mantenga al menos 7 días de historial para recuperación ante desastres.</li>
                    <li class="mb-2">Considere 30 días de retención para un balance entre espacio y seguridad.</li>
                    <li>Almacene backups importantes en una ubicación externa periódicamente.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-body text-center">
                <a href="{{ route('backups.index') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-database me-2"></i>Ver Backups Disponibles
                </a>
                <a href="{{ route('backups.logs') }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="fas fa-list me-2"></i>Ver Historial de Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
