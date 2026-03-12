// Componente Dashboard
const DashboardComponent = {
    name: 'DashboardComponent',
    props: {
        stats: Object
    },
    emits: ['navegar'],
    template: `
        <div class="space-y-6">
            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Clientes Totales -->
                <div @click="$emit('navegar', 'clientes')"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-blue-500 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm md:text-base font-semibold">Clientes Totales</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ stats.clientes_totales || 0 }}</p>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Trabajos Pendientes -->
                <div @click="$emit('navegar', 'trabajos')"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-yellow-500 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm md:text-base font-semibold">Trabajos Pendientes</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ stats.trabajos_pendientes || 0 }}</p>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Ingresos del Mes -->
                <div class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm md:text-base font-semibold">Ingresos del Mes</p>
                            <p class="text-xl md:text-2xl font-bold text-gray-800">S/ {{ stats.ingresos_mes || 0 }}</p>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600 text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Stock Bajo -->
                <div @click="$emit('navegar', 'inventario')"
                    class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-red-500 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm md:text-base font-semibold">Stock Bajo</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ stats.stock_bajo || 0 }}</p>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `
};

// Registrar componente globalmente
window.DashboardComponent = DashboardComponent;
