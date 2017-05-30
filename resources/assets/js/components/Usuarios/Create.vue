<template>
    <div>
            <!--<formulario @usuario="create" :usuario= "usuario"></formulario>-->
    </div>
</template>

<script>
import Formulario from './CrudForm.vue';
import router from '../../routes.js';

export default {
    data() {
        return {
            usuario: {
                avatar: '',
                nombre: '',
                apellido: '',
                fecha_nac: '',
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
                    //this.errorsApi = response.body//lista de errores

            })
        },
       

        valida(usuario) {
                this.sendForm(usuario);
        }
    }
}
</script>
