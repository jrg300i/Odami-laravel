@extends('layouts.app')

@section('title', 'Trabajos - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-tools me-2"></i>Trabajos
            </h1>
            <a href="{{ route('trabajos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Trabajo
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['total'] }}</h4>
                <small>Total Trabajos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['en_proceso'] }}</h4>
                <small>En Proceso</small>
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
                <h4 class="card-title mb-0">{{ $estadisticas['urgentes'] }}</h4>
                <small>Urgentes</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('trabajos.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar trabajos..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="presupuesto" {{ request('estado') == 'presupuesto' ? 'selected' : '' }}>
                            Presupuesto
                        </option>
                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>
                            En Proceso
                        </option>
                        <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>
                            Completado
                        </option>
                        <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>
                            Entregado
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tipo" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los tipos</option>
                        <option value="silla" {{ request('tipo') == 'silla' ? 'selected' : '' }}>Sillas</option>
                        <option value="sofa" {{ request('tipo') == 'sofa' ? 'selected' : '' }}>Sofás</option>
                        <option value="sillon" {{ request('tipo') == 'sillon' ? 'selected' : '' }}>Sillones</option>
                        <option value="cabecero" {{ request('tipo') == 'cabecero' ? 'selected' : '' }}>Cabeceros</option>
                        <option value="butaca" {{ request('tipo') == 'butaca' ? 'selected' : '' }}>Butacas</option>
                        <option value="personalizado" {{ request('tipo') == 'personalizado' ? 'selected' : '' }}>Personalizados</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check form-switch pt-2">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="urgente" 
                               value="1" 
                               {{ request('urgente') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label">Solo urgentes</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Trabajos -->
<div class="row">
    @forelse($trabajos as $trabajo)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">{{ $trabajo->codigo_trabajo }}</h6>
                        <div>
                            @if($trabajo->urgente)
                                <span class="badge bg-danger">Urgente</span>
                            @endif
                            <span class="badge bg-{{ $trabajo->estado_color }}">{{ $trabajo->estado }}</span>
                        </div>
                    </div>
                </div>
                
                @if($trabajo->foto_principal)
                    <img src="{{ $trabajo->foto_principal->url_miniatura }}" 
                         class="card-img-top" 
                         alt="{{ $trabajo->titulo }}"
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-couch fa-3x text-muted"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($trabajo->titulo, 50) }}</h5>
                    <p class="card-text text-muted small">
                        <i class="fas fa-user me-1"></i>
                        {{ $trabajo->cliente->nombre_completo }}
                    </p>
                    
                    @if($trabajo->descripcion)
                        <p class="card-text small">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                    @endif

                    <div class="mb-3">
                        <span class="badge bg-secondary">{{ $trabajo->tipo }}</span>
                        <span class="badge bg-info">Prioridad: {{ $trabajo->prioridad }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-primary">
                                {{ number_format($trabajo->costo_final ?? $trabajo->costo_estimado, 2) }} €
                            </strong>
                        </div>
                        <small class="text-muted">
                            {{ $trabajo->created_at->diffForHumans() }}
                        </small>
                    </div>

                    @if($trabajo->fecha_fin_estimada && $trabajo->estado != 'completado')
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Entrega: {{ $trabajo->fecha_fin_estimada->format('d/m/Y') }}
                                @if($trabajo->dias_restantes !== null)
                                    <span class="badge bg-{{ $trabajo->dias_restantes < 0 ? 'danger' : ($trabajo->dias_restantes < 3 ? 'warning' : 'success') }}">
                                        {{ $trabajo->dias_restantes }} días
                                    </span>
                                @endif
                            </small>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100">
                        <a href="{{ route('trabajos.show', $trabajo) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('trabajos.edit', $trabajo) }}" 
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('trabajos.fotos.index', $trabajo) }}" 
                           class="btn btn-sm btn-outline-info">
                            <i class="fas fa-camera"></i>
                        </a>
                        @if($trabajo->estado != 'completado')
                            <form action="{{ route('trabajos.completar', $trabajo) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-success"
                                        onclick="return confirm('¿Marcar este trabajo como completado?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron trabajos</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'estado', 'tipo', 'urgente']))
                            Intenta ajustar los filtros de búsqueda
                        @else
                            Comienza agregando tu primer trabajo
                        @endif
                    </p>
                    <a href="{{ route('trabajos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Agregar Trabajo
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Paginación -->
@if($trabajos->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Mostrando {{ $trabajos->firstItem() }} - {{ $trabajos->lastItem() }} de {{ $trabajos->total() }} trabajos
        </div>
        {{ $trabajos->links() }}
    </div>
@endif
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .estado-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>
@endpush