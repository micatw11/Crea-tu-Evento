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
                        name= "user_id"
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
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('ingresos_brutos')&&validar}">
                <label for="inputIngresosBrutos" class="col-sm-2 control-label">N° Ingresos Brutos</label>
                    <div class="col-sm-10">
                     <input name="ingresos_brutos" v-validate="'required'" type="number" v-model="proveedor.ingresos_brutos" class="form-control" value="ingresos_brutos">
                </div>
                <!-- validacion vee-validation -->
                <div>
                    <span v-show="errors.has('ingresos_brutos')&&validar" class="help-block">{{ errors.first('ingresos_brutos') }}</span>
                </div>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.ingresos_brutos">
                    <div v-for="msj in errorsApi.ingresos_brutos">
                        <p>{{ msj }}</p>
                    </div>
                </div>
                
            </div>
           <form-domicilio :domicilio="domicilio" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-domicilio>
        </div>
        <div class="col-sm-12">
                <div class="form-group has-feedback">
                    <label for="tipoProveedor" class="col-sm-2 control-label">Tipo Proveedor</label>
                    <div class="col-sm-10">
                        <input v-model="tipoProveedor" name="tipoProveedor" id="rubro" type="radio" value="rubro">Rubro<br/>
                        <input v-model="tipoProveedor" name="tipoProveedor" id="salon" type="radio" value="salon">Salón<br/>
                    </div>
                </div>
            
                <div v-if="tipoProveedor == 'rubro'">
                     <label for="tipoProveedor" class="control-label">Complete los datos del {{tipoProveedor}}</label><br><br>
                    <form-rubro :rubro="rubro" :validarRubro="validarRubro" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-rubro>
                </div>

                <div v-if="tipoProveedor == 'salon'">salon
                    <label for="tipoProveedor" class="control-label">Complete los datos del {{tipoProveedor}}</label><br><br>
                    <form-rubro :rubro="rubro" :validarRubro="validarRubro" :validarDomicilio="validarDomicilio" :errorsApi="errorsApi"></form-rubro>
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
import FormRubro from './FormRubro.vue';


export default {
    data() {
        return {
            tipoProveedor: null,
            showModificar: false,
            usuarios:[],
            validar: false,
            validarDomicilio: false,
            validarRubro: false,
            showModificar: false,
            proveedor: { 
                user_id: null,
                nombre: null,
                cuit: null,
                ingresos_brutos: null,
                email: null
            },
            domicilio: {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            },
            rubro:{
                categoria_id: null,
                denominacion: null,
                habilitacion: null,
                fecha_habilitacion: null,
                domicilio: {
                    calle: null,
                    numero: null,
                    piso: null,
                    localidad_id: null
                },
            },
            salon:{
                denominacion: null, 
                codigo: null, 
                habilitacion: null,
                fecha_habilitacion: null,
                domicilio: {
                    calle: null,
                    numero: null,
                    piso: null,
                    localidad_id: null
                },
            },
            error: false,
            errorsApi: {}
        }
    },
    components: {
        vSelect, FormDomicilio, FormRubro
    },
    methods: {
       //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
             console.log('send', this.rubro)
            this.$http.post(
                'api/proveedor', 
                {
                    user_id: this.proveedor.user_id.value,
                    nombre: this.proveedor.nombre,
                    cuit: this.proveedor.cuit,
                    ingresos_brutos: this.proveedor.ingresos_brutos,
                    email: this.proveedor.email,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: this.domicilio.localidad_id.value,
                    rubro_categoria_id: this.rubro.categoria_id,
                    rubro_denominacion: this.rubro.denominacion,
                    rubro_habilitacion: this.rubro.habilitacion,
                    rubro_fecha_habilitacion: this.rubro.fecha_habilitacion,
                    rubro_domicilio:  {calle: this.rubro.domicilio.calle,
                            numero: this.rubro.domicilio.numero,
                            piso: this.rubro.domicilio.piso, 
                            localidad_id: this.rubro.domicilio.localidad_id.value}
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
        validateBeforeSubmit: function() {
                this.$validator.validateAll().then(() => {
                       this.sendForm();                  
                }).catch(() => {
                    this.validar = true;
                    this.validarDomicilio = true;
                    this.validarRubro = true;
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
                ingresos_brutos: null,
                email: null
            },
            this.domicilio= {
                habilitacion: null,
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            },
            this.rubro={
                categoria_id: null,
                denominacion: null,
                habilitacion: null,
                fecha_habilitacion: null
            }
        }
    }
}
</script>