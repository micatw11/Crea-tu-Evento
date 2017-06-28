<template>
<div>
    <form class="form-horizontal"><br><br>
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('tipo_rubro')&&validarRubro}">
                <div>
                    <label for="inputTipoRubro" class="control-label">Tipo de Proveedor</label><br>
                    <select 
                        v-model="rubro.tipo_rubro" class="form-control">
                        <option 
                            v-for="option in options" 
                            v-bind:value="option.text" 
                            selected>
                            {{ option.text }}
                        </option>
                    </select>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('tipo_rubro')&&validarRubro" class="help-block">{{ errors.first('tipo_rubro') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.tipo_rubro">
                        <div v-for="msj in errorsApi.tipo_rubro">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarRubro}">
                <div>
                    <label for="inputCategoria" class="control-label">Categoria</label><br>
                     <input name="categoria" v-validate="'required'" type="number" v-model="rubro.categoria_id" value="categoria" class="form-control">
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
                <div>
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
                <div>
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
                <div><br/>
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
          <div>
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validarRubro}">
                <div>
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
            </div>
          <div>
           <form-domicilio :domicilio="domicilio" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-domicilio>
            </div>
        </div>
    </form>
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
                tipo_rubro: null,
                categoria_id: null,
                denominacion: null,
                descripcion: null,
                habilitacion: null,
                fecha_habilitacion: null
            },
            options: [
                          { text: 'Producto' },
                          { text: 'Servicio' },
                          { text: 'Salon' }
                      ],
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
         this.$validator.validateAll().then(() => {
                       this.sendForm();                  
                }).catch(() => {
                    this.validar = true;
                    this.validarRubro = true;
                    this.validarDomicilio = true;
                    this.$events.fire('validarForm')
                });
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