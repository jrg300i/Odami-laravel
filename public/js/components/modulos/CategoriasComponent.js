// Componente Categorías
const CategoriasComponent = {
    name: 'CategoriasComponent',
    props: {
        categorias: Array,
        filtroCategoria: String,
        cargando: Boolean
    },
    emits: ['nuevo', 'editar', 'eliminar', 'cambiar-filtro'],
    template: `
        <div class="space-y-6">
            <!-- Header con filtros -->
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div class="w-full lg:w-auto overflow-x-auto">
                        <div class="flex items-center gap-2 whitespace-nowrap pb-2">
                            <button @click="$emit('cambiar-filtro', '')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroCategoria === '' ? 'bg-gray-800 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']">
                                <i class="fas fa-th mr-1"></i>Todas
                            </button>
                            <button @click="$emit('cambiar-filtro', 'activo')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroCategoria === 'activo' ? 'bg-green-500 text-white shadow-lg scale-105' : 'bg-green-100 text-green-700 hover:bg-green-200']">
                                <i class="fas fa-check-circle mr-1"></i>Activas
                            </button>
                            <button @click="$emit('cambiar-filtro', 'inactivo')"
                                :class="['px-4 py-2 rounded-lg font-medium transition-all duration-200 flex-shrink-0',
                                    filtroCategoria === 'inactivo' ? 'bg-red-500 text-white shadow-lg scale-105' : 'bg-red-100 text-red-700 hover:bg-red-200']">
                                <i class="fas fa-times-circle mr-1"></i>Inactivas
                            </button>
                        </div>
                    </div>
                    <button @click="$emit('nuevo')"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-5 py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 shadow-md transition-all duration-200 font-medium flex-shrink-0">
                        <i class="fas fa-plus mr-2"></i>Nueva Categoría
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="cargando" class="flex justify-center py-12">
                <div class="loading-spinner"></div>
            </div>

            <!-- Lista -->
            <div v-else-if="categorias.length === 0" class="bg-white rounded-xl shadow-md p-12 text-center">
                <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">No hay categorías registradas</h3>
                <p class="text-gray-500 mb-6">Comienza agregando tu primera categoría</p>
                <button @click="$emit('nuevo')" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>Agregar Categoría
                </button>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="cat in categorias" :key="cat.id"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 hover:shadow-lg hover:scale-105 transition-all duration-300"
                    :class="cat.color || 'border-blue-500'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3 flex-1">
                            <div :class="cat.color || 'bg-blue-500'" 
                                class="w-12 h-12 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <i :class="['fas', cat.icono || 'fa-box', 'text-xl']"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-base md:text-lg text-gray-800 truncate">{{ cat.nombre }}</h3>
                                <span :class="cat.activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" 
                                    class="px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ cat.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p v-if="cat.descripcion" class="text-sm text-gray-600 mb-4 line-clamp-2">{{ cat.descripcion }}</p>
                    <div class="flex gap-2 pt-3 border-t">
                        <button @click="$emit('editar', cat)" 
                            class="flex-1 bg-blue-100 text-blue-600 py-2 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </button>
                        <button @click="$emit('eliminar', cat.id)" 
                            class="px-3 bg-red-100 text-red-600 py-2 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `
};

window.CategoriasComponent = CategoriasComponent;
