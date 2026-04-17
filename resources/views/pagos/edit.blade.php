@extends('layouts.app')

@section('title', 'Editar Pago - Tapicería Odami')

@section('page-title', 'Editar Pago')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Pagos</a></li>
<li class="breadcrumb-item"><a href="{{ route('pagos.show', $pago) }}">#{{ $pago->id }}</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('page-actions')
<a href="{{ route('pagos.show', $pago) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Volver
</a>
@endsection

@section('content')
<form action="{{ route('pagos.update', $pago) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Datos del Pago</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                        <select name="cliente_id" id="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('cliente_id') border-red-500 @enderror" required>
                            <option value="">Seleccionar cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $pago->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="factura_id" class="block text-sm font-medium text-gray-700 mb-1">Factura *</label>
                        <select name="factura_id" id="factura_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('factura_id') border-red-500 @enderror" required>
                            <option value="">Seleccionar factura...</option>
                            @foreach($facturas as $factura)
                                <option value="{{ $factura->id }}" {{ old('factura_id', $pago->factura_id) == $factura->id ? 'selected' : '' }}>
                                    {{ $factura->numero_completo }} - {{ number_format($factura->total, 2) }} €
                                </option>
                            @endforeach
                        </select>
                        @error('factura_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="monto" class="block text-sm font-medium text-gray-700 mb-1">Monto (€) *</label>
                        <input type="number" name="monto" id="monto" step="0.01" min="0.01" value="{{ old('monto', $pago->monto) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('monto') border-red-500 @enderror" required>
                        @error('monto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="fecha_pago" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Pago *</label>
                        <input type="date" name="fecha_pago" id="fecha_pago" value="{{ old('fecha_pago', $pago->fecha_pago->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('fecha_pago') border-red-500 @enderror" required>
                        @error('fecha_pago')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="metodo_pago" class="block text-sm font-medium text-gray-700 mb-1">Método de Pago *</label>
                        <select name="metodo_pago" id="metodo_pago" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('metodo_pago') border-red-500 @enderror" required>
                            <option value="">Seleccionar método...</option>
                            <option value="efectivo" {{ old('metodo_pago', $pago->metodo_pago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="transferencia" {{ old('metodo_pago', $pago->metodo_pago) == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                            <option value="tarjeta" {{ old('metodo_pago', $pago->metodo_pago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                            <option value="cheque" {{ old('metodo_pago', $pago->metodo_pago) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('metodo_pago')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select name="estado" id="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('estado') border-red-500 @enderror" required>
                            <option value="pendiente" {{ old('estado', $pago->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="completado" {{ old('estado', $pago->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="fallido" {{ old('estado', $pago->estado) == 'fallido' ? 'selected' : '' }}>Fallido</option>
                            <option value="reembolsado" {{ old('estado', $pago->estado) == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                        </select>
                        @error('estado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Referencia</label>
                        <input type="text" name="referencia" id="referencia" value="{{ old('referencia', $pago->referencia) }}" placeholder="Número de transferencia, comprobante..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('referencia') border-red-500 @enderror">
                        @error('referencia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3" placeholder="Notas adicionales sobre el pago..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('observaciones') border-red-500 @enderror">{{ old('observaciones', $pago->observaciones) }}</textarea>
                        @error('observaciones')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Comprobante</h3>
                
                @if($pago->comprobante_path)
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">Comprobante actual</p>
                            <a href="{{ route('pagos.descargar-comprobante', $pago) }}" class="text-xs text-green-600 hover:text-green-800">Descargar</a>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-4">Subir un nuevo archivo reemplazará el actual.</p>
                @endif
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="comprobante" id="comprobante" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="updateFileName(this)">
                    <label for="comprobante" class="cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Subir comprobante</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (máx. 5MB)</p>
                    </label>
                    <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
                </div>
                @error('comprobante')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="bg-amber-50 rounded-lg p-6 border border-amber-200">
                <h3 class="text-lg font-semibold text-amber-800 mb-4">Importante</h3>
                <ul class="space-y-2 text-sm text-amber-700">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>No se pueden editar pagos completados.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Si cambias el estado a "completado", se actualizará automáticamente.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex justify-end gap-4">
        <a href="{{ route('pagos.show', $pago) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            Cancelar
        </a>
        <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Actualizar Pago
        </button>
    </div>
</form>

@push('scripts')
<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        document.getElementById('file-name').textContent = fileName;
    }
</script>
@endpush
@endsection
