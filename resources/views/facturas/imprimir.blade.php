<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $factura->numero_factura }} - {{ $factura->tipo === 'original' ? 'ORIGINAL' : 'COPIA' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            margin-bottom: 20px;
        }

        .logo {
            text-align: left;
        }

        .logo h1 {
            font-size: 24px;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo p {
            font-size: 11px;
            color: #666;
        }

        .factura-info {
            text-align: right;
        }

        .factura-info h2 {
            font-size: 28px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tipo-factura {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            margin-top: 5px;
        }

        .tipo-original {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #166534;
        }

        .tipo-copia {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #374151;
        }

        .numero-factura {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 8px;
            font-family: 'Courier New', monospace;
        }

        /* Información */
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-box {
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            background: #f9fafb;
        }

        .info-box h3 {
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #3b82f6;
        }

        .info-box p {
            margin-bottom: 5px;
            font-size: 11px;
        }

        .info-box strong {
            color: #111827;
        }

        /* Tabla */
        .tabla-detalles {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .tabla-detalles thead {
            background: #2563eb;
            color: white;
        }

        .tabla-detalles th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }

        .tabla-detalles td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .tabla-detalles tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totales */
        .totales {
            width: 100%;
            max-width: 300px;
            margin-left: auto;
            margin-bottom: 20px;
        }

        .totales .fila {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .totales .fila.subtotal {
            background: #f3f4f6;
            font-weight: bold;
        }

        .totales .fila.igv {
            background: #f3f4f6;
        }

        .totales .fila.total {
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
        }

        /* Observaciones */
        .observaciones {
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            background: #fef3c7;
            margin-bottom: 20px;
        }

        .observaciones h3 {
            font-size: 12px;
            color: #92400e;
            margin-bottom: 8px;
        }

        .observaciones p {
            font-size: 11px;
            color: #78350f;
            white-space: pre-wrap;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
        }

        .footer-box {
            text-align: center;
        }

        .footer-box p {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }

        .footer-box strong {
            font-size: 11px;
            color: #1f2937;
        }

        .firma {
            margin-top: 30px;
            border-top: 1px solid #9ca3af;
            padding-top: 5px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }

        /* Estado de pago */
        .estado-pago {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .estado-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .estado-pagado {
            background: #dcfce7;
            color: #166534;
        }

        .estado-parcial {
            background: #dbeafe;
            color: #1e40af;
        }

        .estado-anulado {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Botones de acción (no se imprimen) */
        .action-buttons {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media print {
            .action-buttons {
                display: none;
            }

            body {
                padding: 0;
            }

            .container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <!-- Botones de acción -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Imprimir Factura
        </button>
        <a href="{{ url('/api/facturas/' . $factura->id . '/pdf') }}" class="btn btn-success">
            📥 Descargar PDF
        </a>
        <button onclick="window.close()" class="btn btn-secondary">
            ❌ Cerrar
        </button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <h1>🎨 Tapicería Odami</h1>
                <p>Sistema de Gestión Profesional</p>
                <p>RUC: 20XXXXXXXXX</p>
            </div>
            <div class="factura-info">
                <h2>FACTURA</h2>
                <span class="tipo-factura {{ $factura->tipo === 'original' ? 'tipo-original' : 'tipo-copia' }}">
                    {{ $factura->tipo === 'original' ? '📄 ORIGINAL' : '📋 COPIA' }}
                </span>
                <div class="numero-factura">{{ $factura->numero_factura }}</div>
                <p style="margin-top: 5px; font-size: 10px; color: #666;">
                    Fecha de Emisión: {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Información -->
        <div class="info-section">
            <div class="info-box">
                <h3>👤 Información del Cliente</h3>
                <p><strong>Nombre:</strong> {{ $cliente->nombre_completo ?? 'N/A' }}</p>
                <p><strong>ID Cliente:</strong> #{{ $cliente->id ?? 'N/A' }}</p>
                <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $cliente->email ?? 'N/A' }}</p>
                <p><strong>Dirección:</strong> {{ $cliente->direccion ?? 'N/A' }}</p>
            </div>

            <div class="info-box">
                <h3>🔧 Información del Trabajo</h3>
                <p><strong>Trabajo:</strong> {{ $trabajo->tipo_trabajo ?? 'N/A' }}</p>
                <p><strong>Descripción:</strong> {{ $trabajo->descripcion ?? 'N/A' }}</p>
                <p><strong>F. Recibido:</strong> {{ $trabajo->fecha_recibido ? \Carbon\Carbon::parse($trabajo->fecha_recibido)->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>F. Entrega:</strong> {{ $trabajo->fecha_entrega ? \Carbon\Carbon::parse($trabajo->fecha_entrega)->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>Estado:</strong> {{ str_replace('_', ' ', ucfirst($trabajo->estado ?? 'N/A')) }}</p>
            </div>
        </div>

        <!-- Tabla de detalles -->
        <table class="tabla-detalles">
            <thead>
                <tr>
                    <th style="width: 50%;">Descripción</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $trabajo->tipo_trabajo ?? 'Servicio de Tapicería' }}</strong>
                        <br>
                        <small style="color: #666;">{{ $trabajo->descripcion ?? '' }}</small>
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right">S/ {{ number_format($factura->subtotal, 2) }}</td>
                    <td class="text-right">S/ {{ number_format($factura->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totales">
            <div class="fila subtotal">
                <span>Subtotal:</span>
                <span>S/ {{ number_format($factura->subtotal, 2) }}</span>
            </div>
            <div class="fila igv">
                <span>IGV (18%):</span>
                <span>S/ {{ number_format($factura->igv, 2) }}</span>
            </div>
            <div class="fila total">
                <span>TOTAL:</span>
                <span>S/ {{ number_format($factura->total, 2) }}</span>
            </div>
        </div>

        <!-- Estado de Pago -->
        <div style="margin-bottom: 20px;">
            <span class="estado-pago estado-{{ $factura->estado_pago }}">
                📊 ESTADO: {{ strtoupper($factura->estado_pago) }}
            </span>
            @if($factura->metodo_pago)
                <span style="margin-left: 10px; font-size: 11px; color: #666;">
                    | Método de Pago: {{ $factura->metodo_pago }}
                </span>
            @endif
        </div>

        <!-- Observaciones -->
        @if($factura->observaciones)
        <div class="observaciones">
            <h3>📝 Observaciones</h3>
            <p>{{ $factura->observaciones }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-box">
                <p><strong> Emitido por:</strong></p>
                <p>{{ $emisor->nombre ?? 'Administrador' }}</p>
            </div>
            <div class="footer-box">
                <p><strong> Cliente:</strong></p>
                <p>{{ $cliente->nombre_completo ?? 'N/A' }}</p>
            </div>
            <div class="footer-box">
                <p><strong> Fecha:</strong></p>
                <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Firma -->
        <div class="firma">
            <p>Este documento es una representación impresa de la factura electrónica {{ $factura->numero_factura }}.</p>
            <p>Gracias por su preferencia - Tapicería Odami</p>
        </div>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
