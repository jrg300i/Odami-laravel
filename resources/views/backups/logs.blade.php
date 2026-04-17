@extends('layouts.app')

@section('title', 'Logs de Backup - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-list me-2"></i>Logs de Backup
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('backups.index') }}">Backups</a></li>
                        <li class="breadcrumb-item active">Logs</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('backups.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver a Backups
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['total'] }}</h4>
                <small>Total Logs</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['completados'] }}</h4>
                <small>Completados</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['fallidos'] }}</h4>
                <small>Fallidos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($estadisticas['tamanio_total'], 1) }} MB</h4>
                <small>Espacio Total</small>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Logs -->
<div class="card shadow">
    <div class="card-header">
        <h5 class="card-title mb-0">Historial de Actividades de Backup</h5>
    </div>
    <div class="card-body">
        @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Tipo</th>
                            <th>Archivo</th>
                            <th>Estado</th>
                            <th>Tamaño</th>
                            <th>Duración</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">{{ $log->tipo }}</span>
                                </td>
                                <td>
                                    @if($log->archivo)
                                        <i class="fas fa-file-archive text-warning me-1"></i>
                                        {{ $log->archivo }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $log->estado_color }}">
                                        {{ $log->estado }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->tamanio)
                                        {{ $log->tamanio }} MB
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->duracion_formateada)
                                        {{ $log->duracion_formateada }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->observaciones)
                                        <span title="{{ $log->observaciones }}">
                                            {{ Str::limit($log->observaciones, 50) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->archivo && Storage::exists('backups/manuales/' . $log->archivo))
                                        <a href="{{ route('backups.descargar', $log->archivo) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Descargar backup">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Mostrando {{ $logs->firstItem() }} - {{ $logs->lastItem() }} de {{ $logs->total() }} logs
                </div>
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-list fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay logs registrados</h4>
                <p class="text-muted">Los logs aparecerán aquí después de realizar operaciones de backup.</p>
            </div>
        @endif
    </div>
</div>
@endsection