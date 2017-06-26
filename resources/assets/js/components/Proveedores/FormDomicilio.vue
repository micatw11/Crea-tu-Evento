<template>
   <form class="form-horizontal">
     <div>
         <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad_id')&&validarDomicilio}">
            <div class="col-sm-10">
            <label class="control-label">Localidad</label><br>
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
                <span v-show="errors.has('localidad_id')&&validarDomicilio" class="help-block">{{ errors.first('localidad_id') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.localidad_id">
                    <div v-for="msj in errorsApi.localidad_id">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarDomicilio}">
            <div class="col-sm-10">
            <label for="inputCalle" class="control-label">Direccion: </label><br>
                <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="calle">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('calle')&&validarDomicilio" class="help-block">{{ errors.first('calle') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.calle">
                    <div v-for="msj in errorsApi.calle">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarDomicilio}">
            <div class="col-sm-6">
                <div class="col-sm-10">
                <label for="inputNro" class="control-label">N°: </label><br>
                     <input name="numero" v-validate="'required'" type="number" v-model="domicilio.numero" value="numero" class="form-control">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="col-sm-10">
                <label for="inputPiso" class="control-label">Dpto.: </label><br>
                     <input name="piso" v-validate="'required'" type="number" v-model="domicilio.piso" value="piso" class="form-control">
                </div>
            </div>
            <!-- validacion vee-validation -->
            <span v-show="errors.has('numero')&&validarDomicilio" class="help-block">{{ errors.first('numero') }}</span>
            <span v-show="errors.has('piso')&&validarDomicilio" class="help-block">{{ errors.first('piso') }}</span>
            <!-- validacion api-->
            <div class="text-red">
                <div v-if="errorsApi.numero" v-for="msj in errorsApi.numero">
                    <p>{{ msj }}</p>
                </div>
                <div v-if="errorsApi.piso" v-for="msj in errorsApi.piso">
                    <p>{{ msj }}</p>
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
                    this.validarDomicilio = false;
                }).catch(() => {
                    this.validarDomicilio = true;
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