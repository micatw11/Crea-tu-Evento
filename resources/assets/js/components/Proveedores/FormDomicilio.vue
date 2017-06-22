<template>
   <form class="form-horizontal">
     <div>
         <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad_id')&&validarDom}">
            <label class="col-sm-2 control-label">Localidad</label>
            <div class="col-sm-10">
                   <v-select
                        :debounce="250" 
                        :on-search="getOptions" 
                        :options="localidades"
                        data-vv-name="localidad"
                        v-model="domicilio.localidad_id" 
                        v-validate="'required'" 
                        placeholder="Seleccione una localidad">
                    </v-select>
               
                <!-- validacion vee-validation -->
                <span v-show="errors.has('localidad_id')&&validarDom" class="help-block">{{ errors.first('localidad_id') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.localidad_id">
                    <div v-for="msj in errorsApi.localidad_id">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarDom}">
            <label for="inputCalle" class="col-sm-2 control-label">Direccion: </label>
            <div class="col-sm-10">
                <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="calle">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('calle')&&validarDom" class="help-block">{{ errors.first('calle') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.calle">
                    <div v-for="msj in errorsApi.calle">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('codigo_postal')||('numero')||('piso'))&&validarDom}">
            <div class="col-sm-12">
                <div class="col-sm-4">
                    <label for="inputNro" class="control-label">N°: </label>
                    <div>
                         <input name="numero" v-validate="'required'" type="number" v-model="domicilio.numero" value="numero" class="form-control">
                    </div>
                </div>

                <div class="col-sm-4">
                    <label for="inputPiso" class="control-label">Piso: </label>
                    <div>
                         <input name="piso" v-validate="'required'" type="number" v-model="domicilio.piso" value="piso" class="form-control">
                    </div>
                </div>

                <div class="col-sm-4">
                    <label for="inputCodigoPostal" class="control-label">C. Postal: </label>
                    
                         <input name="codigo_postal" v-validate="'required'" type="number" v-model="domicilio.codigo_postal" value="codigo_postal" class="form-control">
                   
                </div>
                <!-- validacion vee-validation -->
                <span v-show="errors.has('numero')&&validarDom" class="help-block">{{ errors.first('numero') }}</span>
                <span v-show="errors.has('codigo_postal')&&validarDom" class="help-block">{{ errors.first('codigo_postal') }}</span>
                <span v-show="errors.has('piso')&&validarDom" class="help-block">{{ errors.first('piso') }}</span>
                <!-- validacion api-->
                <div class="text-red">
                    <div v-if="errorsApi.numero" v-for="msj in errorsApi.numero">
                        <p>{{ msj }}</p>
                    </div>
                    <div v-if="errorsApi.piso" v-for="msj in errorsApi.piso">
                        <p>{{ msj }}</p>
                    </div>
                    <div v-if="errorsApi.codigo_postal" v-for="msj in errorsApi.codigo_postal">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </form>
</template>



<script>
import auth from '../../auth.js';
import vSelect from "vue-select";

export default {
     props: {
            domicilio: {
                type: Object,
                required: true
            },
            validarDom: {
                value: this.validarDom,
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
            showModificar: false,
            error: false,
            localidades: []
        }
    },
    components: {
        vSelect
    },
    mounted() {
            this.$events.$on('validarForm', () =>this.validateBeforeSubmit());
        },
    methods: {
         validateBeforeSubmit: function() {
            console.log('domicilio');
            this.$validator.validateAll().then(() => {
                    this.validarDom = false;
                }).catch(() => {
                    this.validarDom = true;
                });
        },
        //obtiene lista de usuarios segun requiera
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        }
    }
}
</script>