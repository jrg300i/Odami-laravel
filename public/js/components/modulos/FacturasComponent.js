// Componente Facturas
const FacturasComponent = {
    name: 'FacturasComponent',
    props: {
        facturas: Array,
        cargando: Boolean
    },
    emits: ['nueva', 'editar', 'ver', 'imprimir', 'eliminar'],
    template: `
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex justify-between items-center">
                    <button @click="$emit('nueva')" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>Nueva Factura
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="cargando" class="flex justify-center py-12">
                <div class="loading-spinner"></div>
            </div>

            <!-- Lista -->
            <div v-else-if="facturas.length === 0" class="bg-white rounded-xl shadow-md p-12 text-center">
                <i class="fas fa-file-invoice text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">No hay facturas registradas</h3>
                <p class="text-gray-500 mb-6">Comienza emitiendo tu primera factura</p>
                <button @click="$emit('nueva')" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Agregar Factura
                </button>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="factura in facturas" :key="factura.id"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-base md:text-lg text-gray-800">{{ factura.numero_factura }}</h3>
                            <p class="text-sm text-gray-600">{{ factura.cliente_nombre }}</p>
                        </div>
                        <span :class="estadoClass(factura.estado_pago)" 
                            class="px-2 py-0.5 rounded text-xs font-semibold">
                            {{ factura.estado_pago }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total:</span>
                            <span class="font-semibold text-green-600">S/ {{ factura.total }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Fecha:</span>
                            <span class="font-semibold">{{ formatDate(factura.fecha) }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-3 border-t">
                        <button @click="$emit('imprimir', factura)" 
                            class="flex-1 bg-purple-100 text-purple-600 py-2 rounded-lg hover:bg-purple-200 transition text-sm font-medium">
                            <i class="fas fa-print mr-1"></i>Imprimir
                        </button>
                        <button @click="$emit('editar', factura)" 
                            class="px-3 bg-blue-100 text-blue-600 py-2 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
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
                pagado: 'bg-green-100 text-green-700',
                parcial: 'bg-blue-100 text-blue-700'
            };
            return classes[estado] || 'bg-gray-100 text-gray-700';
        },
        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString();
        }
    }
};

window.FacturasComponent = FacturasComponent;
