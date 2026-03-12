// Modales de Ver Detalle
const ModalVerClienteComponent = {
    name: 'ModalVerClienteComponent',
    props: { clienteDetalle: Object },
    emits: ['cerrar'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6"><h3 class="text-xl font-bold mb-4">Detalle del Cliente</h3><div v-if="clienteDetalle"><p><strong>Nombre:</strong> {{ clienteDetalle.nombre_completo }}</p><p><strong>Documento:</strong> {{ clienteDetalle.documento }}</p><p><strong>Teléfono:</strong> {{ clienteDetalle.telefono }}</p><p><strong>Email:</strong> {{ clienteDetalle.email }}</p><p><strong>Dirección:</strong> {{ clienteDetalle.direccion }}</p><p><strong>Estado:</strong> <span :class="clienteDetalle.activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="px-2 py-1 rounded text-xs font-semibold">{{ clienteDetalle.activo ? 'Activo' : 'Inactivo' }}</span></p></div><button @click="$emit('cerrar')" class="mt-4 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cerrar</button></div></div>`
};
window.ModalVerClienteComponent = ModalVerClienteComponent;
