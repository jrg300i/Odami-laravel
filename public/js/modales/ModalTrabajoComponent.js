// Modal Trabajo Component
const ModalTrabajoComponent = {
    name: 'ModalTrabajoComponent',
    props: { editingTrabajo: Object, trabajoForm: Object, clientes: Array },
    emits: ['cerrar', 'guardar'],
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
                <div class="p-6 border-b"><h3 class="text-xl font-bold">{{ editingTrabajo ? 'Editar' : 'Nuevo' }} Trabajo</h3></div>
                <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Cliente</label>
                        <select v-model="form.cliente_id" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar cliente</option>
                            <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre_completo }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tipo de Trabajo</label>
                        <input v-model="form.tipo_trabajo" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Descripción</label>
                        <textarea v-model="form.descripcion" rows="3" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Precio</label>
                            <input v-model.number="form.precio_estimado" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Anticipo</label>
                            <input v-model.number="form.anticipo" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" @click="$emit('cerrar')" class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    `,
    data() { return { form: { ...this.trabajoForm } }; },
    methods: { handleSubmit() { this.$emit('guardar', { ...this.form }); } }
};
window.ModalTrabajoComponent = ModalTrabajoComponent;
