<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>{{ titleContent }} formulario</legend>
            <formulario v-on:submitted="valida(usuario)" :usuario="usuario"></formulario>
        </div>
    </div>
</template>

<script>
import Formulario from './CrudForm.vue'

export default {
    data() {
        return {
            titleContent: 'Completar ',
            usuario: {
                nombre: '',
                apellido: '',
                    //fecha_nac: null,
                sexo: ''
            }
            
        }
    },
    components: {Formulario},
    methods: {
        sendForm(formData){
            this.$http.post(
                'api/usuario', formData)
                    .then(response => {
                usuario = response.body.data

            }, response => {
                this.error = true
            })
        },
        valida(usuario) {
                this.sendForm(usuario);
        }
    }
}
</script>
