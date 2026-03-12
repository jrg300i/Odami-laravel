// Modal Inventario Component
const ModalInventarioComponent = {
    name: 'ModalInventarioComponent',
    props: { editingInventario: Object, inventarioForm: Object, categorias: Array },
    emits: ['cerrar', 'guardar'],
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b"><h3 class="text-xl font-bold">{{ editingInventario ? 'Editar' : 'Nuevo' }} Item</h3></div>
                <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nombre</label>
                        <input v-model="form.nombre" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Categoría</label>
                        <select v-model="form.categoria" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option v-for="cat in categorias" :key="cat" :value="cat">{{ cat }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Stock Actual</label>
                            <input v-model.number="form.stock_actual" type="number" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Stock Mínimo</label>
                            <input v-model.number="form.stock_minimo" type="number" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Precio Unitario</label>
                        <input v-model.number="form.precio_unitario" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" @click="$emit('cerrar')" class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    `,
    data() { return { form: { ...this.inventarioForm } }; },
    methods: { handleSubmit() { this.$emit('guardar', { ...this.form }); } }
};
window.ModalInventarioComponent = ModalInventarioComponent;
