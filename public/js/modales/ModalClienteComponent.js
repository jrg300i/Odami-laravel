// Modal Cliente Component
const ModalClienteComponent = {
    name: 'ModalClienteComponent',
    props: {
        editingCliente: Object,
        clienteForm: Object
    },
    emits: ['cerrar', 'guardar'],
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold">{{ editingCliente ? 'Editar' : 'Nuevo' }} Cliente</h3>
                </div>
                <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Nombre</label>
                            <input v-model="form.nombre" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Apellido</label>
                            <input v-model="form.apellido" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Documento</label>
                        <input v-model="form.documento" type="text" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Teléfono</label>
                        <input v-model="form.telefono" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Email</label>
                        <input v-model="form.email" type="email" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Dirección</label>
                        <input v-model="form.direccion" type="text" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input v-model="form.activo" type="checkbox" id="cliente-activo" class="mr-2">
                        <label for="cliente-activo" class="text-sm font-semibold">Activo</label>
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" @click="$emit('cerrar')" class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    `,
    data() {
        return {
            form: { ...this.clienteForm }
        };
    },
    methods: {
        handleSubmit() {
            this.$emit('guardar', { ...this.form });
        }
    }
};

window.ModalClienteComponent = ModalClienteComponent;
