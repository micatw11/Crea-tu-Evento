<template>
    <div>
        <a class="btn btn-primary btn-block" @click="toggleShow">Cambiar Foto</a>
        <my-upload
            field="avatar"
            v-model="show"
            :width="300"
            :url="url"
            :params="params"
            :height="300"
            :langExt="es"
            :headers="headers">
        </my-upload>
    </div>

</template>

<script>
import 'babel-polyfill';
import myUpload from 'vue-image-crop-upload/upload-2.vue';
import lang from './lang.js';

export default {
        data() {
            return {
                url: 'api/user/'+this.$route.params.userId+'/perfil/avatar/',
                show: false,
                headers: {
                    smail: '*_~',
                    'X-CSRF-TOKEN': Laravel.csrfToken
                },
                params: {
                    name: 'avatar'
                },
                es: {
                        hint: "Haga clic en o arrastre el archivo",
                        loading: "Cargando...",
                        noSupported: "El navegador no admite, utilice IE10 + u otros navegadores",
                        success: "Subida exitosa",
                        fail: "Subida fallida",
                        preview: "Vista previa",
                        btn: {
                            off: "Cancelar",
                            close: "Cerrar",
                            back: "Atras",
                            save: "Guardar"
                        },
                        error: {
                            onlyImg: "Sólo imagen",
                            outOfSize: "La imagen supera el límite de tamaño: ",
                            lowestPx: "El tamaño minimo requerido es de: "
                        }
                }
            }
        },
        components: {
            'my-upload': myUpload
        },
        methods: {
            toggleShow() {
                this.show = !this.show;
            }
        }
    }
</script> 
