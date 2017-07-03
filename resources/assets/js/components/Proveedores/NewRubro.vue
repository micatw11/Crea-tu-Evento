<template>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" @click="closeModal()">&times;</button>
            <h4 class="modal-title">Crear Rubro</h4>
        </div>
        <div class="modal-body">

            <div class="box-body">
            	<form-rubro 
                    :rubro="rubro" 
                    :domicilio= "domicilio" 
                    :estado= "estado"
                    :errorsApi="errorsApi">
                </form-rubro>
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
    </div>
</template>

<script>
import auth from '../../auth.js';
import FormRubro from './FormRubro.vue';

export default {
    data() {
        return {
            domicilio: {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            },
            rubro:{
                tipo_rubro: null,
                categoria_id: null,
                denominacion: null,
                descripcion: null,
                habilitacion: null,
                fecha_habilitacion: null
            },
            showNew: false,
            validarRubro: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            Comercio: null,
            estado: true
        }
    },
    components: {
        FormRubro
    },
     mounted() {
        this.$events.$on('validado', () =>this.sendForm());
        console.log("activado evento validar nuevo")
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
             console.log('send newww', this.rubro)
            this.$http.post(
                'api/proveedor/rubro/'+ this.$route.params.userId, 
                {
                    tipo_rubro: this.rubro.tipo_rubro,
                    categoria_id: this.rubro.categoria_id,
                    denominacion: this.rubro.denominacion,
                    descripcion: this.rubro.descripcion,
                    habilitacion: this.rubro.habilitacion,
                    fecha_habilitacion: this.rubro.fecha_habilitacion,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: this.domicilio.localidad_id.value
                })
                .then(response => {
                    this.$emit('reload')
                    this.showNew = false;
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm();
                }, response => {
                    this.validar= false;
                    this.validarRubro= false;
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
                    this.validarRubro = true;
                    this.$events.fire('validarForm')

        },
        resetForm() {
            this.rubro={
                tipo_rubro: null,
                categoria_id: null,
                denominacion: null,
                descripcion: null,
                habilitacion: null,
                fecha_habilitacion: null
            },
            this.domicilio= {
                habilitacion: null,
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            }
        },
        closeModal: function(){
            this.showNew = false;
        },
    }
}
</script>
