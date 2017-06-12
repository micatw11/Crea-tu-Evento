<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>


<template>
    <form @submit.prevent="validateBeforeSubmit" class="form-horizontal">

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('contraseña')}">
            <label for="contraseña" class="col-sm-2 control-label">Contraseña</label>
            <div class="col-sm-10">
                <input name="contraseña" v-model="oldPassword" type="password" v-validate="'required|min:6'" class="form-control" placeholder="Actual Contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                <!-- validacion vee-validation -->
                <span v-show="errors.has('contraseña')" class="help-block">{{ errors.first('contraseña') }}</span>

                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.oldPassword">
                    <div v-for="msj in errorsApi.oldPassword">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nueva')}">
            <label for="nueva" class="col-sm-2 control-label">Nueva</label>
            <div class="col-sm-10">
                <input name="nueva" v-model="password" type="password" v-validate="'required|min:6'" class="form-control" placeholder="Nueva Contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                <!-- validacion vee-validation -->
                <span v-show="errors.has('nueva')" class="help-block">{{ errors.first('nueva') }}</span>

                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.password">
                    <div v-for="msj in errorsApi.password">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('confirmar')}">
            <label for="confirmar" class="col-sm-2 control-label">Confirmar</label>
            <div class="col-sm-10">
                <input name="confirmar" v-model="password_confirmation" type="password" v-validate="'required|confirmed:nueva'" class="form-control" placeholder="Confirmar contraseña">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>

                <!-- validacion vee-validation -->
                <span v-show="errors.has('confirmar')" class="help-block">{{ errors.first('confirmar') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.password_confirmation">
                    <div v-for="msj in errorsApi.password_confirmation">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="desactivar" class="col-sm-3 control-label">Desactivar Cuenta</label>
            <div class="col-sm-9">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Desactivar</button>
            </div>
            <!-- Modal Disable-->
            <div id="myModal" class="modal fade modal-primary" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Desactivar cuenta</h4>
                        </div>
                        <div class="modal-body">
                            <p>Al desactivar tu cuenta, se desactivará tu perfil y se borrará 
                            tu nombre y tu foto de la mayor parte del contenido que compartiste. 
                            Algunas personas podrán seguir viendo determinada información, como 
                            tu nombre en los mensajes que les enviaste.
                            Podras volver a activarla cuando desees introduciendo tu correo y contraseña 
                            desde la pagina de inicio de sesión.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button @click="disableAccount()" type="button" class="btn btn-outline" data-dismiss="modal">Dasactivar</button>
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
    </form>

</template>

<script>
import auth from './../../auth.js'
import router from './../../routes.js'

export default {
    data() {
        return {
            auth: auth,
            errorsApi: [],
            oldPassword: '',
            password_confirmation: '',
            password: ''
        }
    },
    methods: {
        //validacion de formulario de contraseña
        validateBeforeSubmit: function(){
            this.$validator.validateAll().then(() => {
                this.sendChangePaswword();
                }).catch(() => {
                    // failed
                });
        },
        //envia formulario de cambio de contraseña
        sendChangePaswword: function(){
            this.$http.post('api/user/'+ this.$route.params.userId+'/password',
                {
                    _method: 'PATCH',
                    oldPassword: this.oldPassword,
                    password_confirmation: this.password_confirmation,
                    password: this.password
                })
                .then(response => {
                    this.clearErrors();
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se ha cambiado correctamente su contraseña. :D'
                    });
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 401)
                    {
                        //setea errores en validaciones de api
                        this.errorsApi = response.body;
                    }
                })
        },
        disableAccount: function(){
            this.$http.post('api/user/'+ this.$route.params.userId+'/account',
                {
                    _method: 'DELETE',
                    logout: true,

                })
                .then(response => {
                    //redirect to login
                    auth.user.authenticated = false
                    auth.user.profile = null
                    router.push({
                        path: '/login', query: { deactivated: true }
                    })
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido realizar los cambios. :('
                    });

                })
        },
        //limpia formulario
        clearForm: function(){
            this.oldPassword= '',
            this.password_confirmation= '',
            this.password= ''
        },
        //limpia errores de api
        clearErrors: function(){
            this.oldPasswordError= '',
            this.errorsApi= []
        }
    }
}
</script>
