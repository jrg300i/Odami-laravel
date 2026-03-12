        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    // Configuración - Conexión automática con Cloudflare Tunnel
                    apiBaseUrl: window.location.origin,

                    // Autenticación
                    usuario: JSON.parse(localStorage.getItem('usuario')) || null,
                    token: localStorage.getItem('token') || null,
                    loginForm: { username: '', password: '' },
                    error: '',

                    // UI
                    cargando: false,
                    vistaActual: 'dashboard',
                    modalAbierto: null,
                    mostrarNotificaciones: false,
                    mostrarMenuUsuario: false,

                    // Datos
                    stats: {},
                    clientes: [],
                    trabajos: [],
                    facturas: [],
                    inventario: [],
                    categorias: [],
                    proveedores: [],
                    entregasHoy: [],
                    trabajosRecientes: [],
                    stockCritico: [],

                    // Filtros y búsquedas
                    busquedaCliente: {
                        nombre: '',
                        documento: '',
                        telefono: ''
                    },
                    // Búsqueda de trabajos recientes en dashboard
                    busquedaTrabajoReciente: {
                        cliente: '',
                        documento: '',
                        tipo: '',
                        fecha: ''
                    },
                    filtroEstado: '',
                    filtroCategoria: '',
                    estadosTrabajo: ['', 'pendiente', 'en_proceso', 'completado', 'entregado', 'cancelado'],
                    
                    // Búsqueda de facturas
                    busquedaFactura: {
                        cliente: '',
                        numero: '',
                        fecha_recibido: '',
                        fecha_entrega: '',
                        tipo: '',
                        estado_pago: ''
                    },

                    // Búsqueda de proveedores
                    busquedaProveedor: '',

                    // Dashboard - Clientes con trabajos
                    busquedaClienteDashboard: '',
                    clientesConTrabajos: [],
                    clientesConTrabajosFiltrados: [],

                    // Formularios
                    editingCliente: null,
                    clienteForm: { nombre: '', apellido: '', documento: '', telefono: '', email: '', direccion: '', activo: true },

                    editingTrabajo: null,
                    trabajoForm: { cliente_id: '', tipo_trabajo: '', descripcion: '', estado: 'pendiente', precio_estimado: 0, anticipo: 0 },

                    editingInventario: null,
                    inventarioForm: { nombre: '', categoria: 'telas', stock_actual: 0, stock_minimo: 5, precio_unitario: 0, proveedor: '' },

                    editingFactura: null,
                    facturaForm: {
                        numero_factura: '',
                        tipo: 'original',
                        trabajo_id: '',
                        // Datos editables del cliente
                        cliente_nombre: '',
                        cliente_apellido: '',
                        cliente_documento: '',
                        cliente_direccion: '',
                        cliente_telefono: '',
                        cliente_email: '',
                        // Montos
                        subtotal: 0,
                        igv: 0,
                        total: 0,
                        estado_pago: 'pendiente',
                        observaciones: '',
                        // Condiciones seleccionadas
                        condiciones_ids: []
                    },

                    // Condiciones disponibles
                    condiciones: [],

                    itemMovimiento: null,
                    movimientoForm: { item_id: '', tipo_movimiento: 'entrada', cantidad: 0, motivo: '' },

                    // Categorías
                    editingCategoria: null,
                    categoriaForm: { nombre: '', descripcion: '', color: 'bg-blue-500', icono: 'fa-box', activo: true, orden: 0 },

                    // Proveedores
                    editingProveedor: null,
                    proveedorForm: { nombre: '', ruc: '', telefono: '', email: '', direccion: '', contacto: '', telefono_contacto: '', notas: '', activo: true },

                    // Toasts
                    toasts: [],
                    toastId: 0,
                    
                    // Control de peticiones para evitar concurrencia
                    pendingRequests: {},

                    // Notificaciones
                    notificaciones: [],
                    mostrarNotificaciones: false,

                    // Nuevas variables para fotos y detalle de trabajo
                    trabajoDetalle: null,
                    clienteDetalle: null,
                    inventarioDetalle: null,
                    fotoTipoSeleccionado: 'recepcion',
                    fotoCapturada: null,
                    fotoEnGrande: null,
                    videoCamara: null,
                    canvasCamara: null,
                    streamCamara: null
                };
            },
            
            computed: {
                vistaTitulo() {
                    const titulos = {
                        dashboard: 'Dashboard',
                        clientes: 'Clientes',
                        trabajos: 'Trabajos',
                        inventario: 'Inventario',
                        categorias: 'Categorías',
                        proveedores: 'Proveedores',
                        facturas: 'Facturas'
                    };
                    return titulos[this.vistaActual] || 'Tapicería Odami';
                },
                
                menuItems() {
                    return [
                        { id: 'dashboard', nombre: 'Dashboard', icono: 'fa-home' },
                        { id: 'clientes', nombre: 'Clientes', icono: 'fa-users' },
                        { id: 'trabajos', nombre: 'Trabajos', icono: 'fa-briefcase' },
                        { id: 'inventario', nombre: 'Inventario', icono: 'fa-boxes' },
                        { id: 'categorias', nombre: 'Categorías', icono: 'fa-tags' },
                        { id: 'proveedores', nombre: 'Proveedores', icono: 'fa-truck' },
                        { id: 'facturas', nombre: 'Facturas', icono: 'fa-file-invoice-dollar' }
                    ];
                },

                clientesFiltrados() {
                    // La búsqueda se hace vía API, aquí solo retornamos los clientes cargados
                    return this.clientes;
                },

                trabajosFiltrados() {
                    if (!this.filtroEstado) return this.trabajos;
                    return this.trabajos.filter(t => t.estado === this.filtroEstado);
                },

                trabajosRecientesFiltrados() {
                    const busqueda = this.busquedaTrabajoReciente;
                    
                    // Si no hay filtros, retornar todos los trabajos recientes
                    if (!busqueda.cliente && !busqueda.documento && 
                        !busqueda.tipo && !busqueda.fecha) {
                        return this.trabajosRecientes;
                    }

                    return this.trabajosRecientes.filter(trabajo => {
                        // Filtro por cliente (nombre o apellido)
                        const clienteMatch = !busqueda.cliente || 
                            (trabajo.cliente?.nombre_completo && 
                             trabajo.cliente.nombre_completo.toLowerCase().includes(busqueda.cliente.toLowerCase()));
                        
                        // Filtro por cédula
                        const documentoMatch = !busqueda.documento || 
                            (trabajo.cliente?.documento && 
                             trabajo.cliente.documento.includes(busqueda.documento));
                        
                        // Filtro por tipo de trabajo
                        const tipoMatch = !busqueda.tipo || 
                            (trabajo.tipo_trabajo && 
                             trabajo.tipo_trabajo.toLowerCase().includes(busqueda.tipo.toLowerCase()));
                        
                        // Filtro por fecha
                        const fechaMatch = !busqueda.fecha || 
                            (trabajo.fecha_ingreso && 
                             trabajo.fecha_ingreso.startsWith(busqueda.fecha));
                        
                        return clienteMatch && documentoMatch && tipoMatch && fechaMatch;
                    });
                },

                clientesConTrabajosFiltrados() {
                    if (!this.busquedaClienteDashboard) {
                        return this.clientesConTrabajos;
                    }
                    const busqueda = this.busquedaClienteDashboard.toLowerCase();
                    return this.clientesConTrabajos.filter(cliente => 
                        cliente.nombre_completo.toLowerCase().includes(busqueda) ||
                        (cliente.documento && cliente.documento.includes(busqueda))
                    );
                },

                inventarioFiltrado() {
                    if (!this.filtroCategoria) return this.inventario;
                    return this.inventario.filter(i => i.categoria === this.filtroCategoria);
                },

                proveedoresFiltrados() {
                    if (!this.busquedaProveedor) return this.proveedores;
                    const busqueda = this.busquedaProveedor.toLowerCase();
                    return this.proveedores.filter(prov =>
                        prov.nombre.toLowerCase().includes(busqueda) ||
                        (prov.ruc && prov.ruc.includes(busqueda)) ||
                        (prov.contacto && prov.contacto.toLowerCase().includes(busqueda))
                    );
                },

                notificacionesCount() {
                    return this.notificaciones.filter(n => !n.leida).length;
                },

                facturasFiltradas() {
                    // Si no hay búsqueda, retornar todas las facturas
                    if (!this.busquedaFactura.cliente && 
                        !this.busquedaFactura.numero && 
                        !this.busquedaFactura.fecha_recibido && 
                        !this.busquedaFactura.fecha_entrega &&
                        !this.busquedaFactura.tipo &&
                        !this.busquedaFactura.estado_pago) {
                        return this.facturas;
                    }
                    
                    return this.facturas.filter(factura => {
                        const coincideCliente = !this.busquedaFactura.cliente || 
                            (factura.nombre_cliente && factura.nombre_cliente.toLowerCase().includes(this.busquedaFactura.cliente.toLowerCase()));
                        
                        const coincideNumero = !this.busquedaFactura.numero || 
                            (factura.numero_factura && factura.numero_factura.toLowerCase().includes(this.busquedaFactura.numero.toLowerCase()));
                        
                        const coincideFechaRecibido = !this.busquedaFactura.fecha_recibido || 
                            (factura.fecha_recibido && factura.fecha_recibido.startsWith(this.busquedaFactura.fecha_recibido));
                        
                        const coincideFechaEntrega = !this.busquedaFactura.fecha_entrega || 
                            (factura.fecha_entrega && factura.fecha_entrega.startsWith(this.busquedaFactura.fecha_entrega));
                        
                        const coincideTipo = !this.busquedaFactura.tipo || factura.tipo === this.busquedaFactura.tipo;
                        
                        const coincideEstado = !this.busquedaFactura.estado_pago || factura.estado_pago === this.busquedaFactura.estado_pago;
                        
                        return coincideCliente && coincideNumero && coincideFechaRecibido && coincideFechaEntrega && coincideTipo && coincideEstado;
                    });
                }
            },

            async mounted() {
                if (this.token) {
                    await this.cargarDatos();
                    // await this.cargarNotificaciones(); // Comentado - endpoint no implementado
                }

                // Cargar condiciones disponibles
                await this.cargarCondiciones();

                // Cerrar menú usuario al hacer clic fuera
                document.addEventListener('click', (e) => {
                    // Verificar si el click fue fuera del menú de usuario
                    if (this.$refs.menuUsuarioContainer) {
                        const clickedInside = this.$refs.menuUsuarioContainer.contains(e.target);
                        if (!clickedInside && this.mostrarMenuUsuario) {
                            this.mostrarMenuUsuario = false;
                        }
                    }

                    // Cerrar notificaciones al hacer clic fuera
                    const notifButton = e.target.closest('[title="Refrescar"]') || e.target.closest('.fa-bell');
                    const notifPanel = e.target.closest('.fixed.top-16.right-4.w-96');
                    if (!notifButton && !notifPanel && this.mostrarNotificaciones) {
                        this.mostrarNotificaciones = false;
                    }
                });

                // Cerrar modales y menús con tecla ESC
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' || e.key === 'Esc') {
                        // Cerrar menú de usuario
                        if (this.mostrarMenuUsuario) {
                            this.mostrarMenuUsuario = false;
                        }
                        // Cerrar notificaciones
                        if (this.mostrarNotificaciones) {
                            this.mostrarNotificaciones = false;
                        }
                        // Cerrar modal activo
                        if (this.modalAbierto) {
                            this.cerrarModal();
                        }
                    }
                });
            },

            methods: {
                toggleMenuUsuario() {
                    this.mostrarMenuUsuario = !this.mostrarMenuUsuario;
                },
                
                // Asegurar que el menú esté cerrado al cargar
                beforeMount() {
                    this.mostrarMenuUsuario = false;
                    this.modalAbierto = null;
                    this.mostrarNotificaciones = false;
                },
                // API Helper
                api() {
                    return axios.create({
                        baseURL: this.apiBaseUrl,
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                },
                
                // Autenticación
                async login() {
                    this.cargando = true;
                    this.error = '';
                    
                    const url = `${this.apiBaseUrl}/api/auth/login`;
                    console.log('=== INICIANDO LOGIN ===');
                    console.log('URL:', url);
                    console.log('Credenciales:', JSON.stringify(this.loginForm));
                    
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.loginForm)
                        });
                        
                        console.log('Status:', response.status);
                        console.log('Headers:', [...response.headers.entries()]);
                        
                        const data = await response.json();
                        console.log('Respuesta:', data);
                        
                        if (data.success) {
                            console.log('Login exitoso!');
                            this.usuario = data.data.usuario;
                            this.token = data.data.token;
                            localStorage.setItem('usuario', JSON.stringify(this.usuario));
                            localStorage.setItem('token', this.token);
                            
                            this.showToast('¡Bienvenido!', 'success');
                            await this.cargarDatos();
                        } else {
                            this.error = data.message || 'Error al iniciar sesión';
                            console.log('Error:', this.error);
                        }
                    } catch (error) {
                        console.error('Error completo:', error);
                        this.error = 'Error de conexión - Verifica que la API esté corriendo';
                    } finally {
                        this.cargando = false;
                    }
                },
                
                async logout() {
                    try {
                        // Llamar al endpoint de logout si hay token
                        if (this.token) {
                            await this.api().post('/api/auth/logout');
                        }
                    } catch (error) {
                        console.error('Error en logout API:', error);
                    } finally {
                        // Limpiar estado local siempre
                        localStorage.removeItem('usuario');
                        localStorage.removeItem('token');
                        this.usuario = null;
                        this.token = null;
                        this.mostrarMenuUsuario = false;
                        this.showToast('Sesión cerrada correctamente', 'success');
                    }
                },
                
                // Cargar datos
                async cargarDatos() {
                    this.cargando = true;
                    try {
                        await Promise.all([
                            this.cargarDashboard(),
                            this.cargarClientes(),
                            this.cargarTrabajos(),
                            this.cargarInventario(),
                            this.cargarCategorias(),
                            this.cargarProveedores(),
                            this.cargarFacturas()
                        ]);
                    } catch (error) {
                        console.error('Error cargando datos:', error);
                        this.showToast('Error cargando datos', 'error');
                    } finally {
                        this.cargando = false;
                    }
                },
                
                async cargarDashboard() {
                    const response = await this.api().get('/api/dashboard/stats');
                    this.stats = response.data.data;

                    const entregasRes = await this.api().get('/api/dashboard/entregas-hoy');
                    this.entregasHoy = entregasRes.data.data;

                    // Cargar clientes con sus trabajos
                    await this.cargarClientesConTrabajos();

                    const stockRes = await this.api().get('/api/dashboard/stock-critico');
                    this.stockCritico = stockRes.data.data;
                },
                
                async cargarClientesConTrabajos() {
                    try {
                        const response = await this.api().get('/api/clientes');
                        const clientes = response.data.data;
                        
                        // Para cada cliente, cargar sus trabajos con fotos y facturas
                        const clientesConTrabajos = await Promise.all(
                            clientes.map(async (cliente) => {
                                const trabajosRes = await this.api().get(`/api/clientes/${cliente.id}/trabajos`);
                                const trabajos = trabajosRes.data.data.trabajos || [];
                                
                                // Para cada trabajo, cargar fotos
                                const trabajosConFotos = await Promise.all(
                                    trabajos.map(async (trabajo) => {
                                        try {
                                            const fotosRes = await this.api().get(`/api/trabajos/${trabajo.id}/fotos`);
                                            trabajo.fotos = fotosRes.data.data?.fotos || [];
                                        } catch (error) {
                                            trabajo.fotos = [];
                                        }
                                        trabajo.facturas = trabajo.facturas || [];
                                        return trabajo;
                                    })
                                );
                                
                                // Agrupar trabajos por estado
                                const trabajosPorEstado = {};
                                trabajosConFotos.forEach(trabajo => {
                                    if (!trabajosPorEstado[trabajo.estado]) {
                                        trabajosPorEstado[trabajo.estado] = [];
                                    }
                                    trabajosPorEstado[trabajo.estado].push(trabajo);
                                });
                                
                                return {
                                    ...cliente,
                                    nombre_completo: `${cliente.nombre} ${cliente.apellido}`,
                                    trabajos_count: trabajosConFotos.length,
                                    trabajos_por_estado: trabajosPorEstado,
                                    expandido: false
                                };
                            })
                        );
                        
                        // Ordenar por cantidad de trabajos (desc)
                        this.clientesConTrabajos = clientesConTrabajos
                            .sort((a, b) => b.trabajos_count - a.trabajos_count)
                            .slice(0, 10); // Top 10 clientes
                        this.clientesConTrabajosFiltrados = this.clientesConTrabajos;
                    } catch (error) {
                        console.error('Error al cargar clientes con trabajos:', error);
                    }
                },
                
                toggleTrabajosCliente(clienteId) {
                    const cliente = this.clientesConTrabajos.find(c => c.id === clienteId);
                    if (cliente) {
                        cliente.expandido = !cliente.expandido;
                    }
                },
                
                filtrarClientesDashboard() {
                    // La filtración se hace automáticamente vía computed property
                },
                
                async cargarClientes() {
                    const params = new URLSearchParams();
                    if (this.busquedaCliente.nombre) params.append('busqueda', this.busquedaCliente.nombre);
                    if (this.busquedaCliente.documento) params.append('documento', this.busquedaCliente.documento);
                    
                    const url = params.toString() ? `/api/clientes?${params}` : '/api/clientes';
                    const response = await this.api().get(url);
                    this.clientes = response.data.data.map(c => ({
                        ...c,
                        nombre_completo: `${c.nombre} ${c.apellido}`
                    }));
                },

                async buscarClientes() {
                    await this.cargarClientes();
                },

                limpiarBusquedaClientes() {
                    this.busquedaCliente = {
                        nombre: '',
                        documento: '',
                        telefono: ''
                    };
                    this.cargarClientes();
                },

                filtrarTrabajosRecientes() {
                    // La filtración se hace automáticamente vía computed property
                    console.log('Filtrando trabajos recientes:', this.busquedaTrabajoReciente);
                },

                async cargarTrabajos() {
                    try {
                        const response = await this.api().get('/api/trabajos');
                        this.trabajos = response.data.data.map(trabajo => ({
                            ...trabajo,
                            facturas: [],
                            facturasCargadas: false,
                            materiales: [],
                            materialesCargados: false
                        }));
                    } catch (error) {
                        console.error('Error al cargar trabajos:', error);
                        this.trabajos = [];
                        this.showToast('Error al cargar trabajos. Intente recargar la página.', 'error');
                    }
                },
                
                async cargarFacturasDeTrabajo(trabajo) {
                    try {
                        const facturasRes = await this.api().get(`/api/facturas/trabajo/${trabajo.id}`);
                        trabajo.facturas = facturasRes.data.data || [];
                        trabajo.facturasCargadas = true;
                    } catch (error) {
                        console.error(`Error al cargar facturas del trabajo ${trabajo.id}:`, error);
                        this.showToast('Error al cargar facturas', 'error');
                    }
                },
                
                async cargarMaterialesDeTrabajo(trabajo) {
                    try {
                        const materialesRes = await this.api().get(`/api/trabajos/${trabajo.id}/materiales`);
                        trabajo.materiales = materialesRes.data.data || [];
                        trabajo.materialesCargados = true;
                    } catch (error) {
                        console.error(`Error al cargar materiales del trabajo ${trabajo.id}:`, error);
                        this.showToast('Error al cargar materiales', 'error');
                    }
                },
                
                async cargarInventario() {
                    const response = await this.api().get('/api/inventario');
                    this.inventario = response.data.data;
                },

                async cargarCategorias() {
                    try {
                        const response = await this.api().get('/api/categorias');
                        this.categorias = response.data.data || [];
                    } catch (error) {
                        console.error('Error al cargar categorías:', error);
                        this.categorias = [];
                    }
                },

                async cargarProveedores() {
                    try {
                        const response = await this.api().get('/api/proveedores');
                        this.proveedores = response.data.data || [];
                    } catch (error) {
                        console.error('Error al cargar proveedores:', error);
                        this.proveedores = [];
                    }
                },
                
                async cargarFacturas() {
                    try {
                        const response = await this.api().get('/api/facturas');
                        this.facturas = response.data.data || [];
                    } catch (error) {
                        console.error('Error al cargar facturas:', error);
                        if (error.response?.status === 401) {
                            this.showToast('Sesión expirada. Vuelva a iniciar sesión', 'error');
                            this.logout();
                        } else {
                            this.showToast('Error al cargar facturas', 'error');
                        }
                        this.facturas = [];
                    }
                },

                refrescarDatos() {
                    this.cargarDatos();
                    this.cargarNotificaciones();
                    this.showToast('Datos actualizados', 'success');
                },

                // Notificaciones
                async cargarNotificaciones() {
                    try {
                        const response = await this.api().get('/notificaciones');
                        this.notificaciones = response.data.data || [];
                    } catch (error) {
                        // Silencioso - el endpoint de notificaciones puede no estar implementado
                        console.log('Notificaciones: Endpoint no disponible (404)');
                        this.notificaciones = [];
                    }
                },

                async marcarLeida(id) {
                    try {
                        await this.api().post(`/notificaciones/${id}/leida`);
                        const notif = this.notificaciones.find(n => n.id === id);
                        if (notif) notif.leida = true;
                    } catch (error) {
                        console.error('Error marcando notificación como leída:', error);
                    }
                },

                async marcarTodasLeidas() {
                    try {
                        await this.api().post('/notificaciones/todas-leidas');
                        this.notificaciones.forEach(n => n.leida = true);
                        this.showToast('Todas las notificaciones marcadas como leídas', 'success');
                    } catch (error) {
                        console.error('Error marcando todas como leídas:', error);
                    }
                },

                formatearFechaRelativa(fecha) {
                    if (!fecha) return '';
                    const now = new Date();
                    const d = new Date(fecha);
                    const diffMs = now - d;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMins / 60);
                    const diffDays = Math.floor(diffHours / 24);

                    if (diffMins < 1) return 'ahora';
                    if (diffMins < 60) return `hace ${diffMins}m`;
                    if (diffHours < 24) return `hace ${diffHours}h`;
                    if (diffDays < 7) return `hace ${diffDays}d`;
                    return this.formatearFecha(fecha);
                },

                // Utilidades
                estadoClass(estado) {
                    const classes = {
                        pendiente: 'bg-yellow-100 text-yellow-700',
                        en_proceso: 'bg-blue-100 text-blue-700',
                        completado: 'bg-green-100 text-green-700',
                        entregado: 'bg-purple-100 text-purple-700',
                        cancelado: 'bg-red-100 text-red-700'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-700';
                },
                
                estadoTexto(estado) {
                    const textos = {
                        pendiente: 'Pendiente',
                        en_proceso: 'En Proceso',
                        completado: 'Completado',
                        entregado: 'Entregado',
                        cancelado: 'Cancelado'
                    };
                    return textos[estado] || estado;
                },
                
                estadoPagoClass(estado) {
                    const classes = {
                        pendiente: 'bg-yellow-100 text-yellow-700',
                        pagado: 'bg-green-100 text-green-700',
                        parcial: 'bg-blue-100 text-blue-700',
                        anulado: 'bg-red-100 text-red-700'
                    };
                    return classes[estado] || 'bg-gray-100 text-gray-700';
                },
                
                estadoPagoTexto(estado) {
                    const textos = {
                        pendiente: 'Pendiente',
                        pagado: 'Pagado',
                        parcial: 'Parcial',
                        anulado: 'Anulado'
                    };
                    return textos[estado] || estado;
                },
                
                formatearHora(fecha) {
                    if (!fecha) return '';
                    return new Date(fecha).toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' });
                },

                /**
                 * Verificar si el usuario actual es administrador
                 */
                esAdmin() {
                    return this.usuario && this.usuario.rol === 'admin';
                },

                abrirModal(tipo) {
                    // Solo resetear si NO es edición de trabajo
                    if (tipo !== 'trabajo' || !this.editingTrabajo) {
                        this.resetearFormularios();
                    }

                    // Si es modal de factura, cargar siguiente número
                    if (tipo === 'factura') {
                        this.obtenerSiguienteNumeroFactura().then(numero => {
                            this.facturaForm.numero_factura = numero;
                        });
                    }

                    this.modalAbierto = tipo;
                },

                cerrarModal() {
                    this.modalAbierto = null;
                    this.resetearFormularios();
                    this.detenerCamara();
                    this.fotoCapturada = null;
                    this.trabajoDetalle = null;
                    this.clienteDetalle = null;
                    this.inventarioDetalle = null;
                },
                
                // Cerrar modal al hacer click fuera
                clickFueraModal(event) {
                    if (event.target === event.currentTarget) {
                        this.cerrarModal();
                    }
                },
                
                resetearFormularios() {
                    this.editingCliente = null;
                    this.editingTrabajo = null;
                    this.editingInventario = null;
                    this.editingFactura = null;
                    this.itemMovimiento = null;
                    this.clienteForm = { nombre: '', apellido: '', documento: '', telefono: '', email: '', direccion: '', activo: true };
                    this.trabajoForm = { 
                        cliente_id: '', 
                        tipo_trabajo: '', 
                        descripcion: '', 
                        estado: 'pendiente', 
                        precio_estimado: 0, 
                        anticipo: 0,
                        fecha_recibido: '',
                        fecha_entrega: '',
                        notas: ''
                    };
                    this.inventarioForm = { nombre: '', categoria: 'telas', stock_actual: 0, stock_minimo: 5, precio_unitario: 0, proveedor: '' };
                    this.facturaForm = { 
                        numero_factura: '',
                        tipo: 'original',
                        trabajo_id: '',
                        cliente_nombre: '',
                        cliente_apellido: '',
                        cliente_documento: '',
                        cliente_direccion: '',
                        cliente_telefono: '',
                        cliente_email: '',
                        subtotal: 0,
                        igv: 0,
                        total: 0,
                        estado_pago: 'pendiente',
                        observaciones: '',
                        condiciones_ids: []
                    };
                    this.movimientoForm = { item_id: '', tipo_movimiento: 'entrada', cantidad: 0, motivo: '' };
                },
                
                // Clientes
                async verDetalleCliente(cliente) {
                    try {
                        this.cargando = true;
                        const response = await this.api().get(`/api/clientes/${cliente.id}`);
                        this.clienteDetalle = response.data.data;
                        this.modalAbierto = 'ver-cliente';
                        this.cargando = false;
                    } catch (error) {
                        console.error('Error al cargar detalle del cliente:', error);
                        this.showToast('Error al cargar el detalle del cliente', 'error');
                        this.cargando = false;
                    }
                },
                
                async verClientePorId(clienteId) {
                    if (!clienteId) {
                        this.showToast('Cliente no disponible', 'error');
                        return;
                    }
                    try {
                        this.cargando = true;
                        const response = await this.api().get(`/api/clientes/${clienteId}`);
                        this.clienteDetalle = response.data.data;
                        this.modalAbierto = 'ver-cliente';
                        this.cargando = false;
                    } catch (error) {
                        console.error('Error al cargar cliente:', error);
                        this.showToast('Error al cargar cliente', 'error');
                        this.cargando = false;
                    }
                },

                editarCliente(cliente) {
                    this.resetearFormularios();
                    this.editingCliente = cliente.id;
                    this.clienteForm = { ...cliente };
                    this.modalAbierto = 'cliente';
                },
                
                async guardarCliente() {
                    try {
                        if (this.editingCliente) {
                            await this.api().put(`/api/clientes/${this.editingCliente}`, this.clienteForm);
                            this.showToast('Cliente actualizado', 'success');
                        } else {
                            await this.api().post('/api/clientes', this.clienteForm);
                            this.showToast('Cliente creado', 'success');
                        }
                        this.cerrarModal();
                        await this.cargarClientes();
                    } catch (error) {
                        this.showToast('Error al guardar', 'error');
                    }
                },
                
                async eliminarCliente(id) {
                    if (!confirm('¿Eliminar cliente?')) return;
                    try {
                        await this.api().delete(`/api/clientes/${id}`);
                        this.showToast('Cliente eliminado', 'success');
                        await this.cargarClientes();
                    } catch (error) {
                        this.showToast('Error al eliminar', 'error');
                    }
                },
                
                // Trabajos
                async editarTrabajo(trabajo) {
                    try {
                        this.editingTrabajo = trabajo.id;
                        
                        // Cargar fotos del trabajo
                        let fotos = [];
                        try {
                            const fotosRes = await this.api().get(`/api/trabajos/${trabajo.id}/fotos`);
                            fotos = fotosRes.data.data?.fotos || [];
                        } catch (error) {
                            console.error('Error al cargar fotos:', error);
                        }
                        
                        this.trabajoForm = {
                            cliente_id: trabajo.cliente_id || trabajo.cliente?.id || '',
                            tipo_trabajo: trabajo.tipo_trabajo || '',
                            descripcion: trabajo.descripcion || '',
                            estado: trabajo.estado || 'pendiente',
                            precio_estimado: trabajo.precio_estimado || 0,
                            anticipo: trabajo.anticipo || 0,
                            fecha_recibido: trabajo.fecha_recibido ? this.formatearFechaInput(trabajo.fecha_recibido) : '',
                            fecha_entrega: trabajo.fecha_entrega ? this.formatearFechaInput(trabajo.fecha_entrega) : '',
                            notas: trabajo.notas || '',
                            fotos: fotos
                        };
                        
                        console.log('Editando trabajo ID:', this.editingTrabajo);
                        console.log('Trabajo form:', this.trabajoForm);
                        console.log('Es edición:', !!this.editingTrabajo);
                        this.abrirModal('trabajo');
                    } catch (error) {
                        console.error('Error al cargar trabajo para editar:', error);
                        this.showToast('Error al cargar trabajo', 'error');
                    }
                },

                formatearFechaInput(fecha) {
                    if (!fecha) return '';
                    const d = new Date(fecha);
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    return `${year}-${month}-${day}T${hours}:${minutes}`;
                },
                
                async guardarTrabajo() {
                    console.log('Guardando trabajo:', this.trabajoForm);
                    try {
                        if (this.editingTrabajo) {
                            await this.api().put(`/api/trabajos/${this.editingTrabajo}`, this.trabajoForm);
                            this.showToast('Trabajo actualizado', 'success');
                        } else {
                            await this.api().post('/api/trabajos', this.trabajoForm);
                            this.showToast('Trabajo creado', 'success');
                        }
                        this.cerrarModal();
                        await this.cargarTrabajos();
                    } catch (error) {
                        console.error('Error al guardar trabajo:', error);
                        this.showToast('Error al guardar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                async verTrabajo(trabajo) {
                    try {
                        this.cargando = true;
                        // Cargar detalle completo del trabajo
                        const response = await this.api().get(`/api/trabajos/${trabajo.id}`);
                        const trabajoData = response.data.data;

                        // Cargar fotos por etapa
                        const fotosResponse = await this.api().get(`/api/trabajos/${trabajo.id}/fotos`);
                        const fotosData = fotosResponse.data.data;

                        // Cargar facturas del trabajo
                        const facturasResponse = await this.api().get(`/api/facturas/trabajo/${trabajo.id}`);
                        const facturasData = facturasResponse.data.data;

                        this.trabajoDetalle = {
                            ...trabajoData,
                            fotos: fotosData.fotos_por_tipo || { recepcion: [], proceso: [], final: [] },
                            facturas: facturasData || []
                        };

                        this.modalAbierto = 'ver-trabajo';
                        this.cargando = false;
                    } catch (error) {
                        console.error('Error al cargar trabajo:', error);
                        this.showToast('Error al cargar el detalle del trabajo: ' + (error.response?.data?.message || error.message), 'error');
                        this.cargando = false;
                    }
                },

                async cargarFotosDeTrabajo(trabajoId) {
                    try {
                        const response = await this.api().get(`/api/trabajos/${trabajoId}/fotos`);
                        return response.data.data;
                    } catch (error) {
                        console.error('Error al cargar fotos:', error);
                        return null;
                    }
                },

                abrirCamaraParaTrabajo(tipo) {
                    this.fotoTipoSeleccionado = tipo;
                    this.modalAbierto = 'camara';
                    this.$nextTick(() => {
                        this.iniciarCamara();
                    });
                },

                async iniciarCamara() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment' },
                            audio: false
                        });
                        
                        this.videoCamara = this.$refs.videoCamara;
                        this.canvasCamara = this.$refs.canvasCamara;
                        
                        if (this.videoCamara) {
                            this.videoCamara.srcObject = stream;
                            this.streamCamara = stream;
                        }
                    } catch (error) {
                        console.error('Error al iniciar cámara:', error);
                        this.showToast('No se pudo acceder a la cámara', 'error');
                    }
                },

                tomarFoto() {
                    if (!this.videoCamara || !this.canvasCamara) return;
                    
                    const context = this.canvasCamara.getContext('2d');
                    this.canvasCamara.width = this.videoCamara.videoWidth;
                    this.canvasCamara.height = this.videoCamara.videoHeight;
                    context.drawImage(this.videoCamara, 0, 0);
                    
                    this.fotoCapturada = this.canvasCamara.toDataURL('image/jpeg', 0.8);
                },

                async guardarFotoCapturada() {
                    if (!this.fotoCapturada || !this.trabajoDetalle) return;
                    
                    try {
                        await this.api().post('/api/fotos', {
                            trabajo_id: this.trabajoDetalle.id,
                            tipo: this.fotoTipoSeleccionado,
                            foto_base64: this.fotoCapturada,
                            descripcion: `Foto de ${this.fotoTipoSeleccionado}`
                        });
                        
                        this.showToast('Foto guardada exitosamente', 'success');
                        this.fotoCapturada = null;
                        this.detenerCamara();
                        this.modalAbierto = null;
                        
                        // Recargar fotos del trabajo
                        await this.verTrabajo(this.trabajoDetalle);
                    } catch (error) {
                        console.error('Error al guardar foto:', error);
                        this.showToast('Error al guardar la foto', 'error');
                    }
                },

                detenerCamara() {
                    if (this.streamCamara) {
                        this.streamCamara.getTracks().forEach(track => track.stop());
                        this.streamCamara = null;
                    }
                },

                verFotoEnGrande(fotoBase64) {
                    this.fotoEnGrande = fotoBase64;
                    this.modalAbierto = 'ver-foto';
                },

                async eliminarFoto(fotoId, tipo) {
                    if (!confirm('¿Eliminar esta foto?')) return;
                    
                    try {
                        await this.api().delete(`/api/fotos/${fotoId}`);
                        this.showToast('Foto eliminada', 'success');
                        
                        // Recargar fotos del trabajo
                        await this.verTrabajo(this.trabajoDetalle);
                    } catch (error) {
                        console.error('Error al eliminar foto:', error);
                        this.showToast('Error al eliminar la foto', 'error');
                    }
                },

                editarTrabajoDesdeDetalle() {
                    if (this.trabajoDetalle) {
                        this.editarTrabajo(this.trabajoDetalle);
                    }
                },

                enviarWhatsAppDesdeDetalle() {
                    if (!this.trabajoDetalle) return;

                    const cliente = this.trabajoDetalle.cliente;
                    const telefono = cliente?.telefono || '';
                    
                    let mensaje = `👋 *Tapicería Odami* - Estado de tu Trabajo

📋 *Trabajo #${this.trabajoDetalle.id}*
🔧 Tipo: ${this.trabajoDetalle.tipo_trabajo}
📊 Estado: ${this.trabajoDetalle.estado.replace('_', ' ').toUpperCase()}

💰 *Precio:* S/ ${this.trabajoDetalle.precio_estimado || '0.00'}
💵 *Anticipo:* S/ ${this.trabajoDetalle.anticipo || '0.00'}
⏳ *Saldo:* S/ ${(this.trabajoDetalle.precio_estimado || 0) - (this.trabajoDetalle.anticipo || 0)}

📅 *Fecha Entrega:* ${this.formatearFecha(this.trabajoDetalle.fecha_entrega) || 'No definida'}`;

                    if (this.trabajoDetalle.notas) {
                        mensaje += `\n\n📝 *Notas:* ${this.trabajoDetalle.notas}`;
                    }

                    mensaje += `\n\n¡Gracias por tu confianza! 🎨`;

                    // Usar función que detecta móvil vs desktop
                    const url = this.obtenerWhatsAppUrl(telefono, mensaje);
                    window.open(url, '_blank');
                },

                formatearFecha(fecha) {
                    if (!fecha) return '';
                    const d = new Date(fecha);
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    return `${year}-${month}-${day} ${hours}:${minutes}`;
                },

                formatearMoneda(monto) {
                    if (monto === null || monto === undefined) return "0.00";
                    return Number(monto).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
                },
                
                // Inventario
                async verInventario(item) {
                    try {
                        const response = await this.api().get(`/api/inventario/${item.id}`);
                        this.inventarioDetalle = response.data.data;
                        this.modalAbierto = 'ver-inventario';
                    } catch (error) {
                        console.error('Error al ver inventario:', error);
                        this.showToast('Error al cargar el item', 'error');
                    }
                },

                editarInventario(item) {
                    this.editingInventario = item.id;
                    this.inventarioForm = { 
                        ...item,
                        stock_maximo: item.stock_maximo || null,
                        proveedor: item.proveedor || '',
                        contacto_proveedor: item.contacto_proveedor || '',
                        ubicacion: item.ubicacion || ''
                    };
                    this.abrirModal('inventario');
                },

                async guardarInventario() {
                    try {
                        if (this.editingInventario) {
                            await this.api().put(`/api/inventario/${this.editingInventario}`, this.inventarioForm);
                            this.showToast('Item actualizado', 'success');
                        } else {
                            await this.api().post('/api/inventario', this.inventarioForm);
                            this.showToast('Item creado', 'success');
                        }
                        this.cerrarModal();
                        await this.cargarInventario();
                    } catch (error) {
                        this.showToast('Error al guardar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                async eliminarInventario(id) {
                    if (!confirm('¿Eliminar este item del inventario? Esta acción no se puede deshacer.')) return;
                    try {
                        await this.api().delete(`/api/inventario/${id}`);
                        this.showToast('Item eliminado', 'success');
                        await this.cargarInventario();
                    } catch (error) {
                        if (error.response?.status === 401) {
                            this.showToast('Sesión expirada. Vuelva a iniciar sesión', 'error');
                            this.logout();
                        } else {
                            this.showToast('Error al eliminar: ' + (error.response?.data?.message || error.message), 'error');
                        }
                    }
                },

                registrarMovimiento(item) {
                    this.itemMovimiento = item;
                    this.movimientoForm = { item_id: item.id, tipo_movimiento: 'entrada', cantidad: 0, motivo: '' };
                    this.abrirModal('movimiento');
                },
                
                async guardarMovimiento() {
                    try {
                        await this.api().post('/api/inventario/movimientos', this.movimientoForm);
                        this.showToast('Movimiento registrado', 'success');
                        this.cerrarModal();
                        await this.cargarInventario();
                    } catch (error) {
                        this.showToast('Error al registrar movimiento', 'error');
                    }
                },

                // Categorías
                abrirModalCategoria(categoria = null) {
                    if (categoria) {
                        this.editingCategoria = categoria.id;
                        this.categoriaForm = { ...categoria };
                    } else {
                        this.editingCategoria = null;
                        this.categoriaForm = { nombre: '', descripcion: '', color: 'bg-blue-500', icono: 'fa-box', activo: true, orden: 0 };
                    }
                    this.abrirModal('categoria');
                },

                async guardarCategoria() {
                    try {
                        if (this.editingCategoria) {
                            await this.api().put(`/api/categorias/${this.editingCategoria}`, this.categoriaForm);
                            this.showToast('Categoría actualizada', 'success');
                        } else {
                            await this.api().post('/api/categorias', this.categoriaForm);
                            this.showToast('Categoría creada', 'success');
                        }
                        this.cerrarModal();
                        await this.cargarCategorias();
                    } catch (error) {
                        this.showToast('Error al guardar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                async eliminarCategoria(id) {
                    if (!confirm('¿Eliminar esta categoría? Esta acción no se puede deshacer.')) return;
                    try {
                        await this.api().delete(`/api/categorias/${id}`);
                        this.showToast('Categoría eliminada', 'success');
                        await this.cargarCategorias();
                    } catch (error) {
                        this.showToast('Error al eliminar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                // Proveedores
                abrirModalProveedor(proveedor = null) {
                    if (proveedor) {
                        this.editingProveedor = proveedor.id;
                        this.proveedorForm = { ...proveedor };
                    } else {
                        this.editingProveedor = null;
                        this.proveedorForm = { nombre: '', ruc: '', telefono: '', email: '', direccion: '', contacto: '', telefono_contacto: '', notas: '', activo: true };
                    }
                    this.abrirModal('proveedor');
                },

                async guardarProveedor() {
                    try {
                        if (this.editingProveedor) {
                            await this.api().put(`/api/proveedores/${this.editingProveedor}`, this.proveedorForm);
                            this.showToast('Proveedor actualizado', 'success');
                        } else {
                            await this.api().post('/api/proveedores', this.proveedorForm);
                            this.showToast('Proveedor creado', 'success');
                        }
                        this.cerrarModal();
                        await this.cargarProveedores();
                    } catch (error) {
                        this.showToast('Error al guardar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                async eliminarProveedor(id) {
                    if (!confirm('¿Eliminar este proveedor? Esta acción no se puede deshacer.')) return;
                    try {
                        await this.api().delete(`/api/proveedores/${id}`);
                        this.showToast('Proveedor eliminado', 'success');
                        await this.cargarProveedores();
                    } catch (error) {
                        this.showToast('Error al eliminar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                // Facturas
                async cargarFacturas() {
                    try {
                        const response = await this.api().get('/api/facturas');
                        this.facturas = response.data.data || [];
                    } catch (error) {
                        console.error('Error al cargar facturas:', error);
                        this.facturas = [];
                    }
                },

                buscarFacturas() {
                    // La búsqueda se realiza automáticamente mediante el computed facturasFiltradas
                    console.log('Buscando facturas...', this.busquedaFactura);
                },

                limpiarBusqueda() {
                    this.busquedaFactura = {
                        cliente: '',
                        numero: '',
                        fecha_recibido: '',
                        fecha_entrega: '',
                        tipo: '',
                        estado_pago: ''
                    };
                },

                async obtenerSiguienteNumeroFactura() {
                    try {
                        const response = await this.api().get('/api/facturas/siguiente-numero');
                        return response.data.data.siguiente_numero;
                    } catch (error) {
                        console.error('Error obteniendo siguiente número:', error);
                        return 'F001-00000001';
                    }
                },

                calcularTotal() {
                    const subtotal = this.facturaForm.subtotal || 0;
                    const igv = this.facturaForm.igv || 0;
                    this.facturaForm.total = subtotal + igv;
                },

                obtenerNombreCliente(trabajoId) {
                    if (!trabajoId) return '';
                    const trabajo = this.trabajos.find(t => t.id === trabajoId);
                    if (!trabajo) return 'Trabajo no encontrado';
                    // Intentar obtener de diferentes fuentes
                    if (trabajo.cliente?.nombre_completo) {
                        return trabajo.cliente.nombre_completo;
                    }
                    if (trabajo.cliente?.nombre && trabajo.cliente?.apellido) {
                        return `${trabajo.cliente.nombre} ${trabajo.cliente.apellido}`;
                    }
                    return 'Sin cliente';
                },

                obtenerClienteId(trabajoId) {
                    if (!trabajoId) return 'N/A';
                    const trabajo = this.trabajos.find(t => t.id === trabajoId);
                    if (!trabajo) return 'N/A';
                    // Intentar obtener de diferentes fuentes
                    return trabajo.cliente?.id || trabajo.cliente_id || 'N/A';
                },

                obtenerFechaRecibido(trabajoId) {
                    const trabajo = this.trabajos.find(t => t.id === trabajoId);
                    return trabajo?.fecha_recibido || '';
                },

                obtenerFechaEntrega(trabajoId) {
                    const trabajo = this.trabajos.find(t => t.id === trabajoId);
                    return trabajo?.fecha_entrega || '';
                },

                formatearFechaCorta(fecha) {
                    if (!fecha) return '';
                    const d = new Date(fecha);
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    const year = String(d.getFullYear()).slice(-2);
                    return `${day}/${month}/${year}`;
                },

                /**
                 * Limpiar número de teléfono para WhatsApp
                 * Elimina espacios, guiones, paréntesis y el símbolo +
                 */
                limpiarTelefono(telefono) {
                    if (!telefono) return '';
                    // Eliminar todo lo que no sea número
                    let limpio = telefono.replace(/\D/g, '');
                    // Si comienza con 0, quitarlo y agregar 51 (Perú)
                    if (limpio.startsWith('0')) {
                        limpio = '51' + limpio.substring(1);
                    }
                    // Si no tiene código de país y tiene 9 dígitos, agregar 51
                    if (limpio.length === 9) {
                        limpio = '51' + limpio;
                    }
                    return limpio;
                },
                
                /**
                 * Obtener enlace de WhatsApp (funciona en móvil y web)
                 * @param {string} telefono - Número de teléfono
                 * @param {string} mensaje - Mensaje opcional
                 * @returns {string} URL de WhatsApp
                 */
                obtenerWhatsAppUrl(telefono, mensaje = '') {
                    const telefonoLimpio = this.limpiarTelefono(telefono);
                    
                    // Detectar si es dispositivo móvil
                    const esMovil = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    
                    if (esMovil) {
                        // En móvil: usa wa.me que abre la app
                        return mensaje 
                            ? `https://wa.me/${telefonoLimpio}?text=${encodeURIComponent(mensaje)}`
                            : `https://wa.me/${telefonoLimpio}`;
                    } else {
                        // En desktop: usa WhatsApp Web
                        return mensaje
                            ? `https://web.whatsapp.com/send?phone=${telefonoLimpio}&text=${encodeURIComponent(mensaje)}`
                            : `https://web.whatsapp.com/send?phone=${telefonoLimpio}`;
                    }
                },

                /**
                 * Actualizar estado de un trabajo rápidamente
                 */
                async actualizarEstadoTrabajo(trabajoId, nuevoEstado) {
                    try {
                        const trabajo = this.trabajos.find(t => t.id === trabajoId);
                        if (!trabajo) return;

                        await this.api().put(`/api/trabajos/${trabajoId}`, {
                            ...trabajo,
                            estado: nuevoEstado
                        });

                        // Actualizar localmente
                        trabajo.estado = nuevoEstado;
                        this.showToast(`Estado actualizado a: ${this.estadoTexto(nuevoEstado)}`, 'success');

                        // Recargar trabajos para actualizar el orden
                        await this.cargarTrabajos();
                    } catch (error) {
                        console.error('Error al actualizar estado:', error);
                        this.showToast('Error al actualizar estado', 'error');
                    }
                },

                getEstadoFechaEntregaClass(fecha) {
                    if (!fecha) return 'bg-gray-100 text-gray-600';
                    const d = new Date(fecha);
                    const now = new Date();
                    const diffTime = d - now;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays < 0) return 'bg-red-100 text-red-700';
                    if (diffDays === 0) return 'bg-orange-100 text-orange-700';
                    if (diffDays <= 7) return 'bg-yellow-100 text-yellow-700';
                    return 'bg-green-100 text-green-700';
                },

                async verFactura(factura) {
                    const mensaje = `📄 *FACTURA ${factura.tipo === 'original' ? 'ORIGINAL' : 'COPIA'}*

*N° Factura:* ${factura.numero_factura}
*Cliente:* ${factura.nombre_cliente || 'N/A'} (ID: #${factura.cliente_id || 'N/A'})
*Trabajo:* ${factura.trabajo || 'N/A'}

*F. Recibido:* ${this.formatearFechaCorta(factura.fecha_recibido)}
*F. Entrega:* ${this.formatearFechaCorta(factura.fecha_entrega)}

*Subtotal:* S/ ${factura.subtotal}
*IGV:* S/ ${factura.igv}
*TOTAL:* S/ ${factura.total}

*Estado:* ${factura.estado_pago.toUpperCase()}`;

                    const url = `https://web.whatsapp.com/send?text=${encodeURIComponent(mensaje)}`;
                    window.open(url, '_blank');
                },

                imprimirFactura(factura) {
                    // Abrir vista de impresión en nueva ventana
                    const url = `${this.apiBaseUrl}/api/facturas/${factura.id}/imprimir`;
                    window.open(url, '_blank', 'width=900,height=700');
                },

                editarFactura(factura) {
                    this.editingFactura = factura.id;
                    this.facturaForm = {
                        ...factura,
                        numero_factura: factura.numero_factura,
                        tipo: factura.tipo || 'original',
                        observaciones: factura.observaciones || '',
                        condiciones_ids: factura.condiciones?.map(c => c.id) || []
                    };
                    this.abrirModal('factura');
                },

                async eliminarFactura(id) {
                    if (!confirm('¿Eliminar factura? Esta acción no se puede deshacer.')) return;
                    try {
                        await this.api().delete(`/api/facturas/${id}`);
                        this.showToast('Factura eliminada', 'success');
                        await this.cargarFacturas();
                    } catch (error) {
                        this.showToast('Error al eliminar', 'error');
                    }
                },

                // Condiciones
                async cargarCondiciones() {
                    try {
                        const response = await this.api().get('/api/condiciones');
                        this.condiciones = response.data.data;
                    } catch (error) {
                        console.error('Error cargando condiciones:', error);
                    }
                },

                async guardarFactura() {
                    try {
                        // Si hay un trabajo seleccionado, obtener datos del cliente
                        if (this.facturaForm.trabajo_id) {
                            const trabajo = this.trabajos.find(t => t.id === this.facturaForm.trabajo_id);
                            if (trabajo && trabajo.cliente) {
                                this.facturaForm.cliente_nombre = trabajo.cliente.nombre || '';
                                this.facturaForm.cliente_apellido = trabajo.cliente.apellido || '';
                                this.facturaForm.cliente_documento = trabajo.cliente.documento || '';
                                this.facturaForm.cliente_direccion = trabajo.cliente.direccion || '';
                                this.facturaForm.cliente_telefono = trabajo.cliente.telefono || '';
                                this.facturaForm.cliente_email = trabajo.cliente.email || '';
                            } else if (trabajo && trabajo.cliente_id) {
                                // Si no hay objeto cliente completo, buscar en clientes
                                const cliente = this.clientes.find(c => c.id === trabajo.cliente_id);
                                if (cliente) {
                                    this.facturaForm.cliente_nombre = cliente.nombre || '';
                                    this.facturaForm.cliente_apellido = cliente.apellido || '';
                                    this.facturaForm.cliente_documento = cliente.documento || '';
                                    this.facturaForm.cliente_direccion = cliente.direccion || '';
                                    this.facturaForm.cliente_telefono = cliente.telefono || '';
                                    this.facturaForm.cliente_email = cliente.email || '';
                                }
                            }
                        }

                        if (this.editingFactura) {
                            await this.api().put(`/api/facturas/${this.editingFactura}`, {
                                ...this.facturaForm,
                                condiciones: this.facturaForm.condiciones_ids
                            });
                            this.showToast('Factura actualizada', 'success');
                        } else {
                            // Obtener siguiente número si no está definido
                            if (!this.facturaForm.numero_factura) {
                                this.facturaForm.numero_factura = await this.obtenerSiguienteNumeroFactura();
                            }
                            await this.api().post('/api/facturas', {
                                ...this.facturaForm,
                                condiciones: this.facturaForm.condiciones_ids
                            });
                            this.showToast('Factura creada', 'success');
                        }
                        this.cerrarModal();
                        await this.cargarFacturas();
                    } catch (error) {
                        console.error('Error al guardar factura:', error);
                        this.showToast('Error al guardar: ' + (error.response?.data?.message || error.message), 'error');
                    }
                },

                // Toast
                showToast(message, type = 'info') {
                    const id = ++this.toastId;
                    this.toasts.push({ id, message, type });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 3000);
                }
            }
        }).mount('#app');
