// Componente Login
const LoginComponent = {
    name: 'LoginComponent',
    props: {
        error: String,
        cargando: Boolean
    },
    emits: ['login'],
    data() {
        return {
            username: '',
            password: ''
        };
    },
    template: `
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-700 to-blue-500 p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-couch text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">Tapicería Odami</h1>
                    <p class="text-gray-500 mt-2">Sistema de Gestión</p>
                </div>

                <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    {{ error }}
                </div>

                <form @submit.prevent="handleSubmit">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Usuario</label>
                        <input v-model="username" type="text" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="admin">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Contraseña</label>
                        <input v-model="password" type="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="••••••••">
                    </div>
                    <button type="submit" :disabled="cargando"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white font-bold py-3 rounded-lg hover:from-blue-700 hover:to-blue-900 transition duration-300 disabled:opacity-50">
                        <span v-if="cargando"><i class="fas fa-spinner fa-spin"></i> Iniciando...</span>
                        <span v-else><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</span>
                    </button>
                </form>

                <div class="mt-6 text-center text-xs text-gray-400">
                    <p><i class="fas fa-shield-alt"></i> Conexión segura</p>
                </div>
            </div>
        </div>
    `,
    methods: {
        handleSubmit() {
            this.$emit('login', {
                username: this.username,
                password: this.password
            });
        }
    }
};

// Registrar componente globalmente
window.LoginComponent = LoginComponent;
