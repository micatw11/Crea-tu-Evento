<template>
    <div class="modal-body">
        <div class="box-body">
        	<form-rubro 
                :rubro="rubro" 
                :domicilio= "domicilio" 
                :nuevo= "nuevo"
                :errorsApi="errorsApi"
                @validado="sendForm()">
            </form-rubro>
        </div>
        <div class="modal-footer" style="text-align">
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
                rubro_id: null,
                comercio: false,
                habilitacion: null,
                fecha_habilitacion: null
            },
            showNew: false,
            nuevo: true,
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
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
            this.$http.post(
                'api/proveedor/rubro/'+ this.$route.params.userId, 
                {
                    rubro_id: this.rubro.rubro_id,
                    comercio: this.rubro.comercio,
                    habilitacion: this.rubro.habilitacion,
                    fecha_habilitacion: this.rubro.fecha_habilitacion,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: this.domicilio.localidad_id.value
                })
                .then(response => {
                    this.$emit('reload')
                    this.closeModal(),
                    this.errorsApi= {},
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm();
                    this.errorsApi= {};
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
                rubro_id: null,
                comercio: false,
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
            this.$events.fire('cerrar')
            this.$events.fire('reloadComponentPerfil');
        }
    }
}
</script>
