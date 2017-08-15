<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Registrar un rubro</h3>
                        </div>
                        <div class="box box-body">
                        	<form-rubro v-if="getRubros"
                                :rubro="rubro" 
                                :domicilio= "domicilio"
                                :rubrosRegistrados="rubrosRegistrados"
                                :nuevo= "true"
                                :errorsApi="errorsApi"
                                @validado="sendForm()">
                            </form-rubro>
                        </div>
                        <div class="box box-footer">
                            <div style="text-align:center;">
                                <button @click="goBack()" class="btn btn-default">
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
import auth from '../../../auth.js';
import route from '../../../routes.js';
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
            validarRubro: false,
            errorsApi: [],
            error: false,
            estado: true,
            getRubros: false,
            rubrosRegistrados: [],
            param: {}
        }
    },
    beforeMount: function(){
        this.getRubrosRegistrados(); 
    },
    components: {
        FormRubro
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {

            if (this.rubro.comercio){
                    this.params= {
                            rubro_id: this.rubro.rubro_id,
                            habilitacion: this.rubro.habilitacion,
                            fecha_habilitacion: this.rubro.fecha_habilitacion,
                            calle: this.domicilio.calle,
                            numero: this.domicilio.numero,
                            piso: this.domicilio.piso,
                            localidad_id: this.domicilio.localidad_id.value,
                            comercio: this.rubro.comercio
                    }
            } else {
                this.params= {
                        rubro_id: this.rubro.rubro_id,
                        comercio: this.rubro.comercio
                }
            }
            this.$http.post(
                'api/proveedor/rubro/', this.params
                )
                .then(response => {
                    console.log("respuesta ok");
                    this.$toast.success({
                         title:'¡Detalle de Rubro Creada!',
                         message:'Se creado correctamente el Detalle de Rubro. :D'
                    });
                    this.resetForm();
                    this.goBack();
                    this.errorsApi= [];
                }, response => {
                    console.log("resp. fallo");
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
                comercio: true,
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
        goBack(){
            route.go(-1)
        },
        getRubrosRegistrados: function() {
            this.$http.get('api/proveedor/'+auth.user.profile.id+'/rubro/'
                ).then(response => {
                    this.rubrosRegistrados = response.data.rubros
                    this.getRubros = true;
                });
        },
    }
}
</script>
