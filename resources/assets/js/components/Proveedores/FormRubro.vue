<template>
    <form class="form-horizontal">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarRubro}">
                <label for="inputCategoria" class="col-sm-2 control-label">Categoria</label>
                <div class="col-sm-10">
                     <input name="categoria" v-validate="'required'" type="number" v-model="rubro.categoria" value="categoria">
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
                <label for="inputDenominacion" class="col-sm-2 control-label">Denominacion del rubro: </label>
                <div class="col-sm-10">
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
        <!--Datos de Habilitación y dirección del comercio-->
        <label class="control-label">Cuenta con Comercio de atención. </label> <br>  
        <input type="checkbox" id="checkbox" v-model="Comercio">
        <label for="checkbox">{{ Comercio == true ? "Si" : "No" }}</label>
         <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('facha')&&validarRubro}" v-if="Comercio == true">
                <label for="inputNombre" class="col-sm-2 control-label">Fecha de habilitación</label>
                <div class="col-sm-10"><br/>

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
        
        <div class="col-sm-6" v-if="Comercio == true">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validarRubro}">
                <label for="inputHabilitacion" class="col-sm-2 control-label">Habilitación</label>
                <div class="col-sm-10">
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
           <form-domicilio :domicilio="rubro.domicilio" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-domicilio>
        </div>
    </form>
</template>



<script>
import auth from '../../auth.js';
import vSelect from "vue-select";
import FormDomicilio from './FormDomicilio.vue';

export default {
    props: {
            rubro: {
                type: Object,
                required: true
            },
            validarRubro: {
                value: this.validarRubro,
                type: Boolean,
                required: true
            },
            validarDomicilio: {
                value: this.validarRubro,
                type: Boolean,
                required: true
            },
            errorsApi: {
                type: Object,
                required: true
            }
    },
    data() {
        return {
            validar: false,
            showModificar: false,
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
        },
        //obtiene lista de usuarios segun requiera
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/busqueda/usuarios/?q='+ search
                ).then(response => {
                    this.usuarios = response.data
                    loading(false)
                })
        }
    }
}
</script>