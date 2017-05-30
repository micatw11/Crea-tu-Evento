<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>{{ titleContent }}</legend>
            <formulario v-on:usuario="sendForm" :usuario= "usuario"></formulario>
        </div>
    </div>
</template>

<script>
import Formulario from './CrudForm.vue';
import router from '../../routes.js';

export default {
    props:['usuario'],
    data() {
        return {
            titleContent: 'Editar ',
        }
    },
    components: {Formulario},
    methods: {
        sendForm(formData){
            this.$http.path(
                'api/usuario', formData)
                    .then(response => {
                    this.usuario = response.body.data, 
                    console.log(this.usuario)

            }, response => {
                    console.log(response);
                    this.errorsApi = response.body//lista de errores

            })
        }
    }
}
</script>
