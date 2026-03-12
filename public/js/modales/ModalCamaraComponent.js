// Modal Cámara
const ModalCamaraComponent = {
    name: 'ModalCamaraComponent',
    props: { trabajoId: Number },
    emits: ['cerrar', 'foto-capturada'],
    template: `<div class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4" @click.self="$emit('cerrar')"><div class="bg-white rounded-xl p-6 max-w-md"><h3 class="text-xl font-bold mb-4">Capturar Foto</h3><video ref="video" class="w-full rounded-lg mb-4" autoplay></video><canvas ref="canvas" class="hidden"></canvas><div class="flex gap-2"><button @click="capturar" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Capturar</button><button @click="$emit('cerrar')" class="flex-1 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Cancelar</button></div></div></div>`,
    mounted() {
        this.iniciarCamara();
    },
    methods: {
        async iniciarCamara() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                this.$refs.video.srcObject = stream;
            } catch (error) {
                console.error('Error al acceder a la cámara:', error);
            }
        },
        capturar() {
            const context = this.$refs.canvas.getContext('2d');
            this.$refs.canvas.width = this.$refs.video.videoWidth;
            this.$refs.canvas.height = this.$refs.video.videoHeight;
            context.drawImage(this.$refs.video, 0, 0);
            const foto = this.$refs.canvas.toDataURL('image/jpeg');
            this.$emit('foto-capturada', foto);
            this.$emit('cerrar');
        }
    }
};
window.ModalCamaraComponent = ModalCamaraComponent;
