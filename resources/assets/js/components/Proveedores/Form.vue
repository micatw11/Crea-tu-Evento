<template>
<div>
      <form class="form-horizontal">
         <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('user_id')&&validar}">
                <label class="col-sm-2 control-label">Usuario</label>
                <div class="col-sm-10">
                    <v-select 
                        :debounce="250"
                        v-validate="'required'" 
                        v-model="proveedor.user_id"
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
                <label for="inputNombre" class="col-sm-2 control-label">Nombre Razón social</label>
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
                     <input name="cuit" v-validate="'required|min:9|max:11'" type="number" v-model="proveedor.cuit" value="cuit" class="form-control">
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
                    <input name="email"  v-validate="'required|email'" type="email" class="form-control" v-model="proveedor.email" placeholder="Email">
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
        </div>
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')||('ingresos_brutos')&&validar}">
                <div class="col-sm-6">
                    <label for="inputHabilitacion" class="control-label">N° Habilitación</label>
                     <input name="habilitación" v-validate="'required'" type="number" v-model="proveedor.habilitacion" class="form-control" value="habilitacion">
                </div>
                <div class="col-sm-6">
                    <label for="inputIngresosBrutos" class="control-label">N° Ingresos Brutos</label>
                     <input name="ingresos brutos" v-validate="'required'" type="number" v-model="proveedor.ingresos_brutos" class="form-control" value="ingresos_brutos">
                </div>
                <!-- validacion vee-validation -->
                <div class="col-sm-6">
                    <span v-show="errors.has('habilitacion')&&validar" class="help-block">{{ errors.first('habilitacion') }}</span>
                </div>
                <div class="col-sm-6">
                    <span v-show="errors.has('ingresos_brutos')&&validar" class="help-block">{{ errors.first('ingresos_brutos') }}</span>
                </div>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.ingresos_brutos">
                    <div v-if="errorsApi.ingresos_brutos" v-for="msj in errorsApi.ingresos_brutos">
                        <p>{{ msj }}</p>
                    </div>
                    <div v-if="errorsApi.habilitacion" v-for="msj in errorsApi.habilitacion">
                            <p>{{ msj }}</p>
                    </div>
                </div>
                
            </div>
           <form-domicilio :domicilio="domicilio" :validarDom="validarDom" :errorsApi="errorsApi"></form-domicilio>
        </div>
    
     </form>
     <div class="box-footer clearfix">
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
            showModificar: false,
            usuarios:[],
            validar: false,
            validarDom: false,
            showModificar: false,
            proveedor: { 
                user_id: null,
                nombre: null,
                cuit: null,
                habilitacion: null,
                ingresos_brutos: null,
                persona: null,
                email: null
            },
            domicilio: {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null,
                codigo_postal: null
            },
            error: false,
            errorsApi: {}
        }
    },
    components: {
        vSelect, FormDomicilio
    },
    methods: {
       //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
             console.log('send')
            this.$http.post(
                'api/proveedor', 
                {
                    user_id: this.proveedor.user_id.value,
                    nombre: this.proveedor.nombre,
                    cuit: this.proveedor.cuit,
                    habilitacion: this.proveedor.habilitacion,
                    ingresos_brutos: this.proveedor.ingresos_brutos,
                    email: this.proveedor.email,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: this.domicilio.localidad_id.value,
                    codigo_postal: this.domicilio.codigo_postal
                })
                .then(response => {
                    //recarga de informacion de perfil
                    this.$emit('reload')
                    this.showModificar = false;
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.resetForm();
                }, response => {
                    this.validar= false;
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
        validateBeforeSubmit: function() {
                this.$validator.validateAll().then(() => {
                       this.sendForm();                  
                }).catch(() => {
                    this.validar = true;
                    this.validarDom = true;
                    this.$events.fire('validarForm')
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
        },
        resetForm() {
            this.proveedor = {
                auser_id: null,
                nombre: null,
                cuit: null,
                habilitacion: null,
                ingresos_brutos: null,
                persona: null,
                email: null
            },
            this.domicilio= {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null,
                codigo_postal: null,
            }
        }
    }
}
</script>