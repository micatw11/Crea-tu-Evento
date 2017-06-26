<template>
      <form class="form-horizontal">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descripcion')&&validarSalon}">
                <label for="inputDescripcion" class="col-sm-2 control-label">Descripcion del salón: </label>
                <div class="col-sm-10">
                    <input name="descripcion"  v-validate:salon.descripcion="'required|min:4'" type="text" class="form-control" v-model="salon.descripcion" placeholder="Nombre">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('descripcion')&&validarSalon" class="help-block">{{ errors.first('descripcion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.descripcion">
                        <div v-for="msj in errorsApi.descripcion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
             <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('facha')&&validarSalon}">
                <label for="inputNombre" class="col-sm-2 control-label">Fecha de habilitación</label>
                <div class="col-sm-10"><br/>

                    <input 
                        v-model="salon.fecha_habilitacion" 
                        type="date" 
                        v-validate="'required'"
                        name="facha"
                        v-validate:salon.fecha_habilitacion="'required'"
                        >
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('facha')&&validarSalon" class="help-block">{{ errors.first('facha') }}</span>
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
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validarSalon}">
                <label for="inputHabilitacion" class="col-sm-2 control-label">Habilitación</label>
                <div class="col-sm-10">
                     <input name="habilitacion" v-validate="'required'" type="number" v-model="salon.habilitacion" value="habilitacion" class="form-control">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('habilitacion')&&validarSalon" class="help-block">{{ errors.first('habilitacion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.habilitacion">
                        <div v-for="msj in errorsApi.habilitacion">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
           <form-domicilio :domicilio="salon.domicilio" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-domicilio>
        </div>
    </form>
</template>



<script>
import auth from '../../auth.js';
import vSelect from "vue-select";
import FormDomicilio from './FormDomicilio.vue';

export default {
    props: {
            salon: {
                type: Object,
                required: true
            },
            validarSalon: {
                value: this.validarSalon,
                type: Boolean,
                required: true
            },
            validarDomicilio: {
                value: this.validarDomicilio,
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
            error: false
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
                    this.validarSalon = false;
                    //this.$events.fire('validarForm');
                }).catch(() => {
                    this.validarSalon = true;
                });
        }
    }
}
</script>