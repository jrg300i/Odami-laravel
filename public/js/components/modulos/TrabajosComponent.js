// Componente Trabajos
const TrabajosComponent = {
    name: 'TrabajosComponent',
    props: {
        trabajos: Array,
        filtroEstado: String,
        cargando: Boolean
    },
    emits: ['nuevo', 'editar', 'actualizar-estado', 'ver', 'eliminar', 'cambiar-filtro'],
    template: `
        <div class="space-y-6">
            <!-- Header con filtros -->
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div class="w-full lg:w-auto overflow-x-auto">
                        <div class="flex items-center gap-2 whitespace-nowrap pb-2">
                            <button @click="$emit('cambiar-filtro', '')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroEstado === '' ? 'bg-gray-800 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']">
                                <i class="fas fa-th mr-1"></i>Todos
                            </button>
                            <button @click="$emit('cambiar-filtro', 'pendiente')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroEstado === 'pendiente' ? 'bg-yellow-500 text-white shadow-lg scale-105' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200']">
                                <i class="fas fa-clock mr-1"></i>Pendiente
                            </button>
                            <button @click="$emit('cambiar-filtro', 'en_proceso')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroEstado === 'en_proceso' ? 'bg-blue-500 text-white shadow-lg scale-105' : 'bg-blue-100 text-blue-700 hover:bg-blue-200']">
                                <i class="fas fa-spinner mr-1"></i>En Proceso
                            </button>
                            <button @click="$emit('cambiar-filtro', 'completado')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroEstado === 'completado' ? 'bg-green-500 text-white shadow-lg scale-105' : 'bg-green-100 text-green-700 hover:bg-green-200']">
                                <i class="fas fa-check-circle mr-1"></i>Completado
                            </button>
                        </div>
                    </div>
                    <button @click="$emit('nuevo')"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md transition-all duration-200 font-medium flex-shrink-0">
                        <i class="fas fa-plus mr-2"></i>Nuevo Trabajo
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="cargando" class="flex justify-center py-12">
                <div class="loading-spinner"></div>
            </div>

            <!-- Lista de Trabajos -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div v-for="trabajo in trabajos" :key="trabajo.id"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-base md:text-lg text-gray-800">{{ trabajo.tipo_trabajo }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-user mr-1 text-blue-600"></i>
                                <span class="font-medium">{{ trabajo.cliente?.nombre_completo }}</span>
                            </p>
                        </div>
                        <select v-model="trabajo.estado"
                            @change="$emit('actualizar-estado', trabajo.id, $event.target.value)"
                            :class="['px-3 py-1 rounded-full text-xs font-semibold cursor-pointer transition',
                                estadoClass(trabajo.estado)]">
                            <option value="pendiente">Pendiente</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="completado">Completado</option>
                            <option value="entregado">Entregado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ trabajo.descripcion }}</p>
                    <div class="flex gap-2 pt-3 border-t">
                        <button @click="$emit('ver', trabajo)" 
                            class="flex-1 bg-blue-100 text-blue-600 py-2 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </button>
                        <button @click="$emit('editar', trabajo)" 
                            class="px-3 bg-green-100 text-green-600 py-2 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    methods: {
        estadoClass(estado) {
            const classes = {
                pendiente: 'bg-yellow-100 text-yellow-700',
                en_proceso: 'bg-blue-100 text-blue-700',
                completado: 'bg-green-100 text-green-700',
                entregado: 'bg-purple-100 text-purple-700',
                cancelado: 'bg-red-100 text-red-700'
            };
            return classes[estado] || 'bg-gray-100 text-gray-700';
        }
    }
};

window.TrabajosComponent = TrabajosComponent;
