@extends('layouts.app')

@section('title', 'Clientes - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-users me-2"></i>Clientes
            </h1>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Cliente
            </a>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('clientes.index') }}" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar clientes..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="tipo" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los tipos</option>
                        <option value="particular" {{ request('tipo') == 'particular' ? 'selected' : '' }}>
                            Particulares
                        </option>
                        <option value="empresa" {{ request('tipo') == 'empresa' ? 'selected' : '' }}>
                            Empresas
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="activo" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Clientes -->
<div class="card shadow">
    <div class="card-body">
        @if($clientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Tipo</th>
                            <th>Trabajos</th>
                            <th>Facturación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                            <tr>
                                <td>
                                    <strong>{{ $cliente->codigo_cliente }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <span class="text-white fw-bold">
                                                {{ substr($cliente->nombre, 0, 1) }}{{ substr($cliente->apellido, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $cliente->nombre_completo }}</h6>
                                            @if($cliente->dni_cif)
                                                <small class="text-muted">{{ $cliente->dni_cif }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        <small>{{ $cliente->email }}</small>
                                    </div>
                                    @if($cliente->telefono)
                                        <div>
                                            <i class="fas fa-phone me-1 text-muted"></i>
                                            <small>{{ $cliente->telefono }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $cliente->tipo == 'empresa' ? 'info' : 'secondary' }}">
                                        {{ $cliente->tipo }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $cliente->trabajos_completados_count }}/{{ $cliente->trabajos->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        <strong>{{ number_format($cliente->total_facturado, 2) }} €</strong>
                                    </div>
                                    <small class="text-muted">
                                        Pagado: {{ number_format($cliente->total_pagado, 2) }} €
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $cliente->activo ? 'success' : 'danger' }}">
                                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('clientes.show', $cliente) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clientes.edit', $cliente) }}" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($cliente->trabajos->count() == 0 && $cliente->facturas->count() == 0)
                                            <form action="{{ route('clientes.destroy', $cliente) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Mostrando {{ $clientes->firstItem() }} - {{ $clientes->lastItem() }} de {{ $clientes->total() }} clientes
                </div>
                {{ $clientes->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron clientes</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'tipo', 'activo']))
                        Intenta ajustar los filtros de búsqueda
                    @else
                        Comienza agregando tu primer cliente
                    @endif
                </p>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Agregar Cliente
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
</style>
@endpush