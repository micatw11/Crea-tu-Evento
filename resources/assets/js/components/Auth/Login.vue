<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Arma Tu Evento</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Iniciar su sesión</p>
            <div class="help-block" v-if="error">
                <p>La convinacion de usuario y contraseña no es correcta.</p>
            </div>
            <!-- form login -->
            <div class="form-group has-feedback">
                <input v-model="email" type="email" class="form-control" placeholder="Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input v-model="password" type="password" class="form-control" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-4">
                    <button @click="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
            <!-- form -->


            <a href="#">I forgot my password</a><br>
            <a href="register.html" class="text-center">Register a new membership</a>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</template>

<script>
import auth from '../../auth.js';
import router from '../../routes.js';

export default {
    data() {
        return {
            email: null,
            password: null,
            error: false
        }
    },
    methods: {
        login: function() {
            this.$http.post(
                'api/login',
                {
                    email: this.email,
                    password: this.password,
                    remember: false
                }
            ).then(response => {
  
                auth.user.authenticated = true
                auth.user.profile = response.data.data

                router.push({
                    name: 'home'
                })
            }, response => {
                this.error = true
            })
        }
    }
}
</script>
