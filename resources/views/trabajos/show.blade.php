@extends('layouts.app')

@section('title', $trabajo->titulo . ' - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-tools me-2"></i>{{ $trabajo->titulo }}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('trabajos.index') }}">Trabajos</a></li>
                        <li class="breadcrumb-item active">{{ $trabajo->codigo_trabajo }}</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group">
                <a href="{{ route('trabajos.edit', $trabajo) }}" class="btn btn-secondary">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('trabajos.fotos.index', $trabajo) }}" class="btn btn-info">
                    <i class="fas fa-camera me-2"></i>Fotos
                </a>
                @if($trabajo->estado != 'completado')
                    <form action="{{ route('trabajos.completar', $trabajo) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('¿Marcar este trabajo como completado?')">
                            <i class="fas fa-check me-2"></i>Completar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información Principal -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información del Trabajo
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Código:</label>
                            <p class="mb-0">{{ $trabajo->codigo_trabajo }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente:</label>
                            <p class="mb-0">
                                <a href="{{ route('clientes.show', $trabajo->cliente) }}">
                                    {{ $trabajo->cliente->nombre_completo }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo:</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ $trabajo->tipo }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Estado:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $trabajo->estado_color }} fs-6">
                                    {{ $trabajo->estado }}
                                </span>
                                @if($trabajo->urgente)
                                    <span class="badge bg-danger fs-6">Urgente</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Prioridad:</label>
                            <p class="mb-0">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $trabajo->prioridad ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                ({{ $trabajo->prioridad }}/5)
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha Inicio:</label>
                            <p class="mb-0">
                                {{ $trabajo->fecha_inicio ? $trabajo->fecha_inicio->format('d/m/Y') : 'No asignada' }}
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha Fin Estimada:</label>
                            <p class="mb-0">
                                {{ $trabajo->fecha_fin_estimada ? $trabajo->fecha_fin_estimada->format('d/m/Y') : 'No asignada' }}
                                @if($trabajo->dias_restantes !== null && $trabajo->estado != 'completado')
                                    <span class="badge bg-{{ $trabajo->dias_restantes < 0 ? 'danger' : ($trabajo->dias_restantes < 3 ? 'warning' : 'success') }}">
                                        {{ $trabajo->dias_restantes }} días
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        @if($trabajo->fecha_fin_real)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha Fin Real:</label>
                            <p class="mb-0">{{ $trabajo->fecha_fin_real->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if($trabajo->descripcion)
                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción:</label>
                    <p class="mb-0">{{ $trabajo->descripcion }}</p>
                </div>
                @endif

                @if($trabajo->observaciones_cliente)
                <div class="mb-3">
                    <label class="form-label fw-bold">Observaciones del Cliente:</label>
                    <p class="mb-0 text-muted">{{ $trabajo->observaciones_cliente }}</p>
                </div>
                @endif

                @if($trabajo->notas_internas)
                <div class="mb-3">
                    <label class="form-label fw-bold">Notas Internas:</label>
                    <p class="mb-0 text-muted bg-light p-3 rounded">{{ $trabajo->notas_internas }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Materiales Utilizados -->
        @if($trabajo->materiales->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-boxes me-2"></i>Materiales Utilizados
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Costo Unitario</th>
                                <th>Costo Total</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trabajo->materiales as $material)
                                <tr>
                                    <td>
                                        <strong>{{ $material->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $material->tipo }} - {{ $material->color }}</small>
                                    </td>
                                    <td>{{ $material->pivot->cantidad }}</td>
                                    <td>{{ $material->pivot->unidad_medida }}</td>
                                    <td>{{ number_format($material->pivot->costo_total / $material->pivot->cantidad, 2) }} €</td>
                                    <td>{{ number_format($material->pivot->costo_total, 2) }} €</td>
                                    <td>
                                        <small class="text-muted">{{ $material->pivot->observaciones }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end fw-bold">Total Materiales:</td>
                                <td class="fw-bold">{{ number_format($costos['materiales'], 2) }} €</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Facturas Relacionadas -->
        @if($trabajo->facturas->count() > 0)
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-invoice me-2"></i>Facturas Relacionadas
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trabajo->facturas as $factura)
                                <tr>
                                    <td>{{ $factura->numero_completo }}</td>
                                    <td>{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $factura->estado_color }}">
                                            {{ $factura->estado }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($factura->total, 2) }} €</td>
                                    <td>
                                        <a href="{{ route('facturas.show', $factura) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar con Costos y Acciones -->
    <div class="col-lg-4">
        <!-- Resumen de Costos -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-euro-sign me-2"></i>Resumen de Costos
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Costo Estimado:</label>
                    <p class="mb-0 fs-5 text-primary">
                        {{ number_format($costos['estimado'], 2) }} €
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Costo Final:</label>
                    <p class="mb-0 fs-5 {{ $trabajo->costo_final ? 'text-success' : 'text-muted' }}">
                        {{ $trabajo->costo_final ? number_format($costos['final'], 2) . ' €' : 'No asignado' }}
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Costos de Materiales:</label>
                    <p class="mb-0 text-info">
                        {{ number_format($costos['materiales'], 2) }} €
                    </p>
                </div>

                @if($trabajo->costo_final && $costos['materiales'] > 0)
                <div class="mb-3">
                    <label class="form-label fw-bold">Margen Estimado:</label>
                    <p class="mb-0 text-success">
                        {{ number_format($trabajo->costo_final - $costos['materiales'], 2) }} €
                        ({{ number_format((($trabajo->costo_final - $costos['materiales']) / $trabajo->costo_final) * 100, 1) }}%)
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Fotos del Trabajo -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-camera me-2"></i>Galería de Fotos
                </h5>
            </div>
            <div class="card-body">
                @if($trabajo->fotos->count() > 0)
                    <div class="row g-2">
                        @foreach($trabajo->fotos->take(6) as $foto)
                            <div class="col-4">
                                <a href="{{ $foto->url_comprimida }}" data-lightbox="trabajo-{{ $trabajo->id }}">
                                    <img src="{{ $foto->url_miniatura }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $foto->titulo }}"
                                         style="height: 80px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('trabajos.fotos.index', $trabajo) }}" class="btn btn-sm btn-outline-primary">
                            Ver todas las fotos ({{ $trabajo->fotos->count() }})
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay fotos registradas</p>
                        <a href="{{ route('trabajos.fotos.create', $trabajo) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i>Agregar Fotos
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('trabajos.fotos.create', $trabajo) }}" class="btn btn-outline-primary">
                        <i class="fas fa-camera me-2"></i>Agregar Fotos
                    </a>
                    
                    @if($trabajo->facturas->count() == 0)
                        <a href="{{ route('facturas.create') }}?trabajo_id={{ $trabajo->id }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-file-invoice me-2"></i>Crear Factura
                        </a>
                    @endif
                    
                    <a href="{{ route('trabajos.edit', $trabajo) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-2"></i>Editar Trabajo
                    </a>
                    
                    @if($trabajo->estado != 'completado')
                        <form action="{{ route('trabajos.completar', $trabajo) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-success"
                                    onclick="return confirm('¿Marcar este trabajo como completado?')">
                                <i class="fas fa-check me-2"></i>Marcar Completado
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'imageFadeDuration': 300
    });
</script>
@endpush