<template>
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
        <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>Eventos</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
            <a v-if="auth.user.authenticated" href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div v-if="auth.user.authenticated" class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!--
                        @include('layouts.mensaje')
                        @include('layouts.notificaciones')
                        @include('layouts.task')
                        @include('layouts.menu_login_user')
                    -->
                    <mensajes v-if="auth.user.authenticated"></mensajes>
                    <notificaciones v-if="auth.user.authenticated"></notificaciones>
                    <task v-if="auth.user.authenticated"></task>
                    <!-- Control Sidebar Toggle Button -->
                    
 
                   <menu-user v-if="auth.user.authenticated"></menu-user>

                    <li v-else>
                        <router-link :to="{ name: 'login' }"><a>Login</a></router-link>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>
<script>
import auth from '../../auth.js';
import router from '../../routes.js';
import Mensajes from './Mensajes.vue';
import MenuUser from './MenuUser.vue';
import Notificaciones from './Notificaciones.vue';
import Task from './Task.vue';

export default {
    data() {
        return {
            auth: auth
        }
    },
    components:{Mensajes, MenuUser, Notificaciones, Task}
}
</script>
