<template>
    <div>
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
                            <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
                            <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
                            <li v-if="perfil !== null && perfil.user_id == auth.user.profile.id"><a href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                        <div class="tab-content">
                            
                            <div class="active tab-pane" id="activity">
                                <activity></activity>
                            </div>

                            <div class="tab-pane" id="timeline">
                                <time-line></time-line>
                            </div>
                             
                            <div class="tab-pane" id="settings" v-if="perfil !== null && perfil.user_id == auth.user.profile.id">
                                <form-perfil @reload="reload()"></form-perfil>
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
import FormPerfil from '../Usuarios/CrudForm.vue';
import ImageInput from '../Usuarios/ImageInput.vue';
import Activity from '../Usuarios/Activity.vue';
import TimeLine from '../Usuarios/TimeLine.vue';
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
        this.getUserPerfil();
        this.srcUrl = '/storage/avatars/'+this.auth.user.profile.usuario.avatar
    },
    components: {
        PathContent,
        FormPerfil, 
        Activity, 
        TimeLine,
        ImageInput
    },
    methods:{
        getUserPerfil: function(){
            this.$http.get('api/usuario/'+ this.$route.params.userId )
                .then(response => {
                    this.perfil = response.data.data
                }, response => {

                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        updateAvatar: function(){
            this.srcUrl = '/storage/avatars/'+this.auth.user.profile.usuario.avatar;
        },
        reload: function(){
            this.getUserPerfil();
        }
    }
}
</script>