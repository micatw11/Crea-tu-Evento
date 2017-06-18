<template>
    <form class="form-horizontal">
    <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('domicilio.tipo_domicilio')&&validar}">
            <label for="inputTipoDomicilio" class="col-sm-2 control-label">Tipo de Domicilio</label>
            <div class="col-sm-10">
                 <input name="tipo_domicilio" v-validate="'required'" type="radio" v-model="domicilio.tipo_domicilio" value="Real">Real</input>           
                 <input name="tipo_domicilio" type="radio" v-model="domicilio.tipo_domicilio" value="Social">Social</input>
                </div >
                    <!-- validacion vee-validation -->
                <span v-show="errors.has('tipo_domicilio')&&validar" class="help-block">{{ errors.first('tipo_domicilio') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.tipo_domicilio">
                    <div v-for="msj in errorsApi.tipo_domicilio">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        
                
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('domicilio.calle')&&validar}">
            <label for="inputCalle" class="col-sm-2 control-label">Direccion: </label>
            <div class="col-sm-10">
                <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="calle">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('calle')&&validar" class="help-block">{{ errors.first('calle') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.calle">
                    <div v-for="msj in errorsApi.calle">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('domicilio.numero')&&validar}">
            <label for="inputNro" class="col-sm-2 control-label">N°: </label>
            <div class="col-sm-10">
                 <input name="numero" v-validate="'required'" type="number" v-model="domicilio.numero" value="numero" class="col-sm-2 control-label">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('numero')&&validar" class="help-block">{{ errors.first('numero') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.numero">
                    <div v-for="msj in errorsApi.numero">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('domicilio.piso')&&validar}">
            <label for="inputPiso" class="col-sm-2 control-label">Piso: </label>
            <div class="col-sm-10">
                 <input name="piso" v-validate="'required'" type="number" v-model="domicilio.piso" value="piso" class="col-sm-2 control-label">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('piso')&&validar" class="help-block">{{ errors.first('piso') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.piso">
                    <div v-for="msj in errorsApi.piso">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('domicilio.codigo_postal')&&validar}">
            <label for="inputCodigoPostal" class="col-sm-2 control-label">Codigo Postal: </label>
            <div class="col-sm-10">
                 <input name="codigo_postal" v-validate="'required'" type="number" v-model="domicilio.codigo_postal" value="codigo_postal" class="col-sm-2 control-label">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('codigo_postal')&&validar" class="help-block">{{ errors.first('codigo_postal') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.codigo_postal">
                    <div v-for="msj in errorsApi.codigo_postal">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad_id')&&validar}">
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
                <span v-show="errors.has('localidad_id')&&validar" class="help-block">{{ errors.first('localidad_id') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.localidad_id">
                    <div v-for="msj in errorsApi.localidad_id">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
      
            <div class="col-sm-offset-2 col-sm-10">
              
                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                    Guargar
                </button>
            </div>
        </div>
    </form>
</template>



<script>
import auth from '../../auth.js';
import vSelect from "vue-select";

export default {
    data() {
        return {
            validar: false,
            showModificar: false,
            domicilio: { type: Object, default: null},
            error: false,
            errorsApi: [],
            localidades: []
        }
    },
    components: {
        vSelect
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(() => {
                    this.sendForm();
                }).catch(() => {
                    this.validar = true;
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