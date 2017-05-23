<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Crea tu Evento</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Iniciar su sesión</p>
            <div class="help-block" v-if="error">
                <p>La convinacion de usuario y contraseña no es correcta.</p>
            </div>
            <form action="#" v-on:submit.prevent autocomplete="on">
                <div class="form-group has-feedback">
                    <input v-model="email" type="email" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <div class="help-block" v-if="errors.email">
                        <div v-for="msj in errors.email">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <input v-model="password" type="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <div class="help-block" v-if="errors.password">
                        <div v-for="msj in errors.password">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7 col-xs-offset-1">
                        <div class="checkbox icheck">
                            <label>
                                <input v-model="remember" type="checkbox"> Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button @click="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>


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
            remember: false,
            error: false,
            errors: []
        }
    },
    methods: {
        clearErrors: function(){
            this.error = false,
            this.errors = []
        },
        login: function() {
            this.clearErrors();
            this.$http.post(
                'api/login',
                {
                    email: this.email,
                    password: this.password,
                    remember: this.remember
                }
            ).then(response => {
  
                auth.user.authenticated = true
                auth.user.profile = response.data.data

                router.push({
                    name: 'home'
                })
            }, response => {
                if(response.body.error) {
                    this.error = true
                } else {
                    this.errors = response.body
                }
            })
        }
    }

}
</script>
