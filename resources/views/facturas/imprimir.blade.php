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
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 0;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            border: 2px solid #1e40af;
            padding: 25px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
            margin-bottom: 20px;
        }

        .logo {
            text-align: left;
            flex: 1;
        }

        .logo h1 {
            font-size: 22px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .logo p {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }

        .factura-info {
            text-align: right;
            flex: 1;
        }

        .factura-info h2 {
            font-size: 26px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tipo-factura {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
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
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 5px;
            font-family: 'Courier New', monospace;
        }

        .fecha-emision {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        /* Información */
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-box {
            border: 1px solid #e5e7eb;
            padding: 12px;
            border-radius: 4px;
            background: #f9fafb;
        }

        .info-box h3 {
            font-size: 11px;
            color: #1f2937;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #3b82f6;
            font-weight: bold;
        }

        .info-box p {
            margin-bottom: 3px;
            font-size: 10px;
        }

        .info-box strong {
            color: #111827;
        }

        .info-row {
            display: flex;
            margin-bottom: 3px;
        }

        .info-label {
            font-weight: bold;
            min-width: 90px;
            color: #6b7280;
        }

        .info-value {
            color: #1f2937;
        }

        /* Tabla */
        .tabla-detalles {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
        }

        .tabla-detalles thead {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
        }

        .tabla-detalles th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #1e40af;
        }

        .tabla-detalles td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            border: 1px solid #e5e7eb;
        }

        .tabla-detalles tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Totales */
        .totales {
            width: 100%;
            max-width: 280px;
            margin-left: auto;
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
        }

        .totales .fila {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
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
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
            font-size: 14px;
            font-weight: bold;
            border: none;
        }

        /* Estado de Pago */
        .estado-pago-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .estado-pendiente { background: #fef3c7; color: #92400e; }
        .estado-pagado { background: #dcfce7; color: #166534; }
        .estado-parcial { background: #dbeafe; color: #1e40af; }
        .estado-anulado { background: #fee2e2; color: #991b1b; }

        /* Observaciones */
        .observaciones, .notas-legales {
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .observaciones {
            background: #fef3c7;
            border-color: #fcd34d;
        }

        .notas-legales {
            background: #f0f9ff;
            border-color: #7dd3fc;
            font-size: 9px;
        }

        .observaciones h3, .notas-legales h3 {
            font-size: 10px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .observaciones p, .notas-legales p {
            font-size: 10px;
            white-space: pre-wrap;
        }

        /* Áreas de Firma y Sello */
        .firmas-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        .firma-box, .sello-box {
            text-align: center;
            padding: 15px;
        }

        .firma-area, .sello-area {
            height: 100px;
            border: 1px dashed #9ca3af;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            position: relative;
        }

        .firma-area img, .sello-area img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .firma-placeholder, .sello-placeholder {
            color: #9ca3af;
            font-size: 9px;
            text-align: center;
        }

        .firma-info p, .sello-info p {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .firma-info strong {
            color: #1f2937;
        }

        .sello-humedo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.3;
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
            border: 3px solid #dc2626;
            border-radius: 50%;
            padding: 10px 20px;
            transform: translate(-50%, -50%) rotate(-15deg);
        }

        /* Footer */
        .footer {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }

        .footer-empresa {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 10px;
            text-align: left;
        }

        .footer-empresa h4 {
            font-size: 10px;
            color: #1f2937;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .footer-empresa p {
            font-size: 9px;
            margin-bottom: 2px;
        }

        /* Botones de acción (no se imprimen) */
        .action-buttons {
            text-align: center;
            margin: 15px 0;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-primary { background: #2563eb; color: white; }
        .btn-success { background: #16a34a; color: white; }
        .btn-secondary { background: #6b7280; color: white; }
        .btn:hover { opacity: 0.9; }

        @media print {
            .action-buttons { display: none; }
            body { padding: 0; }
            .container { border: 2px solid #1e40af; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>
    <!-- Botones de acción -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir Factura</button>
        <a href="{{ url('/api/facturas/' . $factura->id . '/pdf') }}" class="btn btn-success">📥 Descargar PDF</a>
        <button onclick="window.close()" class="btn btn-secondary">❌ Cerrar</button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <h1>🎨 TAPICERÍA ODAMI</h1>
                <p>Sistema de Gestión Profesional</p>
                <p><strong>RUC:</strong> {{ $factura->empresa_ruc ?? '20XXXXXXXXX' }}</p>
                <p><strong>{{ $factura->empresa_razon_social ?? 'TAPICERÍA ODAMI E.I.R.L.' }}</strong></p>
            </div>
            <div class="factura-info">
                <h2>FACTURA ELECTRÓNICA</h2>
                <span class="tipo-factura {{ $factura->tipo === 'original' ? 'tipo-original' : 'tipo-copia' }}">
                    {{ $factura->tipo === 'original' ? '📄 ORIGINAL' : '📋 COPIA' }}
                </span>
                <div class="numero-factura">{{ $factura->numero_factura }}</div>
                <p class="fecha-emision">
                    Fecha de Emisión: {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="info-section">
            <div class="info-box">
                <h3>👤 DATOS DEL CLIENTE</h3>
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">{{ $factura->cliente_nombre ?? ($factura->cliente?->nombre ?? 'N/A') }} {{ $factura->cliente_apellido ?? ($factura->cliente?->apellido ?? '') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Documento:</span>
                    <span class="info-value">{{ $factura->cliente_documento ?? ($factura->cliente?->documento ?? 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value">{{ $factura->cliente_direccion ?? ($factura->cliente?->direccion ?? 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value">{{ $factura->cliente_telefono ?? ($factura->cliente?->telefono ?? 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $factura->cliente_email ?? ($factura->cliente?->email ?? 'N/A') }}</span>
                </div>
            </div>

            <div class="info-box">
                <h3>🔧 DATOS DEL TRABAJO</h3>
                <div class="info-row">
                    <span class="info-label">Trabajo:</span>
                    <span class="info-value">{{ $factura->trabajo_tipo ?? ($factura->trabajo?->tipo_trabajo ?? 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Descripción:</span>
                    <span class="info-value">{{ $factura->trabajo_descripcion ?? ($factura->trabajo?->descripcion ?? 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">F. Recibido:</span>
                    <span class="info-value">{{ $factura->trabajo_fecha_recibido ? \Carbon\Carbon::parse($factura->trabajo_fecha_recibido)->format('d/m/Y') : ($factura->trabajo?->fecha_recibido ? \Carbon\Carbon::parse($factura->trabajo->fecha_recibido)->format('d/m/Y') : 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">F. Entrega:</span>
                    <span class="info-value">{{ $factura->trabajo_fecha_entrega ? \Carbon\Carbon::parse($factura->trabajo_fecha_entrega)->format('d/m/Y') : ($factura->trabajo?->fecha_entrega ? \Carbon\Carbon::parse($factura->trabajo->fecha_entrega)->format('d/m/Y') : 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado:</span>
                    <span class="info-value">{{ $factura->trabajo?->estado ? str_replace('_', ' ', ucfirst($factura->trabajo->estado)) : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Tabla de detalles -->
        <table class="tabla-detalles">
            <thead>
                <tr>
                    <th style="width: 50%;">Descripción del Servicio</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $factura->trabajo_tipo ?? ($factura->trabajo?->tipo_trabajo ?? 'Servicio de Tapicería') }}</strong>
                        <br>
                        <small style="color: #666;">{{ $factura->trabajo_descripcion ?? ($factura->trabajo?->descripcion ?? '') }}</small>
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
        <div style="margin-bottom: 15px;">
            <span class="estado-pago-badge estado-{{ $factura->estado_pago }}">
                📊 ESTADO: {{ strtoupper($factura->estado_pago) }}
            </span>
            @if($factura->metodo_pago)
                <span style="margin-left: 10px; font-size: 10px; color: #666;">
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

        <!-- Condiciones -->
        @if($factura->condiciones && $factura->condiciones->count() > 0)
        <div class="condiciones" style="border: 1px solid #e5e7eb; padding: 10px; border-radius: 4px; margin-bottom: 15px; background: #f9fafb;">
            <h3 style="font-size: 11px; margin-bottom: 8px; color: #1f2937; font-weight: bold; border-bottom: 2px solid #3b82f6; padding-bottom: 4px;">📋 Condiciones del Trabajo</h3>
            <ol style="margin: 0; padding-left: 20px; font-size: 10px; color: #374151;">
                @foreach($factura->condiciones as $index => $condicion)
                <li style="margin-bottom: 6px;">
                    <strong>{{ $condicion->titulo }}</strong>: {{ $condicion->descripcion }}
                </li>
                @endforeach
            </ol>
        </div>
        @endif

        <!-- Firmas y Sello -->
        <div class="firmas-section">
            <div class="firma-box">
                <div class="firma-area">
                    @if($factura->firma_base64)
                        <img src="{{ $factura->firma_base64 }}" alt="Firma">
                    @else
                        <div class="firma-placeholder">
                            <p>✍️ Espacio para Firma</p>
                            <p>(Firmar después de imprimir)</p>
                        </div>
                    @endif
                </div>
                <div class="firma-info">
                    <p><strong>{{ $factura->representante_nombre ?? 'Representante Legal' }}</strong></p>
                    <p>{{ $factura->representante_cargo ?? 'Gerente General' }}</p>
                    <p>DNI: {{ $factura->representante_dni ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="sello-box">
                <div class="sello-area">
                    @if($factura->sello_base64)
                        <img src="{{ $factura->sello_base64 }}" alt="Sello">
                    @else
                        <div class="sello-humedo">SELLO</div>
                        <div class="sello-placeholder">
                            <p>🔴 Espacio para Sello Húmedo</p>
                            <p>(Aplicar después de imprimir)</p>
                        </div>
                    @endif
                </div>
                <div class="sello-info">
                    <p><strong>{{ $factura->empresa_razon_social ?? 'TAPICERÍA ODAMI' }}</strong></p>
                    <p>RUC: {{ $factura->empresa_ruc ?? '20XXXXXXXXX' }}</p>
                </div>
            </div>
        </div>

        <!-- Notas Legales -->
        @if($factura->notas_legales)
        <div class="notas-legales">
            <h3>⚖️ Notas Legales</h3>
            <p>{{ $factura->notas_legales }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-empresa">
                <div>
                    <h4>📍 Dirección</h4>
                    <p>{{ $factura->empresa_direccion ?? 'Dirección de la empresa' }}</p>
                </div>
                <div>
                    <h4>📞 Contacto</h4>
                    <p>{{ $factura->empresa_telefono ?? 'Teléfono' }}</p>
                    <p>{{ $factura->empresa_email ?? 'Email' }}</p>
                </div>
                <div>
                    <h4>📄 Información</h4>
                    <p>Documento emitido electrónicamente</p>
                    <p>Gracias por su preferencia</p>
                </div>
            </div>
            <p style="border-top: 1px solid #e5e7eb; padding-top: 10px;">
                Esta factura es una representación impresa del documento electrónico {{ $factura->numero_factura }}. 
                Para verificar su autenticidad, contacte con la empresa emisora.
            </p>
        </div>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional, descomentar si se desea)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
