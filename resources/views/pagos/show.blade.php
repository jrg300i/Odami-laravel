@extends('layouts.app')

@section('title', 'Pago - Tapicería Odami')

@section('page-title', 'Detalle del Pago')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Pagos</a></li>
<li class="breadcrumb-item active">#{{ $pago->id }}</li>
@endsection

@section('page-actions')
<div class="flex gap-2">
    <a href="{{ route('pagos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver
    </a>
    @if($pago->estado != 'completado')
        <a href="{{ route('pagos.edit', $pago) }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </a>
    @endif
    @if($pago->estado == 'pendiente')
        <form action="{{ route('pagos.marcar-completado', $pago) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('¿Marcar este pago como completado?')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Completar
            </button>
        </form>
    @endif
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Pago #{{ $pago->id }}</h2>
                    <p class="text-sm text-gray-500">Registrado el {{ $pago->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @php
                    $estadoColors = [
                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                        'completado' => 'bg-green-100 text-green-800',
                        'fallido' => 'bg-red-100 text-red-800',
                        'reembolsado' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $estadoColors[$pago->estado] }}">
                    {{ $pago->estado_formateado }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Pago</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Monto</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $pago->monto_formateado }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Fecha de Pago</p>
                            <p class="text-gray-900">{{ $pago->fecha_pago->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Método de Pago</p>
                            <p class="text-gray-900">{{ $pago->metodo_pago_formateado }}</p>
                        </div>
                        
                        @if($pago->referencia)
                        <div>
                            <p class="text-sm text-gray-500">Referencia</p>
                            <p class="text-gray-900 font-mono">{{ $pago->referencia }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Relaciones</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Cliente</p>
                            @if($pago->cliente)
                                <a href="{{ route('clientes.show', $pago->cliente) }}" class="text-amber-600 hover:text-amber-800 font-medium">
                                    {{ $pago->cliente->nombre_completo }}
                                </a>
                            @else
                                <p class="text-gray-400">No asignado</p>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Factura</p>
                            @if($pago->factura)
                                <a href="{{ route('facturas.show', $pago->factura) }}" class="text-amber-600 hover:text-amber-800 font-medium">
                                    {{ $pago->factura->numero_completo }}
                                </a>
                                <p class="text-sm text-gray-500">{{ number_format($pago->factura->total, 2) }} €</p>
                            @else
                                <p class="text-gray-400">No asignada</p>
                            @endif
                        </div>
                        
                        @if($pago->factura && $pago->factura->trabajo)
                        <div>
                            <p class="text-sm text-gray-500">Trabajo</p>
                            <a href="{{ route('trabajos.show', $pago->factura->trabajo) }}" class="text-amber-600 hover:text-amber-800 font-medium">
                                {{ $pago->factura->trabajo->titulo }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($pago->observaciones)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Observaciones</h3>
                <p class="text-gray-600">{{ $pago->observaciones }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <div class="lg:col-span-1">
        @if($pago->tiene_comprobante)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Comprobante</h3>
            <div class="border border-gray-200 rounded-lg p-4 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">Comprobante adjunto</p>
                <a href="{{ route('pagos.descargar-comprobante', $pago) }}" class="mt-3 inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </a>
            </div>
        </div>
        @endif
        
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
            <div class="space-y-3">
                @if($pago->estado == 'pendiente')
                    <form action="{{ route('pagos.marcar-completado', $pago) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('¿Marcar como completado?')" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Marcar como Completado
                        </button>
                    </form>
                @endif
                
                @if($pago->estado != 'completado')
                    <a href="{{ route('pagos.edit', $pago) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Pago
                    </a>
                    
                    <form action="{{ route('pagos.destroy', $pago) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Eliminar este pago? Esta acción no se puede deshacer.')" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar Pago
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        @if($pago->cliente)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos del Cliente</h3>
            <div class="space-y-3 text-sm">
                <p class="font-medium text-gray-900">{{ $pago->cliente->nombre_completo }}</p>
                @if($pago->cliente->dni_cif)
                    <p class="text-gray-600">DNI/CIF: {{ $pago->cliente->dni_cif }}</p>
                @endif
                @if($pago->cliente->telefono)
                    <p class="text-gray-600">Tel: {{ $pago->cliente->telefono }}</p>
                @endif
                @if($pago->cliente->email)
                    <p class="text-gray-600">Email: {{ $pago->cliente->email }}</p>
                @endif
                <a href="{{ route('clientes.show', $pago->cliente) }}" class="inline-flex items-center text-amber-600 hover:text-amber-800 mt-2">
                    Ver perfil completo
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
