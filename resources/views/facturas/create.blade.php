@extends('layouts.app')

@section('title', 'Nueva Factura - Tapicería Odami')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nueva Factura</h1>
            <p class="text-gray-600">Crear una nueva factura para un cliente</p>
        </div>
        <a href="{{ route('facturas.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <form action="{{ route('facturas.store') }}" method="POST" id="facturaForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Datos de la Factura</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="serie" class="block text-sm font-medium text-gray-700 mb-1">Serie</label>
                        <select name="serie" id="serie" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                            <option value="">Seleccionar serie</option>
                            @foreach($series as $s)
                                <option value="{{ $s->serie }}" {{ old('serie') == $s->serie ? 'selected' : '' }}>
                                    {{ $s->serie }} - {{ $s->descripcion ?? 'Siguiente: ' . $s->obtenerSiguienteNumero() }}
                                </option>
                            @endforeach
                        </select>
                        @error('serie')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                            <option value="">Seleccionar cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre_completo }} ({{ $cliente->dni_cif }})
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="trabajo_id" class="block text-sm font-medium text-gray-700 mb-1">Trabajo (opcional)</label>
                        <select name="trabajo_id" id="trabajo_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Sin trabajo asociado</option>
                            @foreach($trabajos as $trabajo)
                                <option value="{{ $trabajo->id }}" {{ old('trabajo_id') == $trabajo->id ? 'selected' : '' }}
                                        data-cliente="{{ $trabajo->cliente_id }}">
                                    {{ $trabajo->codigo_trabajo }} - {{ $trabajo->titulo }}
                                </option>
                            @endforeach
                        </select>
                        @error('trabajo_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                               value="{{ old('fecha_vencimiento', now()->addDays(30)->format('Y-m-d')) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @error('fecha_vencimiento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="concepto" class="block text-sm font-medium text-gray-700 mb-1">Concepto</label>
                    <input type="text" name="concepto" id="concepto" value="{{ old('concepto') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                           placeholder="Descripción general de la factura" required>
                    @error('concepto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="forma_pago" class="block text-sm font-medium text-gray-700 mb-1">Forma de Pago</label>
                        <select name="forma_pago" id="forma_pago" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Seleccionar</option>
                            <option value="transferencia" {{ old('forma_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia bancaria</option>
                            <option value="efectivo" {{ old('forma_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="tarjeta" {{ old('forma_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="bizum" {{ old('forma_pago') == 'bizum' ? 'selected' : '' }}>Bizum</option>
                            <option value="cheque" {{ old('forma_pago') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>

                    <div>
                        <label for="iva" class="block text-sm font-medium text-gray-700 mb-1">IVA (%)</label>
                        <select name="iva" id="iva" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                            <option value="21" {{ old('iva', 21) == 21 ? 'selected' : '' }}>21% (General)</option>
                            <option value="10" {{ old('iva') == 10 ? 'selected' : '' }}>10% (Reducido)</option>
                            <option value="4" {{ old('iva') == 4 ? 'selected' : '' }}>4% (Superreducido)</option>
                            <option value="0" {{ old('iva') == 0 ? 'selected' : '' }}>0% (Exento)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="incluir_clausulas" id="incluir_clausulas" value="1"
                               {{ old('incluir_clausulas') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500">
                        <label for="incluir_clausulas" class="ml-2 text-sm text-gray-700">
                            Incluir cláusulas legales en el PDF
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                              placeholder="Notas adicionales para la factura">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen</h2>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" id="subtotalDisplay">0.00 €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">IVA (<span id="ivaDisplay">21</span>%):</span>
                        <span class="font-medium" id="ivaAmountDisplay">0.00 €</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span class="text-lg font-bold text-amber-600" id="totalDisplay">0.00 €</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Líneas de Factura</h2>
                <button type="button" id="addLinea" class="text-sm bg-amber-100 text-amber-700 px-3 py-1 rounded-md hover:bg-amber-200">
                    + Agregar línea
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="lineasTable">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-2 font-medium text-gray-700 w-1/2">Descripción</th>
                            <th class="text-center py-2 px-2 font-medium text-gray-700 w-20">Cantidad</th>
                            <th class="text-right py-2 px-2 font-medium text-gray-700 w-28">Precio Unit.</th>
                            <th class="text-right py-2 px-2 font-medium text-gray-700 w-24">Total</th>
                            <th class="w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="lineasBody">
                        <tr class="linea-row border-b border-gray-100">
                            <td class="py-2 px-2">
                                <input type="text" name="lineas[0][descripcion]" placeholder="Descripción del producto/servicio"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm descripcion-input">
                            </td>
                            <td class="py-2 px-2">
                                <input type="number" name="lineas[0][cantidad]" min="0.01" step="0.01" value="1"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm text-center cantidad-input">
                            </td>
                            <td class="py-2 px-2">
                                <input type="number" name="lineas[0][precio]" min="0" step="0.01" placeholder="0.00"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm text-right precio-input">
                            </td>
                            <td class="py-2 px-2 text-right font-medium total-linea">0.00 €</td>
                            <td class="py-2 px-2 text-center">
                                <button type="button" class="text-red-500 hover:text-red-700 removeLinea">×</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @error('lineas')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('facturas.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                Guardar Factura
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let lineaIndex = 1;
    const ivaSelect = document.getElementById('iva');
    const lineasBody = document.getElementById('lineasBody');

    function calcularTotales() {
        let subtotal = 0;
        const iva = parseFloat(ivaSelect.value) || 0;

        document.querySelectorAll('.linea-row').forEach(row => {
            const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
            const totalLinea = cantidad * precio;
            subtotal += totalLinea;
            row.querySelector('.total-linea').textContent = totalLinea.toFixed(2) + ' €';
        });

        const ivaAmount = subtotal * (iva / 100);
        const total = subtotal + ivaAmount;

        document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2) + ' €';
        document.getElementById('ivaDisplay').textContent = iva;
        document.getElementById('ivaAmountDisplay').textContent = ivaAmount.toFixed(2) + ' €';
        document.getElementById('totalDisplay').textContent = total.toFixed(2) + ' €';
    }

    function addLinea() {
        const newRow = document.createElement('tr');
        newRow.className = 'linea-row border-b border-gray-100';
        newRow.innerHTML = `
            <td class="py-2 px-2">
                <input type="text" name="lineas[${lineaIndex}][descripcion]" placeholder="Descripción del producto/servicio"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm descripcion-input">
            </td>
            <td class="py-2 px-2">
                <input type="number" name="lineas[${lineaIndex}][cantidad]" min="0.01" step="0.01" value="1"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm text-center cantidad-input">
            </td>
            <td class="py-2 px-2">
                <input type="number" name="lineas[${lineaIndex}][precio]" min="0" step="0.01" placeholder="0.00"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm text-right precio-input">
            </td>
            <td class="py-2 px-2 text-right font-medium total-linea">0.00 €</td>
            <td class="py-2 px-2 text-center">
                <button type="button" class="text-red-500 hover:text-red-700 removeLinea">×</button>
            </td>
        `;
        lineasBody.appendChild(newRow);
        attachLineaEvents(newRow);
        lineaIndex++;
    }

    function attachLineaEvents(row) {
        row.querySelector('.cantidad-input').addEventListener('input', calcularTotales);
        row.querySelector('.precio-input').addEventListener('input', calcularTotales);
        row.querySelector('.removeLinea').addEventListener('click', function() {
            if (document.querySelectorAll('.linea-row').length > 1) {
                row.remove();
                calcularTotales();
            }
        });
    }

    document.getElementById('addLinea').addEventListener('click', addLinea);
    ivaSelect.addEventListener('change', calcularTotales);

    document.querySelectorAll('.linea-row').forEach(row => attachLineaEvents(row));
    calcularTotales();

    document.getElementById('cliente_id').addEventListener('change', function() {
        const clienteId = this.value;
        document.querySelectorAll('#trabajo_id option[data-cliente]').forEach(opt => {
            if (opt.dataset.cliente === clienteId || opt.value === '') {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
