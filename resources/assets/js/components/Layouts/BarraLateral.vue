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
            <div class="user-panel">
                <div class="pull-left image">
                    <img :src="srcUrl" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p v-if="auth.user.authenticated" >{{ auth.user.profile.name}}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- search form -->
            <form v-on:submit.prevent class="sidebar-form">
                <div class="input-group">
                <input type="text" name="q" class="form-control" v-model="q" placeholder="Busqueda" @change="searchPublicacion()">
                    <span class="input-group-btn">
                        <button  id="search-btn" class="btn btn-flat" @click="searchPublicacion()">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MENU</li>

            <li class="active treeview" 
                v-if="auth.user.profile.roles_id == role.ADMINISTRADOR || 
                    auth.user.profile.roles_id == role.SUPERVISOR ||
                    auth.user.profile.roles_id == role.OPERADOR">
                
                <a href="#">
                    <i class="fa fa-dashboard"></i>
                    <span>Usuarios</span>
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
                            <i class="fa fa-circle-o"></i> Usuarios
                        </a>
                    </router-link>
                    <router-link
                        v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                            auth.user.profile.roles_id == role.SUPERVISOR ||
                            auth.user.profile.roles_id == role.OPERADOR" 
                            tag="li"
                            to="/proveedores">
                            <a>
                                <i class="fa fa-circle-o"></i> Proveedores
                            </a>
                    </router-link>
                </ul>
            </li>
            <li class="treeview">
                <router-link
                    v-if="auth.user.profile.roles_id == role.ADMINISTRADOR ||
                        auth.user.profile.roles_id == role.SUPERVISOR"
                        to="/categorias">
                        <a>
                            <i class="fa fa-th-list"></i> <span>Categorias</span>
                        </a>
                </router-link>
            </li>
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
        this.srcUrl = '/storage/avatars/'+ this.auth.user.profile.usuario.avatar
    },
    methods: {
        searchPublicacion: function(){
            this.$events.fire('search', this.q);
        }
    },
    watch: {
        'q' (newValue, oldValue){
            this.searchPublicacion();
        }
    }
}
</script>

