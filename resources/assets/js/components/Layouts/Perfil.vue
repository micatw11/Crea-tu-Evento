<template>
    <div class="content-wrapper">
        <path-content :titleContent="titleContent"></path-content>
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" :src="srcUrl" alt="User profile picture">

                            <h3 v-if="perfil !== null" class="profile-username text-center">{{perfil.apellido}}, {{perfil.nombre}}</h3>

                            <image-input @avatarUpdate="updateAvatar()" v-if="perfil !== null && perfil.user_id == auth.user.profile.id" ></image-input>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <!-- About Me Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">About Me</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

                            <p v-if="perfil !== null" class="text-muted">{{ perfil.localidad.nombre}}, {{perfil.localidad.provincia.nombre}}</p>

                            <hr>

                            <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <!--<li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>-->
                            <!--<li><a href="#timeline" data-toggle="tab">Timeline</a></li>-->
                            <li class="active" v-if="perfil !== null && perfil.user_id == auth.user.profile.id">
                                <a href="#info" data-toggle="tab">Informaci&oacute;n</a>
                            </li>
                            <li v-if="perfil !== null && perfil.user_id == auth.user.profile.id">
                                <a href="#account" data-toggle="tab">Cuenta</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            
                            <!--<div class="active tab-pane" id="activity">
                                <activity></activity>
                            </div>-->

                            <!--<div class="tab-pane" id="timeline">s
                                <time-line></time-line>
                            </div>-->

                            <div v-if="perfil !== null && perfil.user_id == auth.user.profile.id" 
                                class="active tab-pane" id="info">
                                <show>  </show>
                            </div>

                             <div v-if="perfil !== null && perfil.user_id == auth.user.profile.id" 
                                class="tab-pane content " id="account">
                                    <div class="col-sm-4">
                                        <account></account>
                                    </div>
                                    <br><hr>
                                    <div>
                                        <form-perfil 
                                                @reload="reload()">
                                        </form-perfil>
                                    </div>
                                    <br><hr>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn-block" data-toggle="modal" data-target="#myModal">Desactivar</button>
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
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
            </div>
        </section>
    </div>

 
</template>
<script>
import PathContent from './Path.vue';
import FormPerfil from '../Usuarios/EditForm.vue';
import Show from '../Usuarios/Show.vue';
import ImageInput from '../Usuarios/ImageInput.vue';
import Activity from '../Usuarios/Activity.vue';
import Account from '../Usuarios/Account.vue';
import router from '../../routes.js';
import auth from '../../auth.js';

export default {
    data(){
        return {
            titleContent: 'Perfil',
            perfil: null,
            auth: auth,
            form: null,
            avatar: null,
            srcUrl: ''
        }
    },
    beforeMount: function() {
        this.getUserPerfil()

    },
    components: {
        PathContent,
        FormPerfil, 
        Activity, 
        Account,
        ImageInput,
        Show
    },
    methods:{

        getUserPerfil: function(){
            this.$http.get('api/usuario/'+ this.$route.params.userId )
                .then(response => {
                    this.perfil = response.data.data,
                    this.srcUrl = '/storage/avatars/'+this.perfil.avatar
                }, response => {

                    if(response.status === 404){
                        router.push('/404');
                    }

                })
        },
        updateAvatar: function(){
            this.srcUrl = '/storage/avatars/'+this.perfil.avatar;
            this.$emit('reloadComponents');
        },
        reload: function(){
            this.getUserPerfil();
        }
    }
}
</script>