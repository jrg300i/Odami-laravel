// Modales de Ver Detalle (simplificados)
const ModalVerTrabajoComponent = {
    name: 'ModalVerTrabajoComponent',
    props: { trabajoDetalle: Object },
    emits: ['cerrar'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6"><h3 class="text-xl font-bold mb-4">Detalle del Trabajo</h3><div v-if="trabajoDetalle"><p><strong>Tipo:</strong> {{ trabajoDetalle.tipo_trabajo }}</p><p><strong>Cliente:</strong> {{ trabajoDetalle.cliente?.nombre_completo }}</p><p><strong>Estado:</strong> {{ trabajoDetalle.estado }}</p><p><strong>Descripción:</strong> {{ trabajoDetalle.descripcion }}</p></div><button @click="$emit('cerrar')" class="mt-4 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cerrar</button></div></div>`
};
window.ModalVerTrabajoComponent = ModalVerTrabajoComponent;

const ModalVerClienteComponent = {
    name: 'ModalVerClienteComponent',
    props: { clienteDetalle: Object },
    emits: ['cerrar'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6"><h3 class="text-xl font-bold mb-4">Detalle del Cliente</h3><div v-if="clienteDetalle"><p><strong>Nombre:</strong> {{ clienteDetalle.nombre_completo }}</p><p><strong>Documento:</strong> {{ clienteDetalle.documento }}</p><p><strong>Teléfono:</strong> {{ clienteDetalle.telefono }}</p></div><button @click="$emit('cerrar')" class="mt-4 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cerrar</button></div></div>`
};
window.ModalVerClienteComponent = ModalVerClienteComponent;

const ModalVerInventarioComponent = {
    name: 'ModalVerInventarioComponent',
    props: { inventarioDetalle: Object },
    emits: ['cerrar'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6"><h3 class="text-xl font-bold mb-4">Detalle del Item</h3><div v-if="inventarioDetalle"><p><strong>Nombre:</strong> {{ inventarioDetalle.nombre }}</p><p><strong>Stock:</strong> {{ inventarioDetalle.stock_actual }}</p><p><strong>Precio:</strong> S/ {{ inventarioDetalle.precio_unitario }}</p></div><button @click="$emit('cerrar')" class="mt-4 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cerrar</button></div></div>`
};
window.ModalVerInventarioComponent = ModalVerInventarioComponent;

const ModalCamaraComponent = {
    name: 'ModalCamaraComponent',
    props: { trabajoId: Number },
    emits: ['cerrar', 'foto-capturada'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl p-6"><h3 class="text-xl font-bold mb-4">Capturar Foto</h3><p class="text-gray-600">Funcionalidad de cámara</p><button @click="$emit('cerrar')" class="mt-4 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cerrar</button></div></div>`
};
window.ModalCamaraComponent = ModalCamaraComponent;

const ModalVerFotoComponent = {
    name: 'ModalVerFotoComponent',
    props: { fotoEnGrande: String },
    emits: ['cerrar'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><img :src="fotoEnGrande" class="max-w-full max-h-[90vh] rounded-lg"><button @click="$emit('cerrar')" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">&times;</button></div>`
};
window.ModalVerFotoComponent = ModalVerFotoComponent;
