// Modal Movimiento Component
const ModalMovimientoComponent = {
    name: 'ModalMovimientoComponent',
    props: { itemMovimiento: Object, movimientoForm: Object },
    emits: ['cerrar', 'guardar'],
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b"><h3 class="text-xl font-bold">Registrar Movimiento</h3></div>
                <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="font-semibold">{{ itemMovimiento?.nombre }}</p>
                        <p class="text-sm text-gray-600">Stock actual: {{ itemMovimiento?.stock_actual }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tipo de Movimiento</label>
                        <select v-model="form.tipo_movimiento" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                            <option value="ajuste">Ajuste</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Cantidad</label>
                        <input v-model.number="form.cantidad" type="number" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Motivo</label>
                        <textarea v-model="form.motivo" required rows="2" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" @click="$emit('cerrar')" class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    `,
    data() { return { form: { ...this.movimientoForm } }; },
    methods: { handleSubmit() { this.$emit('guardar', { ...this.form }); } }
};
window.ModalMovimientoComponent = ModalMovimientoComponent;
