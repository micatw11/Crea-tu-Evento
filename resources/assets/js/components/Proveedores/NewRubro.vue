<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h4>Crear Rubro</h4>
                        </div>
                        <div class="box-body table-responsive no-padding">
                        	<form-rubro :rubro="rubro" :domicilio= "domicilio" :validarDomicilio="validarDomicilio" :validarRubro="validarRubro" :errorsApi="errorsApi"></form-rubro>
                            <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
                                <button class="btn btn-default">
                                    <i class="glyphicon glyphicon-chevron-left"></i>
                                    Atras
                                </button>
                                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                                    Guargar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
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
            validarRubro: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            Comercio: null
        }
    },
    components: {
        FormRubro
    },
     mounted() {
        //this.fecha_habilitacion = new Date();
        //this.disabled.from = this.fecha.getFullYear()+'-'+this.fecha.getMonth()+'-'+this.fecha.getDate();
        this.$events.$on('validado', () =>this.sendForm());
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
             console.log('send', this.rubro)
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
                    this.showModificar = false;
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm();
                }, response => {
                    this.validar= false;
                    this.validarDomicilio= false;
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
                    this.validarDomicilio = true;
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
        }
    }
}
</script>
