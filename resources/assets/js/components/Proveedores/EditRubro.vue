<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h4>Modificar Rubro</h4>
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
    props: {
            perfil: {
                type: Object,
                required: true
            }
    },
    data() {
        return {
            dato: this.perfil,
            datos: this.dato.user.proveedor.rubro,
            domicilio: {
                calle: this.datos.domicilio.calle,
                numero: this.datos.domicilio.numero,
                piso: this.datos.domicilio.piso,
                localidad_id: this.datos.domicilio.localidad_id
            },
            rubro:{
                tipo_rubro: this.datos.tipo_rubro,
                categoria_id: this.datos.categoria_id,
                denominacion: this.datos.denominacion,
                descripcion: this.datos.descripcion,
                habilitacion: this.datos.habilitacion,
                fecha_habilitacion: this.datos.fecha_habilitacion
            },
            validarRubro: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            Comercio: null,
            fecha: null,
            disabled: { to: '1920-01-01', from: null }
        }
    },
    components: {
        FormRubro
    },
    beforeMount: function(){
        //selected data
        this.getUserPerfil();
        //this.setDefaultLocalidad();
    },
     mounted() {
         //rangos maximos de fechas
        this.fecha_habilitacion = new Date();
        this.disabled.from = this.fecha.getFullYear()+'-'+this.fecha.getMonth()+'-'+this.fecha.getDate();

        this.$events.$on('validado', () =>this.sendForm());
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        

        
        //form validation
        validateBeforeSubmit: function() {                 
                    this.validarRubro = true;
                    this.validarDomicilio = true;
                    this.$events.fire('validarForm')

        },
     
    }
}



        
</script>