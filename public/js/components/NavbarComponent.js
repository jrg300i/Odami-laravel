// Componente Navbar
const NavbarComponent = {
    name: 'NavbarComponent',
    props: {
        usuario: Object,
        vistaActual: String,
        menuItems: Array,
        notificacionesCount: Number,
        cargando: Boolean
    },
    emits: ['navegar', 'refrescar', 'toggle-notificaciones', 'abrir-menu-usuario'],
    template: `
        <nav class="bg-gradient-to-r from-blue-900 to-blue-800 text-white shadow-lg flex-shrink-0">
            <div class="px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo y Menú -->
                    <div class="flex items-center flex-1 overflow-x-auto scrollbar-thin">
                        <!-- Logo -->
                        <div class="flex items-center mr-2 flex-shrink-0">
                            <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-couch text-blue-800 text-lg"></i>
                            </div>
                            <span class="font-bold text-base ml-2 whitespace-nowrap hidden sm:block">Odami</span>
                        </div>

                        <!-- Separador -->
                        <div class="w-px h-6 bg-blue-700 mx-2 flex-shrink-0 hidden sm:block"></div>

                        <!-- Menú de Navegación -->
                        <div class="flex items-center space-x-0.5">
                            <a v-for="item in menuItems" :key="item.id"
                                @click="$emit('navegar', item.id)"
                                :class="['flex items-center px-3 py-2 rounded-lg cursor-pointer transition whitespace-nowrap group',
                                    vistaActual === item.id
                                        ? 'bg-white text-blue-900 shadow-md'
                                        : 'text-blue-100 hover:bg-blue-700 hover:text-white']"
                                :title="item.nombre">
                                <i :class="['fas', item.icono, 'text-lg',
                                    vistaActual === item.id ? 'text-blue-600' : 'text-blue-200 group-hover:text-white']"></i>
                                <span class="ml-2 text-sm font-medium hidden lg:inline">{{ item.nombre }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Acciones de Usuario -->
                    <div class="flex items-center space-x-1 flex-shrink-0">
                        <!-- Botón Refrescar -->
                        <button @click="$emit('refrescar')" class="p-2 hover:bg-blue-700 rounded-lg transition" title="Refrescar">
                            <i class="fas fa-sync-alt" :class="{'fa-spin': cargando}"></i>
                        </button>

                        <!-- Notificaciones -->
                        <button @click="$emit('toggle-notificaciones')" class="p-2 hover:bg-blue-700 rounded-lg transition relative" title="Notificaciones">
                            <i class="fas fa-bell"></i>
                            <span v-if="notificacionesCount > 0" class="absolute top-1 right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                                {{ notificacionesCount }}
                            </span>
                        </button>

                        <!-- Botón Menú Usuario -->
                        <button @click="$emit('abrir-menu-usuario')"
                            class="p-2 hover:bg-blue-700 rounded-lg transition"
                            title="Menú usuario">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    `
};

// Registrar componente globalmente
window.NavbarComponent = NavbarComponent;
