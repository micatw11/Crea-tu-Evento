<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Crea tu Evento</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Iniciar su sesión</p>
            <div class="text-center" v-if="error">
                <p class="text-red">Estas credenciales no coinciden con nuestros registros.</p>
            </div>
            <form @submit.prevent="validateBeforeSubmit">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('email')}">
                    <input name="email" v-model="email" type="email" v-validate="'required|email'" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.email">
                        <div v-for="msj in errorsApi.email">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>
                </div>
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('password')}">
                    <input name="password" v-model="password" v-validate="'required|min:4'" type="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('password')" class="help-block">{{ errors.first('password') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.password">
                        <div v-for="msj in errorsApi.password">
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
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <router-link tag="a" to="/registrar">¿Se olvido la contraseña?</router-link><br>
            <router-link tag="a" to="/registrar" class="text-center">
                    Registrar una nueva cuenta
              </router-link>

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
            errorsApi: []
        }
    },
    methods: {
        //clear errorsApi
        clearErrors: function(){
            this.error = false,
            this.errorsApi = []
        },
        //send form
        sendForm: function(){
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
                    this.errorsApi = response.body
                }
            })
        },
        //form validation
        validateBeforeSubmit: function(e) {
            this.clearErrors();
            this.$validator.validateAll().then(() => {
                this.sendForm();
            }).catch(() => {
                // failed
            });

        }
    }

}
</script>
