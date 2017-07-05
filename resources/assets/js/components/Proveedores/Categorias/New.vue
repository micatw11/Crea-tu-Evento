<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-categoria 
                :categoria="categoria"  
                :nuevo= "nuevo"
                :errorsApi="errorsApi"
                @validado="sendForm()">
            </form-categoria>
        </div>
        <div class="modal-footer" style="text-align:center;">
            <button class="btn btn-default" @click="closeModal()">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Atras
            </button>
            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                Guargar
            </button>
        </div>
    </div>
</template>

<script>
import auth from '../../../auth.js';
import FormCategoria from './Form.vue';

export default {
    data() {
        return {
            categoria:{
                nombre: null,
                descripcion: null
            },
            nuevo: true,
            validarCategoria: false,
            errorsApi: {},
            error: false,
        }
    },
    components: {
        FormCategoria
    },
    methods: {
        //envio de formulario de nueva Categoria
        sendForm: function() {
            this.$http.post(
                'api/categoria', 
                {
                    nombre: this.categoria.nombre,
                    descripcion: this.categoria.descripcion
                })
                .then(response => {
                    this.$events.fire('reloadIndexCategoria')
                    this.closeModal(),
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm()
                }, response => {
                    this.validarCategoria= false;
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 422)
                    {
                        this.errorsApi = response.body;
                    }
                })
        },
        //form validation
        validateBeforeSubmit: function() {                 
                    this.validarCategoria = true;
                    this.$events.fire('validarFormCategoria')

        },
        resetForm() {
            this.categoria={
                nombre: null,
                descripcion: null
            }
        },
         closeModal: function(){
            this.$events.fire('cerrar')
        }
    }
}
</script>
