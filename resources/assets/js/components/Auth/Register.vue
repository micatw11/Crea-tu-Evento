<template>
    <div class="register-box container row">
      <div class="register-box-body">
          <h3 class="login-box-msg">Registrar una nueva cuenta</h3><br>
          <div class="help-block" v-if="error">
                <p>Error al registrar</p> 
           </div>

          <div class="form-group has-feedback">
            <input v-model="name" type="text" class="form-control" placeholder="Ingresar nombre de usuario">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input v-model="email" type="email" class="form-control" placeholder="Ingresar Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input v-model="password" type="password" class="form-control" placeholder="Ingresar Contraseña">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input v-model="password_confirmation" type="password" class="form-control" placeholder="Confirmar contraseña">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          
          <div class="form-group has-feedback text-center row">
                <p>
                  Al crear una cuenta acepta el <a href="#">Contrato de servicios de Eventos.</a>
                </p>
                <br>
                <!-- /.col -->
                <div class="col-xs-12">
                  <button @click="registrar" class="btn btn-primary btn-flat">Crear cuenta</button>
                </div>
                <!-- /.col -->
        </div>
        <div class="social-auth-links text-center">
          <p>- O -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Iniciar sesión con Facebook
            Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Iniciar sesión con
            Google+</a>
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


export default {
    
    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            error: false
        }
    },
    methods: {
        registrar: function() {

            this.$http.post(
                'api/register',
                {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    remember: false
                }
            ).then(response => {
  
                auth.user.authenticated = true
                auth.user.profile = response.data

                router.push({
                    name: 'home'
                })
            }, response => {
                this.error = true
            })
        }
    }
};


</script>


