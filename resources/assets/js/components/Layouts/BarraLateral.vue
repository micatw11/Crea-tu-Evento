<style type="text/css">
    .main-sidebar{
        height: 100%;
    }
</style>

<template>
    <!-- Left side column. conta ins the logo and sidebar -->
    <aside class="main-sidebar" >
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel" v-if="auth.user.authenticated">
                <div class="pull-left image">
                    <img v-bind:src="srcUrl" class="img-circle" alt="Avatar">
                </div>
                <div class="pull-left info">
                    <p v-if="auth.user.authenticated">{{ auth.user.profile.name}}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <form v-on:submit.prevent="searchPublicacion()" class="sidebar-form" :style="{ display : showFormSearch  ? 'block' : 'none' }">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" v-model="q" placeholder="Busqueda">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MENU</li>

                <!--  Administracion -->
                <div v-if="auth.user.authenticated">
                <li class="treeview" 
                    v-if="auth.user.profile.roles_id == role.ADMINISTRADOR || 
                        auth.user.profile.roles_id == role.SUPERVISOR ||
                        auth.user.profile.roles_id == role.OPERADOR">
                    
                    <a href="#">
                        <i class="fa fa-dashboard"></i>
                        <span>Administrar</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">

                        <router-link 
                            v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                            auth.user.profile.roles_id == role.SUPERVISOR"
                            tag="li" 
                            to="/usuario">
                            <a>
                                <i class="fa fa-th-list"></i> Usuarios
                            </a>
                        </router-link>
                        <router-link
                            v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                auth.user.profile.roles_id == role.SUPERVISOR ||
                                auth.user.profile.roles_id == role.OPERADOR" 
                                tag="li"
                                to="/proveedores">
                                <a>
                                    <i class="fa fa-th-list"></i> Proveedores
                                </a>
                        </router-link>
                        <router-link
                            v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                auth.user.profile.roles_id == role.SUPERVISOR"
                                tag="li"
                                to="/categorias">
                                    <a>
                                        <i class="fa fa-th-list"></i> <span> Categorias</span>
                                    </a>
                        </router-link>

                        <router-link
                            v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                                auth.user.profile.roles_id == role.SUPERVISOR"
                                tag="li"
                                to="/caracteristicas">
                                    <a>
                                        <i class="fa fa-th-list"></i> <span> Caracteristicas de Rubros</span>
                                    </a>
                        </router-link>
                    </ul>
                </li>

                <!-- Proveedores-->
                <li class="treeview" v-if="auth.user.profile.roles_id == role.PROVEEDOR">
                    <a href="#" v-if="auth.user.profile.roles_id == role.PROVEEDOR">
                        <i class="fa fa-dashboard"></i>
                        <span>Agregar</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" v-if="auth.user.profile.roles_id == role.PROVEEDOR">
                        <li>
                            <a v-if="auth.user.profile.roles_id == role.PROVEEDOR" @click="goToNewPublicacion()">
                                <i class="fa fa-plus"></i> <span> Publicaci&oacute;n</span>
                            </a>
                        </li>
                        <li>
                            <a v-if="auth.user.profile.roles_id == role.PROVEEDOR" @click="goToNewRubro()">
                                <i class="fa fa-plus"></i> <span> Rubro</span>
                            </a>
                        </li>
                    </ul>
                </li>
                </div>
                <!--
                <li class="treeview">
                    <a href="/calendario">
                        <i class="fa fa-calendar"></i> <span>Calendar</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-red">3</small>
                        <small class="label pull-right bg-blue">17</small>
                        </span>
                    </a>
                </li>
                -->
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

</template>
<script>
import auth from '../../auth.js';
import route from '../../routes.js';
import Role from '../../config.js';

export default {
    data() {
        return {
            auth: auth,
            srcUrl: '',
            role: Role,
            q: ''
        }
    },
    mounted: function(){
        if(auth.user.authenticated){
            this.avatarUpdated();
        }
    },
    methods: {
        avatarUpdated: function(){
            this.srcUrl = '/storage/avatars/'+ auth.user.profile.usuario.avatar
        },
        goToNewRubro(){
            this.$events.fire('changePath', this.listPath, 'Registrar Rubro');
            route.push('/rubro/new');
        },
        goToNewPublicacion(){
            this.$events.fire('changePath', this.listPath, 'Nueva Publicacion');
            route.push('/publicacion/new');
        },
        searchPublicacion: function(){
            if(route.path !== '/' && this.q !== '' )
                route.push('/?q='+this.q)
            //this.$events.fire('searchPublicacion', this.q);
        }
    },
    computed: {
        showFormSearch: function(){
            return this.$route.name != 'home';
        }
    },
    watch: {
        'auth.user.profile.usuario'  (){
             this.avatarUpdated();
        }
    }

}
</script>

