// Aplicación Principal Vue.js - Tapicería Odami
const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            // Estado de autenticación
            usuario: null,
            token: null,
            loginForm: { username: '', password: '' },
            error: '',
            cargando: false,

            // Navegación
            vistaActual: 'dashboard',
            menuItems: [
                { id: 'dashboard', nombre: 'Dashboard', icono: 'fa-home' },
                { id: 'clientes', nombre: 'Clientes', icono: 'fa-users' },
                { id: 'trabajos', nombre: 'Trabajos', icono: 'fa-briefcase' },
                { id: 'inventario', nombre: 'Inventario', icono: 'fa-boxes' },
                { id: 'categorias', nombre: 'Categorías', icono: 'fa-tags' },
                { id: 'proveedores', nombre: 'Proveedores', icono: 'fa-truck' },
                { id: 'facturas', nombre: 'Facturas', icono: 'fa-file-invoice-dollar' }
            ],

            // Datos
            stats: {},
            clientes: [],
            trabajos: [],
            inventario: [],
            categorias: [],
            proveedores: [],
            facturas: [],

            // Filtros
            filtroEstado: '',
            filtroCategoria: '',
            filtroProveedor: '',
            busquedaProveedor: '',

            // Modales
            modalAbierto: null,
            mostrarMenuUsuario: false,
            mostrarNotificaciones: false,
            notificaciones: [],

            // Debug
            debugLog: [],

            // Formularios
            editingCliente: null,
            editingTrabajo: null,
            editingInventario: null,
            editingCategoria: null,
            editingProveedor: null,
            editingFactura: null,
            itemMovimiento: null,

            clienteForm: {},
            trabajoForm: {},
            inventarioForm: {},
            categoriaForm: {},
            proveedorForm: {},
            facturaForm: {},
            movimientoForm: {},

            // Detalles
            trabajoDetalle: null,
            clienteDetalle: null,
            inventarioDetalle: null,
            fotoEnGrande: null,
            trabajoIdParaFoto: null
        };
    },

    computed: {
        notificacionesCount() {
            return this.notificaciones.filter(n => !n.leida).length;
        },

        categoriasFiltradas() {
            if (!this.filtroCategoria) return this.categorias;
            return this.categorias.filter(cat => {
                if (this.filtroCategoria === 'activo') return cat.activo === true;
                if (this.filtroCategoria === 'inactivo') return cat.activo === false;
                return true;
            });
        },

        proveedoresFiltrados() {
            let resultados = this.proveedores;
            if (this.busquedaProveedor) {
                const busqueda = this.busquedaProveedor.toLowerCase();
                resultados = resultados.filter(prov =>
                    prov.nombre.toLowerCase().includes(busqueda) ||
                    (prov.ruc && prov.ruc.includes(busqueda))
                );
            }
            if (this.filtroProveedor) {
                if (this.filtroProveedor === 'activo') {
                    resultados = resultados.filter(prov => prov.activo === true);
                } else if (this.filtroProveedor === 'inactivo') {
                    resultados = resultados.filter(prov => prov.activo === false);
                }
            }
            return resultados;
        },

        trabajosFiltrados() {
            if (!this.filtroEstado) return this.trabajos;
            return this.trabajos.filter(t => t.estado === this.filtroEstado);
        }
    },

    async mounted() {
        try {
            const baseUrl = window.location.origin;
            
            if (typeof ApiService !== 'undefined') {
                ApiService.init(baseUrl);
            }
        } catch (error) {
            console.error('Error en mounted:', error);
        }
    },

    methods: {
        // Autenticación
        async realizarLogin(credentials) {
            try {
                this.cargando = true;
                this.error = '';

                // Verificar ApiService
                if (typeof ApiService === 'undefined' || !ApiService.baseUrl) {
                    this.error = 'Error: API no disponible';
                    this.cargando = false;
                    return;
                }

                const url = ApiService.baseUrl + '/api/auth/login';
                
                const response = await axios.post(url, {
                    username: credentials.username,
                    password: credentials.password
                });

                if (response.data && response.data.token) {
                    this.token = response.data.token;
                    this.usuario = response.data.usuario;

                    localStorage.setItem('tapiceria_token', this.token);
                    localStorage.setItem('tapiceria_usuario', JSON.stringify(this.usuario));

                    ApiService.setToken(this.token);
                    await this.cargarDatos();
                } else {
                    this.error = 'Respuesta inválida del servidor';
                }
            } catch (error) {
                console.error('Login error:', error);
                this.error = error.response?.data?.message || error.message || 'Error al iniciar sesión';
            } finally {
                this.cargando = false;
            }
        },

        logout() {
            localStorage.removeItem('tapiceria_token');
            localStorage.removeItem('tapiceria_usuario');
            ApiService.clearToken();
            this.usuario = null;
            this.token = null;
        },

        // Navegación
        navegar(vista) {
            this.vistaActual = vista;
        },

        // Carga de datos
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
            } finally {
                this.cargando = false;
            }
        },

        async cargarDashboard() {
            const response = await ApiService.getInstance().get('/api/dashboard/stats');
            this.stats = response.data.data;
        },

        async cargarClientes() {
            const response = await ApiService.getInstance().get('/api/clientes');
            this.clientes = response.data.data;
        },

        async cargarTrabajos() {
            const response = await ApiService.getInstance().get('/api/trabajos');
            this.trabajos = response.data.data;
        },

        async cargarInventario() {
            const response = await ApiService.getInstance().get('/api/inventario');
            this.inventario = response.data.data;
        },

        async cargarCategorias() {
            const response = await ApiService.getInstance().get('/api/categorias');
            this.categorias = response.data.data;
        },

        async cargarProveedores() {
            const response = await ApiService.getInstance().get('/api/proveedores');
            this.proveedores = response.data.data;
        },

        async cargarFacturas() {
            const response = await ApiService.getInstance().get('/api/facturas');
            this.facturas = response.data.data;
        },

        refrescarDatos() {
            this.cargarDatos();
        },

        // Menú usuario
        abrirMenuUsuario() {
            this.mostrarMenuUsuario = true;
        },

        cerrarMenuUsuario() {
            this.mostrarMenuUsuario = false;
        },

        toggleNotificaciones() {
            this.mostrarNotificaciones = !this.mostrarNotificaciones;
        },

        // Filtros
        cambiarFiltroEstado(estado) {
            this.filtroEstado = estado;
        },

        cambiarFiltroCategoria(categoria) {
            this.filtroCategoria = categoria;
        },

        // Modales
        abrirModalCliente(cliente = null) {
            if (cliente) {
                this.editingCliente = cliente;
                this.clienteForm = { ...cliente };
            } else {
                this.editingCliente = null;
                this.clienteForm = { nombre: '', apellido: '', documento: '', telefono: '', email: '', direccion: '', activo: true };
            }
            this.modalAbierto = 'cliente';
        },

        abrirModalTrabajo(trabajo = null) {
            if (trabajo) {
                this.editingTrabajo = trabajo;
                this.trabajoForm = { ...trabajo };
            } else {
                this.editingTrabajo = null;
                this.trabajoForm = { cliente_id: '', tipo_trabajo: '', descripcion: '', estado: 'pendiente', precio_estimado: 0, anticipo: 0 };
            }
            this.modalAbierto = 'trabajo';
        },

        abrirModalInventario(item = null) {
            if (item) {
                this.editingInventario = item;
                this.inventarioForm = { ...item };
            } else {
                this.editingInventario = null;
                this.inventarioForm = { nombre: '', categoria: 'telas', stock_actual: 0, stock_minimo: 5, precio_unitario: 0 };
            }
            this.modalAbierto = 'inventario';
        },

        abrirModalCategoria(categoria = null) {
            if (categoria) {
                this.editingCategoria = categoria;
                this.categoriaForm = { ...categoria };
            } else {
                this.editingCategoria = null;
                this.categoriaForm = { nombre: '', descripcion: '', color: 'bg-blue-500', icono: 'fa-box', activo: true, orden: 0 };
            }
            this.modalAbierto = 'categoria';
        },

        abrirModalProveedor(proveedor = null) {
            if (proveedor) {
                this.editingProveedor = proveedor;
                this.proveedorForm = { ...proveedor };
            } else {
                this.editingProveedor = null;
                this.proveedorForm = { nombre: '', ruc: '', telefono: '', email: '', direccion: '', contacto: '', telefono_contacto: '', notas: '', activo: true };
            }
            this.modalAbierto = 'proveedor';
        },

        abrirModalFactura(factura = null) {
            if (factura) {
                this.editingFactura = factura;
                this.facturaForm = { ...factura };
            } else {
                this.editingFactura = null;
                this.facturaForm = { numero_factura: '', tipo: 'original', trabajo_id: '', subtotal: 0, igv: 0, total: 0, estado_pago: 'pendiente' };
            }
            this.modalAbierto = 'factura';
        },

        registrarMovimiento(item) {
            this.itemMovimiento = item;
            this.movimientoForm = { item_id: item.id, tipo_movimiento: 'entrada', cantidad: 0, motivo: '' };
            this.modalAbierto = 'movimiento';
        },

        cerrarModal() {
            this.modalAbierto = null;
            this.resetearFormularios();
        },

        resetearFormularios() {
            this.editingCliente = null;
            this.editingTrabajo = null;
            this.editingInventario = null;
            this.editingCategoria = null;
            this.editingProveedor = null;
            this.editingFactura = null;
            this.itemMovimiento = null;
        },

        // Acciones CRUD
        async guardarCliente(data) {
            try {
                if (this.editingCliente) {
                    await ApiService.getInstance().put(`/api/clientes/${this.editingCliente.id}`, data);
                } else {
                    await ApiService.getInstance().post('/api/clientes', data);
                }
                this.cerrarModal();
                await this.cargarClientes();
            } catch (error) {
                console.error('Error al guardar cliente:', error);
            }
        },

        async guardarTrabajo(data) {
            try {
                if (this.editingTrabajo) {
                    await ApiService.getInstance().put(`/api/trabajos/${this.editingTrabajo.id}`, data);
                } else {
                    await ApiService.getInstance().post('/api/trabajos', data);
                }
                this.cerrarModal();
                await this.cargarTrabajos();
            } catch (error) {
                console.error('Error al guardar trabajo:', error);
            }
        },

        async guardarInventario(data) {
            try {
                if (this.editingInventario) {
                    await ApiService.getInstance().put(`/api/inventario/${this.editingInventario.id}`, data);
                } else {
                    await ApiService.getInstance().post('/api/inventario', data);
                }
                this.cerrarModal();
                await this.cargarInventario();
            } catch (error) {
                console.error('Error al guardar inventario:', error);
            }
        },

        async guardarCategoria(data) {
            try {
                if (this.editingCategoria) {
                    await ApiService.getInstance().put(`/api/categorias/${this.editingCategoria.id}`, data);
                } else {
                    await ApiService.getInstance().post('/api/categorias', data);
                }
                this.cerrarModal();
                await this.cargarCategorias();
            } catch (error) {
                console.error('Error al guardar categoría:', error);
            }
        },

        async guardarProveedor(data) {
            try {
                if (this.editingProveedor) {
                    await ApiService.getInstance().put(`/api/proveedores/${this.editingProveedor.id}`, data);
                } else {
                    await ApiService.getInstance().post('/api/proveedores', data);
                }
                this.cerrarModal();
                await this.cargarProveedores();
            } catch (error) {
                console.error('Error al guardar proveedor:', error);
            }
        },

        async guardarFactura(data) {
            try {
                if (this.editingFactura) {
                    await ApiService.getInstance().put(`/api/facturas/${this.editingFactura.id}`, data);
                } else {
                    await ApiService.getInstance().post('/api/facturas', data);
                }
                this.cerrarModal();
                await this.cargarFacturas();
            } catch (error) {
                console.error('Error al guardar factura:', error);
            }
        },

        async guardarMovimiento(data) {
            try {
                await ApiService.getInstance().post('/api/inventario/movimientos', data);
                this.cerrarModal();
                await this.cargarInventario();
            } catch (error) {
                console.error('Error al registrar movimiento:', error);
            }
        },

        async eliminarCliente(id) {
            if (!confirm('¿Eliminar este cliente?')) return;
            try {
                await ApiService.getInstance().delete(`/api/clientes/${id}`);
                await this.cargarClientes();
            } catch (error) {
                console.error('Error al eliminar cliente:', error);
            }
        },

        async eliminarTrabajo(id) {
            if (!confirm('¿Eliminar este trabajo?')) return;
            try {
                await ApiService.getInstance().delete(`/api/trabajos/${id}`);
                await this.cargarTrabajos();
            } catch (error) {
                console.error('Error al eliminar trabajo:', error);
            }
        },

        async eliminarInventario(id) {
            if (!confirm('¿Eliminar este item? Solo administradores.')) return;
            try {
                await ApiService.getInstance().delete(`/api/inventario/${id}`);
                await this.cargarInventario();
            } catch (error) {
                console.error('Error al eliminar inventario:', error);
            }
        },

        async eliminarCategoria(id) {
            if (!confirm('¿Eliminar esta categoría?')) return;
            try {
                await ApiService.getInstance().delete(`/api/categorias/${id}`);
                await this.cargarCategorias();
            } catch (error) {
                console.error('Error al eliminar categoría:', error);
            }
        },

        async eliminarProveedor(id) {
            if (!confirm('¿Eliminar este proveedor?')) return;
            try {
                await ApiService.getInstance().delete(`/api/proveedores/${id}`);
                await this.cargarProveedores();
            } catch (error) {
                console.error('Error al eliminar proveedor:', error);
            }
        },

        async eliminarFactura(id) {
            if (!confirm('¿Eliminar esta factura?')) return;
            try {
                await ApiService.getInstance().delete(`/api/facturas/${id}`);
                await this.cargarFacturas();
            } catch (error) {
                console.error('Error al eliminar factura:', error);
            }
        },

        async actualizarEstadoTrabajo(id, estado) {
            try {
                await ApiService.getInstance().put(`/api/trabajos/${id}`, { estado });
                await this.cargarTrabajos();
            } catch (error) {
                console.error('Error al actualizar estado:', error);
            }
        },

        verDetalleCliente(cliente) {
            this.clienteDetalle = cliente;
            this.modalAbierto = 'ver-cliente';
        },

        verDetalleTrabajo(trabajo) {
            this.trabajoDetalle = trabajo;
            this.modalAbierto = 'ver-trabajo';
        },

        verDetalleInventario(item) {
            this.inventarioDetalle = item;
            this.modalAbierto = 'ver-inventario';
        },

        verDetalleFactura(factura) {
            // Implementar según sea necesario
        },

        imprimirFactura(factura) {
            // Implementar impresión
        },

        fotoCapturadaHandler(foto) {
            this.fotoEnGrande = foto;
        }
    }
});

