// Componente Clientes
const ClientesComponent = {
    name: 'ClientesComponent',
    props: {
        clientes: Array,
        cargando: Boolean
    },
    emits: ['nuevo', 'editar', 'ver', 'eliminar'],
    template: `
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex justify-between items-center">
                    <button @click="$emit('nuevo')" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>Nuevo Cliente
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="cargando" class="flex justify-center py-12">
                <div class="loading-spinner"></div>
            </div>

            <!-- Lista de Clientes -->
            <div v-else-if="clientes.length === 0" class="bg-white rounded-xl shadow-md p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">No hay clientes registrados</h3>
                <p class="text-gray-500 mb-6">Comienza agregando tu primer cliente</p>
                <button @click="$emit('nuevo')" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Agregar Cliente
                </button>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="cliente in clientes" :key="cliente.id"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <span class="font-bold text-lg">{{ cliente.nombre_completo?.charAt(0) || 'C' }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-base md:text-lg text-gray-800 truncate">{{ cliente.nombre_completo }}</h3>
                                <span :class="cliente.activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" 
                                    class="px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ cliente.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-id-card mr-2 text-blue-600 w-4"></i>
                            <span>{{ cliente.documento || 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone mr-2 text-green-600 w-4"></i>
                            <a :href="'https://wa.me/' + cliente.telefono" target="_blank" class="hover:text-green-600">
                                {{ cliente.telefono || 'N/A' }}
                            </a>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        <button @click="$emit('editar', cliente)" 
                            class="flex-1 bg-blue-100 text-blue-600 py-2 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </button>
                        <button @click="$emit('eliminar', cliente.id)" 
                            class="px-3 bg-red-100 text-red-600 py-2 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `
};

// Registrar componente globalmente
window.ClientesComponent = ClientesComponent;
