<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" :src="srcUrl" alt="User profile picture">

                            <h3 v-if="perfil !== null" class="profile-username">
                                {{perfil.nombre}} {{perfil.apellido}}
                            </h3>

                            <image-input 
                                @avatarUpdate="updateAvatar()" 
                                v-if="perfil !== null && perfil.user_id == auth.user.profile.id">
                            </image-input>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <!-- About Me Box -->
                    <div class="box box-primary" >
                        <div class="box-header with-border">
                            <h3 class="box-title">Informaci&oacute;n</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <strong><i class="fa fa-map-marker margin-r-5"></i> Locati&oacute;n</strong>

                            <p v-if="perfil !== null" class="text-muted">{{ perfil.localidad.nombre}}, {{perfil.localidad.provincia.nombre}}</p>

                            <div 
                                v-if="perfil !== null && (perfil.user.roles_id == role.ADMINISTRADOR ||
                                perfil.user.roles_id == role.SUPERVISOR) && 
                                perfil.user_id != auth.user.profile.id && auth.user.profile.roles_id == role.ADMINISTRADOR">
                                <hr>

                                <strong><i class="fa fa-users margin-r-5"></i> Rol </strong>
                                <select 
                                    v-model="perfil.user.roles_id" 
                                    @change="changeItemRol()">
                                    <option 
                                        v-for="option in options" 
                                        v-bind:value="option.value" 
                                        selected>
                                        {{ option.text }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>

                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <div class="col-md-9">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cuenta</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="box-body" style="display: block;">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <!--<li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>-->
                                    <li class="active" v-if="perfil !== null && perfil.user_id == auth.user.profile.id">
                                        <a href="#info" data-toggle="tab">Informaci&oacute;n</a>
                                    </li>
                                    <li v-if="perfil !== null && perfil.user_id == auth.user.profile.id">
                                        <a href="#account" data-toggle="tab">Configuración</a>
                                    </li>
                                    <li v-if="perfil !== null && 
                                        (perfil.user_id == auth.user.profile.id 
                                        || auth.user.profile.roles_id === role.ADMINISTRADOR)">
                                        <a href="#timeline" data-toggle="tab">Actividades</a>
                                    </li>
                                </ul>
                            

                                <div class="tab-content">
                                    
                                    <!--<div class="active tab-pane" id="activity">
                                        <activity></activity>
                                    </div>-->

                                    <div v-if="perfil !== null && perfil.user_id == auth.user.profile.id" 
                                        class="active tab-pane" id="info">
                                        <show></show>
                                    </div>

                                    <div v-bind:class="classTabActive" id="timeline">
                                        <time-line></time-line>
                                    </div>

                                     <div v-if="perfil !== null && perfil.user_id == auth.user.profile.id" 
                                        class="tab-pane content" 
                                        id="account">
                                             <div>
                                                <form-perfil 
                                                        @reload="reload()">
                                                </form-perfil>
                                            </div>
                                            <br><hr>
                                            <div>
                                                <account></account>
                                            </div>
                                     </div>
                                    <!-- /.tab-pane -->
                                </div>
                            </div>
                        <!-- /.tab-content -->
                        </div>
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <box-proveedor v-if="perfil !== null && (perfil.user.roles_id == 4)" :perfil="perfil"></box-proveedor>
            </div>
        </section>
    </div>
</template>

<script>

import FormPerfil from '../Usuarios/EditForm.vue';
import Show from '../Usuarios/Show.vue';
import ImageInput from '../Usuarios/ImageInput.vue';
import Activity from '../Usuarios/Activity.vue';
import TimeLine from './TimeLine.vue';
import Account from '../Usuarios/Account.vue';
import router from '../../routes.js';
import auth from '../../auth.js';
import Role from '../../config.js';
import BoxProveedor from '../Proveedores/PerfilProveedor.vue';

export default {
    data(){
        return {
            titleContent: 'Perfil',
            perfil: null,
            auth: auth,
            form: null,
            avatar: null,
            srcUrl: '',
            role: Role,
            options: [
                      { text: 'Supervisor', value: '2' },
                      { text: 'Operador', value: '3' },
                      { text: 'Usuario', value: '5' }
                  ],
        }
    },
    beforeMount: function() {
        this.getUserPerfil()

    },
    mounted: function(){
        this.$events.$on('reloadComponentPerfil', () => this.getUserPerfil());
    },
    components: {
        FormPerfil, 
        Activity, 
        Account,
        ImageInput,
        TimeLine,
        Show,
        BoxProveedor
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
            this.getUserPerfil();
            this.$events.fire('reloadComponents');
        },
        reload: function(){
            this.getUserPerfil();
        },
        //cambiar rol
        changeItemRol() {
            //this.selected = `${event.target.value}`
            this.$http.post('api/user/'+ this.$route.params.userId +'/rol',
            {
                _method: 'PATCH',
                roles_id: this.perfil.user.roles_id
            })
            .then(response => {
                this.$toast.success({
                    title:'¡Cambios realizados!',
                    message:'Se ha cambiado correctamente el rol.'
                });
            }, response => {
                this.$toast.error({
                    title:'¡Error!',
                    message:'No se han podido guardar los cambios.'
                });
            })
           
        }
    },
    watch: {
        '$route.params.userId' (){
            this.getUserPerfil();
        }
    },
    computed: {
        classTabActive: function () {
            if(this.perfil!=null)
                return {
                    'active tab-pane': this.auth.user.profile.id !== this.perfil.user_id,
                    'tab-pane': this.auth.user.profile.id === this.perfil.user_id,
                }
                else
                    return 'tab-pane';
        },
    }
}
</script>
<style>
    .nav-tabs-custom > .tab-content {
        min-height: 250px;
    }
    .profile-username {
        margin-left: 23px;
    }
</style>