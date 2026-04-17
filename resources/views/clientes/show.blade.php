@extends('layouts.app')

@section('title', $cliente->nombre_completo . ' - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-user me-2"></i>{{ $cliente->nombre_completo }}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
                        <li class="breadcrumb-item active">{{ $cliente->codigo_cliente }}</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-secondary">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('trabajos.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Trabajo
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información del Cliente -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información del Cliente
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                        <span class="text-white fw-bold fs-3">
                            {{ substr($cliente->nombre, 0, 1) }}{{ substr($cliente->apellido, 0, 1) }}
                        </span>
                    </div>
                    <h4 class="mt-3">{{ $cliente->nombre_completo }}</h4>
                    <span class="badge bg-{{ $cliente->tipo == 'empresa' ? 'info' : 'secondary' }} fs-6">
                        {{ $cliente->tipo }}
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Código:</label>
                    <p class="mb-0">{{ $cliente->codigo_cliente }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email:</label>
                    <p class="mb-0">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        <a href="mailto:{{ $cliente->email }}">{{ $cliente->email }}</a>
                    </p>
                </div>

                @if($cliente->telefono)
                <div class="mb-3">
                    <label class="form-label fw-bold">Teléfono:</label>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        <a href="tel:{{ $cliente->telefono }}">{{ $cliente->telefono }}</a>
                    </p>
                </div>
                @endif

                @if($cliente->dni_cif)
                <div class="mb-3">
                    <label class="form-label fw-bold">DNI/CIF:</label>
                    <p class="mb-0">{{ $cliente->dni_cif }}</p>
                </div>
                @endif

                @if($cliente->direccion)
                <div class="mb-3">
                    <label class="form-label fw-bold">Dirección:</label>
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                        {{ $cliente->direccion }}
                        @if($cliente->ciudad)
                            <br><small class="text-muted">{{ $cliente->ciudad }}</small>
                        @endif
                        @if($cliente->codigo_postal)
                            <br><small class="text-muted">{{ $cliente->codigo_postal }}</small>
                        @endif
                    </p>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Estado:</label>
                    <p class="mb-0">
                        <span class="badge bg-{{ $cliente->activo ? 'success' : 'danger' }}">
                            {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>

                @if($cliente->notas)
                <div class="mb-3">
                    <label class="form-label fw-bold">Notas:</label>
                    <p class="mb-0 text-muted">{{ $cliente->notas }}</p>
                </div>
                @endif

                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        Cliente desde: {{ $cliente->created_at->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas y Trabajos -->
    <div class="col-lg-8">
        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="card-title">{{ $estadisticas['total_trabajos'] }}</h3>
                        <p class="card-text">Total Trabajos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="card-title">{{ $estadisticas['trabajos_completados'] }}</h3>
                        <p class="card-text">Completados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="card-title">{{ number_format($estadisticas['total_facturado'], 0) }}€</h3>
                        <p class="card-text">Total Facturado</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="card-title">{{ number_format($estadisticas['total_pagado'], 0) }}€</h3>
                        <p class="card-text">Total Pagado</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trabajos del Cliente -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>Trabajos del Cliente
                </h5>
                <a href="{{ route('trabajos.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Nuevo Trabajo
                </a>
            </div>
            <div class="card-body">
                @if($cliente->trabajos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Costo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->trabajos->take(5) as $trabajo)
                                    <tr>
                                        <td>{{ $trabajo->codigo_trabajo }}</td>
                                        <td>{{ Str::limit($trabajo->titulo, 30) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $trabajo->tipo }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $trabajo->estado_color }}">
                                                {{ $trabajo->estado }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($trabajo->costo_final ?? $trabajo->costo_estimado, 2) }} €</td>
                                        <td>
                                            <a href="{{ route('trabajos.show', $trabajo) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($cliente->trabajos->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('trabajos.index') }}?cliente_id={{ $cliente->id }}" 
                               class="btn btn-sm btn-outline-primary">
                                Ver todos los trabajos ({{ $cliente->trabajos->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-tools fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Este cliente no tiene trabajos registrados</p>
                        <a href="{{ route('trabajos.create') }}?cliente_id={{ $cliente->id }}" 
                           class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Crear Primer Trabajo
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Facturas del Cliente -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-invoice me-2"></i>Facturas del Cliente
                </h5>
            </div>
            <div class="card-body">
                @if($cliente->facturas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->facturas->take(5) as $factura)
                                    <tr>
                                        <td>{{ $factura->numero_completo }}</td>
                                        <td>{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($factura->concepto, 30) }}</td>
                                        <td>{{ number_format($factura->total, 2) }} €</td>
                                        <td>
                                            <span class="badge bg-{{ $factura->estado_color }}">
                                                {{ $factura->estado }}
                                            </span>
                                        </td>
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
                    @if($cliente->facturas->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('facturas.index') }}?cliente_id={{ $cliente->id }}" 
                               class="btn btn-sm btn-outline-primary">
                                Ver todas las facturas ({{ $cliente->facturas->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-invoice fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Este cliente no tiene facturas registradas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-lg {
        width: 80px;
        height: 80px;
        font-size: 24px;
    }
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
</style>
@endpush