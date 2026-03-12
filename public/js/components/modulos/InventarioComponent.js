// Componente Inventario
const InventarioComponent = {
    name: 'InventarioComponent',
    props: {
        inventario: Array,
        filtroCategoria: String,
        cargando: Boolean
    },
    emits: ['nuevo', 'editar', 'ver', 'movimiento', 'eliminar', 'cambiar-filtro'],
    template: `
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex justify-between items-center">
                    <button @click="$emit('nuevo')" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>Nuevo Item
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="cargando" class="flex justify-center py-12">
                <div class="loading-spinner"></div>
            </div>

            <!-- Lista -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="item in inventario" :key="item.id"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-base md:text-lg text-gray-800">{{ item.nombre }}</h3>
                            <span :class="stockClass(item.stock_actual, item.stock_minimo)" 
                                class="px-2 py-0.5 rounded text-xs font-semibold">
                                {{ stockText(item.stock_actual, item.stock_minimo) }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stock:</span>
                            <span class="font-semibold">{{ item.stock_actual }} / {{ item.stock_minimo }} mín</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Precio:</span>
                            <span class="font-semibold">S/ {{ item.precio_unitario }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-3 border-t">
                        <button @click="$emit('movimiento', item)" 
                            class="flex-1 bg-purple-100 text-purple-600 py-2 rounded-lg hover:bg-purple-200 transition text-sm font-medium">
                            <i class="fas fa-exchange-alt mr-1"></i>Movimiento
                        </button>
                        <button @click="$emit('editar', item)" 
                            class="px-3 bg-blue-100 text-blue-600 py-2 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    methods: {
        stockClass(actual, minimo) {
            if (actual <= minimo) return 'bg-red-100 text-red-700';
            if (actual <= minimo * 1.5) return 'bg-yellow-100 text-yellow-700';
            return 'bg-green-100 text-green-700';
        },
        stockText(actual, minimo) {
            if (actual <= minimo) return 'Stock Bajo';
            if (actual <= minimo * 1.5) return 'Stock Medio';
            return 'Stock Normal';
        }
    }
};

window.InventarioComponent = InventarioComponent;
