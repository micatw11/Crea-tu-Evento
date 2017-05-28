<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>{{ titleContent }} formulario</legend>
            <formulario @usuario="create" :usuario= "usuario"></formulario>
        </div>
    </div>
</template>

<script>
import Formulario from './CrudForm.vue';
import router from '../../routes.js';

export default {
    data() {
        return {
            titleContent: 'Completar ',
            usuario: {
                nombre: '',
                apellido: '',
                    //fecha_nac: null,
                sexo: '',
                user_id: ''
            }
            
        }
    },
    components: {Formulario},
    methods: {
        create(formData){
            this.$http.post(
                'api/usuario', formData)
                    .then(response => {
                    this.usuario = response.body.data, 
                    console.log(this.usuario),
                    router.push({
                    name: 'home'
                })
            }, response => {
                    console.log(response);
                    this.error = true  
                    this.errorsApi = response.body//lista de errores

            })
        },
        valida(usuario) {
                this.sendForm(usuario);
        }
    }
}
</script>
