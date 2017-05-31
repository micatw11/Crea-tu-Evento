<template>
    <div class="register-box container row">
        <div class="register-box-body">
            <h3 class="login-box-msg">Registrar una nueva cuenta</h3><br>
            <div class="help-block" v-if="error">
                <p class="text-red">Error al registrar</p> 
            </div>
            <form @submit.prevent="validateBeforeSubmit">

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('apellido')}">
                    <input name="apellido" v-model="usuario.apellido" v-validate:apelido="'required|min:4|max:55'"type="text" class="form-control" placeholder="Ingresar apellido">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('apellido')" class="help-block">{{ errors.first('apellido') }}</span>
                 </div>
                 <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')}">
                    <input name="nombre" v-model="usuario.nombre" v-validate:nombre="'required|min:4|max:55'"type="text" class="form-control" placeholder="Ingresar nombre">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')" class="help-block">{{ errors.first('nombre') }}</span>
                    <div class="help-block" v-if="errorsApi.name">
                        <div v-for="msj in errorsApi.name">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                 </div>
  

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')}">
                    <input name="email" v-model="email" v-validate:email="'required|email'" type="email" class="form-control" placeholder="Ingresar Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}</span>
                    <!-- validacion api-->
                    <div class="help-block" v-if="errorsApi.email">
                        <div v-for="msj in errorsApi.email">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('password')}">
                    <input name="password" v-model="password" type="password" v-validate:password="'required|min:6'" class="form-control" placeholder="Ingresar Contraseña">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('password')" class="help-block">{{ errors.first('password') }}</span>
                    <!-- validacion api-->
                    <div class="help-block" v-if="errorsApi.password">
                        <div v-for="msj in errorsApi.password">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('confirmation')}">
                    <input name="confirmation" v-model="password_confirmation" type="password" v-validate:confirmation="'required|confirmed:password'" class="form-control" placeholder="Confirmar contraseña">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('confirmation')" class="help-block">{{ errors.first('confirmation') }}</span>
                    <!-- validacion api-->
                    <div class="help-block" v-if="errorsApi.password_confirmation">
                        <div v-for="msj in errorsApi.password_confirmation">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>

                
                 <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha_nac')}">
                    <input name="fecha_nac" v-model="usuario.fecha_nac" type="date" class="form-control" placeholder="Ingresar fecha de nacimiento">
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('fecha_nac')" class="help-block">{{ errors.first('fecha_nac') }}</span>
                 </div>

                <v-select
                    :debounce="250" 
                    :on-change="changeSelect"
                    :on-search="getOptions" 
                    :options="localidades"
                    placeholder="Seleccione una localidad">
                </v-select>

                  <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('sexo')}">
                    <label for="inputSexo" class="col-sm-2 control-label">Sexo</label>
                    <div class="col-sm-10">
                        <input type="radio" v-model="usuario.sexo" value="M">Masculino</input><br>
                        <input type="radio" v-model="usuario.sexo" value="F">Femenino</input>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="form-group has-feedback text-center row">
                    <p>
                        Al crear una cuenta acepta el <a href="#">Contrato de servicios de Eventos.</a>
                    </p>
                    <br>
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-flat">Crear cuenta</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="social-auth-links text-center">
                <p>- O -</p>
            </div>
            ¿Ya tienes una cuenta?<router-link tag="a" to="/login" class="text-center"> Iniciar sesión</router-link>
      </div>
      <!-- /.form-box -->
    </div>
    <!-- /.register-box -->

</template>

<script>
import auth from '../../auth.js';
import router from '../../routes.js';
import vSelect from "vue-select";

export default {
    
    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            error: false,
            errorsApi: [],
            localidades: [],
            usuario: {
                localidad_id: null,
                nombre: null,
                apellido: null,
                sexo: null,
                fecha_nac: null
            }
        }
    },
    components: {vSelect},
    methods: {
        //clear errorsApi
        clearErrors: function(){
            this.error = false,
            this.errorsApi = []     
        },
        //send form
        register: function(){
            this.$http.post(
                'api/register',
                {
                    name: this.usuario.nombre,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    remember: false,
                    nombre: this.usuario.nombre,
                    apellido: this.usuario.apellido,
                    sexo: this.usuario.sexo,
                    fecha_nac: this.usuario.fecha_nac,
                    localidad_id: this.usuario.localidad_id
                }
            ).then(response => {
  
                auth.user.authenticated = true
                auth.user.profile = response.body.data

                router.push({
                    name: 'home'
                })
            }, response => {
                    console.log(response);
                    this.error = true //error de 
                    this.errorsApi = response.body//lista de errores
            })
        },
        validateBeforeSubmit: function(e) {
            this.clearErrors();
            this.$validator.validateAll().then(() => {
                this.register();
                this.usuarioCreate();
            }).catch(() => {
                // failed
            });

        },
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        },
        changeSelect: function(val){
            this.usuario.localidad_id = val.value
        }
    }
};


</script>


