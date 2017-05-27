<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>{{ titleContent }} formulario</legend>
            <formulario @usuario="create" :usuario= "usuario"></formulario>
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
     events: {
            'submitted': function(usuario) {
                console.log('evento')
                this.create(usuario);
            },
        },
    components: {Formulario},
    methods: {
        create(formData){
            console.log('create....')
            this.$http.post(
                'api/usuario', formData)
                    .then(response => {
                usuario = response.body.data

            }, response => {
                this.error = true
            })
        }
    }
}
</script>
