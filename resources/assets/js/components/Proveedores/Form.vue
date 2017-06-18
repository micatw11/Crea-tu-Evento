<template>
    <form class="form-horizontal">
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('user_id')&&validar}">
            <label class="col-sm-2 control-label">Usuario</label>
            <div class="col-sm-10">
                <v-select 
                    :debounce="250"
                    v-validate="'required'" 
                    data-vv-name="usuarios"
                    :on-search="getOptions" 
                    :options="usuarios"
                    placeholder="Seleccione un usuario">
                </v-select>
                <!-- validacion vee-validation -->
                <span v-show="errors.has('user_id')&&validar" class="help-block">{{ errors.first('user_id') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.user_id">
                    <div v-for="msj in errorsApi.user_id">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>
                
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validar}">
            <label for="inputNombre" class="col-sm-2 control-label">Nombre / Razon social: </label>
            <div class="col-sm-10">
                <input name="nombre"  v-validate:proveedor.nombre="'required|min:4'" type="text" class="form-control" v-model="proveedor.nombre" placeholder="Nombre">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('nombre')&&validar" class="help-block">{{ errors.first('nombre') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.nombre">
                    <div v-for="msj in errorsApi.nombre">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('cuit')&&validar}">
            <label for="inputCuit" class="col-sm-2 control-label">N° de Cuit</label>
            <div class="col-sm-10">
                 <input name="cuit" v-validate="'required'" type="number" v-model="proveedor.cuit" value="cuit" class="form-control">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('cuit')&&validar" class="help-block">{{ errors.first('cuit') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.cuit">
                    <div v-for="msj in errorsApi.cuit">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')&&validar}">
            <label for="inputEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input name="email"  v-validate:proveedor.email="'required|email'" type="email" class="form-control" v-model="proveedor.email" placeholder="Email">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('email')&&validar" class="help-block">{{ errors.first('email') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.email">
                    <div v-for="msj in errorsApi.email">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

     <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('proveedor.persona')&&validar}">
            <label for="inputpersona" class="col-sm-2 control-label">Persona</label>
            <div class="col-sm-10">
                 <input name="persona" v-validate="'required'" type="radio" v-model="proveedor.persona" value="Fisica">Fisica</input>           
                 <input name="persona" type="radio" v-model="proveedor.persona" value="Juridica">Juridica</input>
                </div >
                    <!-- validacion vee-validation -->
                <span v-show="errors.has('persona')&&validar" class="help-block">{{ errors.first('persona') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.persona">
                    <div v-for="msj in errorsApi.persona">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

      <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validar}">
            <label for="inputHabilitacion" class="col-sm-2 control-label">N° de Habilitación</label>
            <div class="col-sm-10">
                 <input name="habilitacion" v-validate="'required'" type="number" v-model="proveedor.habilitacion" value="habilitacion">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('habilitacion')&&validar" class="help-block">{{ errors.first('habilitacion') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.habilitacion">
                    <div v-for="msj in errorsApi.habilitacion">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

          <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('ingresos_brutos')&&validar}">
            <label for="inputIngresosBrutos" class="col-sm-2 control-label">N° de Habilitación</label>
            <div class="col-sm-10">
                 <input name="ingresos_brutos" v-validate="'required'" type="number" v-model="proveedor.ingresos_brutos" value="ingresos_brutos">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('ingresos_brutos')&&validar" class="help-block">{{ errors.first('ingresos_brutos') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.ingresos_brutos">
                    <div v-for="msj in errorsApi.ingresos_brutos">
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
            proveedor: { type: Object, default: null},
            error: false,
            errorsApi: [],
            usuarios:[]
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
            this.$http.get('api/busqueda/usuarios/?q='+ search
                ).then(response => {
                    this.usuarios = response.data
                    loading(false)
                })
        }
    }
}
</script>