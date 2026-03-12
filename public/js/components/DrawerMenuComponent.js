// Componente Drawer Menu Usuario
const DrawerMenuComponent = {
    name: 'DrawerMenuComponent',
    props: {
        usuario: Object
    },
    emits: ['cerrar'],
    data() {
        return {
            drawerTranslateX: 0,
            touchStartX: 0,
            touchCurrentX: 0,
            touchIsSwiping: false
        };
    },
    template: `
        <div class="fixed inset-0 z-50"
            @touchstart="touchStart"
            @touchmove="touchMove"
            @touchend="touchEnd"
            @click.self.stop="$emit('cerrar')">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
            
            <!-- Panel lateral derecho -->
            <div class="absolute right-0 top-0 h-full w-72 max-w-[75vw] bg-white shadow-2xl overflow-y-auto transform transition-transform duration-300 ease-out"
                :style="{ transform: 'translateX(' + drawerTranslateX + 'px)' }">
                <!-- Header con avatar y botón cerrar -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-blue-600 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-blue-300">
                            <span class="font-bold text-blue-600 text-2xl">{{ usuario.nombre.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-white truncate text-lg">{{ usuario.nombre }}</p>
                            <p class="text-xs text-blue-100 capitalize truncate">{{ usuario.rol }}</p>
                        </div>
                    </div>
                    <button @click.stop="$emit('cerrar')" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition">
                        <i class="fas fa-times text-white text-xl"></i>
                    </button>
                </div>

                <!-- Contenido del menú -->
                <div class="p-4 space-y-4">
                    <!-- Email -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-envelope text-blue-600 mt-1 flex-shrink-0"></i>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-500 mb-1">Email</p>
                                <p class="text-sm text-gray-800 font-medium truncate">{{ usuario.email || 'Sin email' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cerrar Sesión -->
                    <button @click="logout"
                        class="w-full bg-red-50 hover:bg-red-100 rounded-lg p-4 flex items-center transition-colors duration-200 group border-2 border-red-200">
                        <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-4 flex-shrink-0 group-hover:bg-red-200 transition">
                            <i class="fas fa-sign-out-alt text-red-600 text-xl group-hover:text-red-700"></i>
                        </div>
                        <div class="min-w-0 flex-1 text-left">
                            <p class="font-bold text-red-600 group-hover:text-red-700 truncate">Cerrar Sesión</p>
                            <p class="text-xs text-gray-500 truncate">Salir de tu cuenta</p>
                        </div>
                    </button>

                    <!-- Información adicional -->
                    <div class="bg-gray-50 rounded-lg p-4 border-t-4 border-blue-500">
                        <div class="flex items-center justify-center space-x-2 text-gray-400">
                            <i class="fas fa-shield-alt"></i>
                            <p class="text-xs text-center">Conexión segura vía Cloudflare Tunnel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    methods: {
        touchStart(event) {
            this.touchStartX = event.touches[0].clientX;
            this.touchCurrentX = this.drawerTranslateX;
            this.touchIsSwiping = true;
        },
        touchMove(event) {
            if (!this.touchIsSwiping) return;
            const touchX = event.touches[0].clientX;
            const deltaX = touchX - this.touchStartX;
            if (deltaX > 0) {
                this.drawerTranslateX = Math.min(288, Math.max(0, this.touchCurrentX + deltaX));
                event.preventDefault();
            }
        },
        touchEnd() {
            if (!this.touchIsSwiping) return;
            this.touchIsSwiping = false;
            if (this.drawerTranslateX > 100) {
                this.$emit('cerrar');
            } else {
                this.drawerTranslateX = 0;
            }
        },
        logout() {
            localStorage.removeItem('tapiceria_token');
            localStorage.removeItem('tapiceria_usuario');
            window.location.reload();
        }
    }
};

// Registrar componente globalmente
window.DrawerMenuComponent = DrawerMenuComponent;
