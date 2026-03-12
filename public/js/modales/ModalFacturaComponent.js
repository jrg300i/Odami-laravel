// Modal Factura Component
const ModalFacturaComponent = {
    name: 'ModalFacturaComponent',
    props: { editingFactura: Object, facturaForm: Object, trabajos: Array },
    emits: ['cerrar', 'guardar'],
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
                <div class="p-6 border-b"><h3 class="text-xl font-bold">{{ editingFactura ? 'Editar' : 'Nueva' }} Factura</h3></div>
                <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Número de Factura</label>
                        <input v-model="form.numero_factura" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Trabajo</label>
                        <select v-model="form.trabajo_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar trabajo</option>
                            <option v-for="t in trabajos" :key="t.id" :value="t.id">{{ t.tipo_trabajo }} - {{ t.cliente?.nombre_completo }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-sm font-semibold mb-1">Subtotal</label><input v-model.number="form.subtotal" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                        <div><label class="block text-sm font-semibold mb-1">IGV (18%)</label><input v-model.number="form.igv" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Total</label>
                        <input v-model.number="form.total" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" @click="$emit('cerrar')" class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    `,
    data() { return { form: { ...this.facturaForm } }; },
    methods: { handleSubmit() { this.$emit('guardar', { ...this.form }); } }
};
window.ModalFacturaComponent = ModalFacturaComponent;
