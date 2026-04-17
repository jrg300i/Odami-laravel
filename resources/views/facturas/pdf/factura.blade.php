<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura {{ $factura->numero_completo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #B45309;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #92400E;
            margin-bottom: 10px;
        }

        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.6;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 14px;
            color: #4B5563;
            margin-bottom: 5px;
        }

        .invoice-date {
            font-size: 11px;
            color: #6B7280;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 4px;
        }

        .client-info {
            font-size: 11px;
            line-height: 1.6;
        }

        .client-name {
            font-size: 13px;
            font-weight: bold;
            color: #1F2937;
        }

        .grid-row {
            display: table;
            width: 100%;
        }

        .grid-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .grid-col-3 {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
        }

        .invoice-details {
            font-size: 11px;
            line-height: 1.8;
        }

        .invoice-details strong {
            color: #374151;
        }

        .status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-paid {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .status-cancelled {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .status-draft {
            background-color: #E5E7EB;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background-color: #92400E;
            color: white;
        }

        th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th.text-right {
            text-align: right;
        }

        th.text-center {
            text-align: center;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 11px;
        }

        td.text-right {
            text-align: right;
        }

        td.text-center {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .totals {
            margin-top: 20px;
            width: 280px;
            margin-left: auto;
        }

        .totals table {
            margin-bottom: 0;
        }

        .totals td {
            padding: 6px 8px;
            border: none;
        }

        .totals .label {
            text-align: right;
            color: #6B7280;
        }

        .totals .value {
            text-align: right;
            font-weight: bold;
            width: 100px;
        }

        .totals .grand-total {
            font-size: 16px;
            color: #B45309;
            background-color: #FEF3C7;
            border-radius: 4px;
        }

        .totals .grand-total td {
            padding: 12px 8px;
        }

        .concept-section {
            margin-bottom: 20px;
            padding: 12px;
            background-color: #F9FAFB;
            border-radius: 4px;
            border-left: 3px solid #B45309;
        }

        .concept-text {
            font-size: 11px;
            color: #374151;
        }

        .observations {
            font-size: 10px;
            color: #6B7280;
            font-style: italic;
            margin-top: 8px;
        }

        .payment-info {
            font-size: 11px;
            line-height: 1.8;
            color: #374151;
        }

        .clauses {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
        }

        .clauses-title {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
        }

        .clause-item {
            font-size: 9px;
            color: #6B7280;
            margin-bottom: 8px;
            text-align: justify;
        }

        .clause-number {
            font-weight: bold;
            color: #374151;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #9CA3AF;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }

        .footer-page {
            position: fixed;
            bottom: 10px;
            right: 20px;
            font-size: 9px;
            color: #9CA3AF;
        }

        @page {
            margin: 20mm;
            margin-bottom: 30mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="company-name">{{ config('app.name', 'Tapicería Odami') }}</div>
                <div class="company-info">
                    <strong>Tapicería Odami</strong><br>
                    {{\App\Models\Configuracion::obtener('empresa_direccion', 'Calle Principal 123') }}<br>
                    {{\App\Models\Configuracion::obtener('empresa_ciudad', 'Madrid') }}, {{\App\Models\Configuracion::obtener('empresa_codigo_postal', '28001') }}<br>
                    Tel: {{\App\Models\Configuracion::obtener('empresa_telefono', '+34 912 345 678') }}<br>
                    Email: {{\App\Models\Configuracion::obtener('empresa_email', 'info@tapiceria-odami.com') }}<br>
                    CIF: {{\App\Models\Configuracion::obtener('empresa_cif', 'B12345678') }}
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">FACTURA</div>
                <div class="invoice-number">{{ $factura->numero_completo }}</div>
                <div class="invoice-date">
                    <strong>Fecha:</strong> {{ $factura->fecha_emision->format('d/m/Y') }}<br>
                    @if($factura->fecha_vencimiento)
                    <strong>Vence:</strong> {{ $factura->fecha_vencimiento->format('d/m/Y') }}
                    @endif
                </div>
                <br>
                <span class="status status-{{ $factura->estado == 'pagada' ? 'paid' : ($factura->estado == 'cancelada' ? 'cancelled' : ($factura->estado == 'borrador' ? 'draft' : 'pending')) }}">
                    {{ strtoupper($factura->estado) }}
                </span>
            </div>
        </div>

        <div class="grid-row section">
            <div class="grid-col">
                <div class="section-title">Datos del Cliente</div>
                <div class="client-info">
                    <div class="client-name">{{ $factura->cliente->nombre_completo }}</div>
                    <div>{{ $factura->cliente->direccion }}</div>
                    <div>{{ $factura->cliente->ciudad }} {{ $factura->cliente->codigo_postal }}</div>
                    <div>{{ $factura->cliente->dni_cif }}</div>
                    @if($factura->cliente->email)
                    <div>{{ $factura->cliente->email }}</div>
                    @endif
                    @if($factura->cliente->telefono)
                    <div>Tel: {{ $factura->cliente->telefono }}</div>
                    @endif
                </div>
            </div>
            <div class="grid-col">
                <div class="section-title">Detalles</div>
                <div class="invoice-details">
                    @if($factura->trabajo)
                    <div><strong>Trabajo:</strong> {{ $factura->trabajo->codigo_trabajo }} - {{ $factura->trabajo->titulo }}</div>
                    @endif
                    @if($factura->forma_pago)
                    <div><strong>Forma de Pago:</strong> {{ ucfirst($factura->forma_pago) }}</div>
                    @endif
                    <div><strong>Serie:</strong> {{ $factura->serie }}</div>
                    @if($factura->fecha_pago)
                    <div><strong>Fecha Pago:</strong> {{ $factura->fecha_pago->format('d/m/Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if($factura->concepto)
        <div class="concept-section">
            <div class="concept-text"><strong>Concepto:</strong> {{ $factura->concepto }}</div>
            @if($factura->observaciones)
            <div class="observations">{{ $factura->observaciones }}</div>
            @endif
        </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th class="text-center" style="width: 80px;">Cantidad</th>
                    <th class="text-right" style="width: 100px;">Precio Unit.</th>
                    <th class="text-right" style="width: 60px;">IVA</th>
                    <th class="text-right" style="width: 100px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @if($factura->lineas && count($factura->lineas) > 0)
                    @foreach($factura->lineas as $linea)
                    <tr>
                        <td>{{ $linea['descripcion'] }}</td>
                        <td class="text-center">{{ number_format($linea['cantidad'], 2) }}</td>
                        <td class="text-right">{{ number_format($linea['precio'], 2) }} €</td>
                        <td class="text-right">{{ $linea['iva'] ?? $factura->iva }}%</td>
                        <td class="text-right">{{ number_format($linea['total'], 2) }} €</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No hay líneas de factura</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="value">{{ number_format($factura->subtotal, 2) }} €</td>
                </tr>
                <tr>
                    <td class="label">IVA ({{ $factura->iva }}%):</td>
                    <td class="value">{{ number_format($factura->total - $factura->subtotal, 2) }} €</td>
                </tr>
                <tr class="grand-total">
                    <td class="label">TOTAL:</td>
                    <td class="value">{{ number_format($factura->total, 2) }} €</td>
                </tr>
            </table>
        </div>

        @if($clausulas && $clausulas->count() > 0)
        <div class="clauses">
            <div class="clauses-title">CLÁUSULAS LEGALES</div>
            @foreach($clausulas as $clausula)
            <div class="clause-item">
                <span class="clause-number">{{ $clausula->numero }}.</span>
                {{ $clausula->contenido }}
            </div>
            @endforeach
        </div>
        @endif

        <div class="footer">
            <p>Gracias por su confianza. Tapicería Odami - Excelencia en tapicería.</p>
            <p>{{ config('app.name', 'Tapicería Odami') }} | {{\App\Models\Configuracion::obtener('empresa_direccion', 'Calle Principal 123') }} | CIF: {{\App\Models\Configuracion::obtener('empresa_cif', 'B12345678') }}</p>
        </div>
    </div>
</body>
</html>
