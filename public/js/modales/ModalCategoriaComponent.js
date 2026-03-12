// Modal Categoria Component
const ModalCategoriaComponent = {
    name: 'ModalCategoriaComponent',
    props: { editingCategoria: Object, categoriaForm: Object },
    emits: ['cerrar', 'guardar'],
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="p-6 border-b"><h3 class="text-xl font-bold">{{ editingCategoria ? 'Editar' : 'Nueva' }} Categoría</h3></div>
                <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nombre</label>
                        <input v-model="form.nombre" type="text" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Descripción</label>
                        <textarea v-model="form.descripcion" rows="2" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Color</label>
                            <select v-model="form.color" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="bg-blue-500">Azul</option>
                                <option value="bg-green-500">Verde</option>
                                <option value="bg-red-500">Rojo</option>
                                <option value="bg-purple-500">Morado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Ícono</label>
                            <select v-model="form.icono" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="fa-box">Caja</option>
                                <option value="fa-tags">Etiquetas</option>
                                <option value="fa-layer-group">Capas</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" @click="$emit('cerrar')" class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="flex-1 bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    `,
    data() { return { form: { ...this.categoriaForm } }; },
    methods: { handleSubmit() { this.$emit('guardar', { ...this.form }); } }
};
window.ModalCategoriaComponent = ModalCategoriaComponent;