// Registrar componentes
app.component('login-component', window.LoginComponent);
app.component('navbar-component', window.NavbarComponent);
app.component('drawer-menu-component', window.DrawerMenuComponent);
app.component('dashboard-component', window.DashboardComponent);
app.component('clientes-component', window.ClientesComponent);
app.component('trabajos-component', window.TrabajosComponent);
app.component('inventario-component', window.InventarioComponent);
app.component('categorias-component', window.CategoriasComponent);
app.component('proveedores-component', window.ProveedoresComponent);
app.component('facturas-component', window.FacturasComponent);
app.component('modal-cliente-component', window.ModalClienteComponent);
app.component('modal-trabajo-component', window.ModalTrabajoComponent);
app.component('modal-inventario-component', window.ModalInventarioComponent);
app.component('modal-categoria-component', window.ModalCategoriaComponent);
app.component('modal-proveedor-component', window.ModalProveedorComponent);
app.component('modal-factura-component', window.ModalFacturaComponent);
app.component('modal-movimiento-component', window.ModalMovimientoComponent);
app.component('modal-ver-trabajo-component', window.ModalVerTrabajoComponent);
app.component('modal-ver-cliente-component', window.ModalVerClienteComponent);
app.component('modal-ver-inventario-component', window.ModalVerInventarioComponent);
app.component('modal-camara-component', window.ModalCamaraComponent);
app.component('modal-ver-foto-component', window.ModalVerFotoComponent);

// Montar aplicación
app.mount('#app');
