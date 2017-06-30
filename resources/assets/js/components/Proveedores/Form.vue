<template>
    <div><br/>
        <form role="form">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('usuarios')&&validar}">
                    <div class="col-sm-12">
                        <label class="control-label">Usuario</label><br>
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
                        <span v-show="errors.has('usuarios')&&validar" class="help-block">{{ errors.first('usuarios') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.user_id">
                            <div v-for="msj in errorsApi.user_id">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validar}">
                    <div class="col-sm-12">
                        <label for="inputNombre" class="control-label">Nombre Razón social</label><br>
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
                    <div class="col-sm-12">
                        <label for="inputCuit" class="control-label">N° de Cuit</label><br>
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
                    <div class="col-sm-12">
                        <label for="inputEmail" class="control-label">Email</label><br>
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

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('dni')&&validar}">
                    <div class="col-sm-12">
                        <label for="inputDni" class="control-label">DNI <i class="fa fa-file-image-o"></i></label><br>
                        <input 
                            type="file" 
                            v-validate.reject="'required|ext:jpg,png,jpeg,pdf'" 
                            @change="onFileChange" 
                            name="dni">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('dni')&&validar" class="help-block">{{ errors.first('dni') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.dni">
                            <div v-for="msj in errorsApi.dni">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>

            <div class="col-sm-6">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('ingresos_brutos')&&validar}">
                    <div class="col-sm-12">
                        <label for="inputIngresosBrutos" class="control-label">N° Ingresos Brutos</label><br>
                        <input 
                            name="ingresos_brutos" 
                            v-validate="'required'" 
                            type="number" 
                            v-model="proveedor.ingresos_brutos" 
                            class="form-control" 
                            value="ingresos_brutos"
                            min="1">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('ingresos_brutos')&&validar" class="help-block">
                                {{ errors.first('ingresos_brutos') }}
                        </span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.ingresos_brutos">
                            <div v-for="msj in errorsApi.ingresos_brutos">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>                    
                </div>
               <form-domicilio 
                    :domicilio="domicilio" 
                    :validarDomicilio="validarDomicilio" 
                    :errorsApi.sync="errorsApi"
                    @update:validarDomicilio='validarEstadoDomicilio(val)'>
                </form-domicilio>
            </div>
        </div>
     </form>
     <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
        <button @click="goBack()" class="btn btn-default">
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
import route from '../../routes.js';
import vSelect from "vue-select";
import FormDomicilio from './FormDomicilio.vue';

export default {
    data() {
        return {
            showModificar: false,
            usuarios:[],
            validar: false,
            validarDomicilio: false,
            showModificar: false,
            proveedor: { 
                user_id: null,
                nombre: null,
                cuit: null,
                ingresos_brutos: null,
                email: null,
                dni: null
            },
            domicilio: {
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
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
             console.log('send', this.rubro)
            this.$http.post(
                'api/proveedor', 
                {
                    user_id: this.proveedor.user_id.value,
                    nombre: this.proveedor.nombre,
                    cuit: this.proveedor.cuit,
                    ingresos_brutos: this.proveedor.ingresos_brutos,
                    email: this.proveedor.email,
                    dni: this.proveedor.dni,
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
                    this.$events.fire('validarForm')
                });
        },
        validarEstadoDomicilio: function(val){
            this.validarDomicilio = val
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

        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            var dni = new Image();
            var reader = new FileReader();

            reader.onload = (e) => {
                this.proveedor.dni = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        resetForm() {
            this.proveedor = {
                auser_id: null,
                nombre: null,
                cuit: null,
                ingresos_brutos: null,
                email: null,
                dni: null
            },
            this.domicilio= {
                habilitacion: null,
                calle: null,
                numero: null,
                piso: null,
                localidad_id: null
            }
        },
        goBack(){
            route.go(-1)
        }
    }
}
</script>