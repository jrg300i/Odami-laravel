// Servicio API para conexión con el backend
const ApiService = {
    api: null,
    baseUrl: null,

    // Inicializar la instancia de Axios
    init(baseUrl) {
        this.baseUrl = baseUrl;
        this.api = axios.create({
            baseURL: baseUrl,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        // Interceptor para agregar token
        this.api.interceptors.request.use(config => {
            const token = localStorage.getItem('tapiceria_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        // Interceptor para manejar errores
        this.api.interceptors.response.use(
            response => response,
            error => {
                if (error.response?.status === 401) {
                    localStorage.removeItem('tapiceria_token');
                    localStorage.removeItem('tapiceria_usuario');
                    window.location.reload();
                }
                return Promise.reject(error);
            }
        );

        return this.api;
    },

    // Obtener instancia de API con token actualizado
    getInstance() {
        const token = localStorage.getItem('tapiceria_token');
        if (this.api && token) {
            this.api.defaults.headers.Authorization = `Bearer ${token}`;
        }
        return this.api;
    },

    // Actualizar token
    setToken(token) {
        if (token) {
            localStorage.setItem('tapiceria_token', token);
            if (this.api) {
                this.api.defaults.headers.Authorization = `Bearer ${token}`;
            }
        }
    },

    // Eliminar token
    clearToken() {
        localStorage.removeItem('tapiceria_token');
        if (this.api) {
            delete this.api.defaults.headers.Authorization;
        }
    }
};

// Exportar para uso global
window.ApiService = ApiService;
