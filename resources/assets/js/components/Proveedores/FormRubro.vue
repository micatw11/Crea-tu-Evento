<template>
    <form class="form-horizontal">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarRubro}">
                <div class="col-sm-10">
                    <label for="inputCategoria" class="control-label">Categoria</label><br>
                     <input name="categoria" v-validate="'required'" type="number" v-model="rubro.categoria_id" value="categoria">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('categoria')&&validarRubro" class="help-block">{{ errors.first('categoria') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.categoria">
                        <div v-for="msj in errorsApi.categoria">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('denominacion')&&validarRubro}">
                <div class="col-sm-10">
                    <label for="inputDenominacion" class="control-label">Denominacion del rubro: </label><br>
                    <input name="denominacion"  v-validate:rubro.denominacion="'required|min:4'" type="text" class="form-control" v-model="rubro.denominacion" placeholder="Nombre">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('denominacion')&&validarRubro" class="help-block">{{ errors.first('denominacion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.denominacion">
                        <div v-for="msj in errorsApi.denominacion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descripcion')&&validarRubro}">
                <div class="col-sm-10">
                    <label for="inputDescripcion" class="control-label">Descripcion: </label><br>
                    <input name="descripcion"  v-validate:rubro.descripcion="'required|min:4'" type="text" class="form-control" v-model="rubro.descripcion" placeholder="Descripcion">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('descripcion')&&validarRubro" class="help-block">{{ errors.first('descripcion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.descripcion">
                        <div v-for="msj in errorsApi.descripcion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!--Datos de Habilitación y dirección del comercio
            <label class="control-label">Cuenta con Comercio de atención. </label> <br>  
            <input type="checkbox" id="checkbox" v-model="Comercio" style="text-align:center;">
            <label for="checkbox">{{ Comercio == true ? "Si" : "No" }}</label>
            v-if="Comercio == true"-->
             <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('facha')&&validarRubro}">
                <div class="col-sm-10"><br/>
                    <label for="inputNombre" class="control-label">Fecha de habilitación</label><br>
                    <input 
                        v-model="rubro.fecha_habilitacion" 
                        type="date" 
                        v-validate="'required'"
                        name="facha"
                        v-validate:rubro.fecha_habilitacion="'required'"
                        >
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('facha')&&validarRubro" class="help-block">{{ errors.first('facha') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.fecha_habilitacion">
                        <div v-for="msj in errorsApi.fecha_habilitacion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
     
        </div>
        
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validarRubro}">
                <div class="col-sm-10">
                    <label for="inputHabilitacion" class="control-label">Habilitación</label><br>
                     <input name="habilitacion" v-validate="'required'" type="number" v-model="rubro.habilitacion" value="habilitacion" class="form-control">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('habilitacion')&&validarRubro" class="help-block">{{ errors.first('habilitacion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.habilitacion">
                        <div v-for="msj in errorsApi.habilitacion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
           <form-domicilio :domicilio="domicilio" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-domicilio>
        </div>
    </form>
</template>



<script>
import auth from '../../auth.js';
import vSelect from "vue-select";
import FormDomicilio from './FormDomicilio.vue';

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
                categoria_id: null,
                denominacion: null,
                descripcion: null,
                habilitacion: null,
                fecha_habilitacion: null
            },
            validar: false,
            showModificar: false,
            validarRubro: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            Comercio: null
        }
    },
    components: {
        vSelect, FormDomicilio
    },
     mounted() {
            this.$events.$on('validarForm', () =>this.validateBeforeSubmit());
        },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(() => {
                    this.validarRubro = false;
                    //this.$events.fire('validarForm');
                }).catch(() => {
                    this.validarRubro = true;
                });
        }
    }
}
</script>