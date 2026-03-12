// Modal Ver Inventario
const ModalVerInventarioComponent = {
    name: 'ModalVerInventarioComponent',
    props: { inventarioDetalle: Object },
    emits: ['cerrar'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6"><h3 class="text-xl font-bold mb-4">Detalle del Item</h3><div v-if="inventarioDetalle"><p><strong>Nombre:</strong> {{ inventarioDetalle.nombre }}</p><p><strong>Categoría:</strong> {{ inventarioDetalle.categoria }}</p><p><strong>Stock:</strong> <span :class="inventarioDetalle.stock_actual <= inventarioDetalle.stock_minimo ? 'text-red-600 font-bold' : 'text-green-600'">{{ inventarioDetalle.stock_actual }} (mín: {{ inventarioDetalle.stock_minimo }})</span></p><p><strong>Precio:</strong> S/ {{ inventarioDetalle.precio_unitario }}</p></div><button @click="$emit('cerrar')" class="mt-4 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cerrar</button></div></div>`
};
window.ModalVerInventarioComponent = ModalVerInventarioComponent;
