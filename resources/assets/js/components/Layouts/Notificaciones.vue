<template>
    <!-- Notifications: style can be found in dropdown.less -->
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span v-if="pendientes > 0" class="label label-warning">1</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header"> {{pendientes == 0 ? 'Sin Notificaciones' : '1 Notificaci&oacute;n' }}</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li v-if="pendientes > 0">                     
                        <router-link 
                            tag="a" 
                            to="/calificaciones">                
                            <i class="fa fa-smile-o text-aqua"></i> 
                            {{pendientes}} {{pendientes == 1 ? 'calificacion pendiente' : 'calificaciones pendientes'}}.
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</template>
<script>
    import auth from '../../auth.js';
    import role from '../../config.js';
    export default {
        data() {
            return {
                pendientes: 0
            }
        },
        beforeMount(){
            if(auth.user.profile.roles_id = role.USUARIO)
            {
                this.calificacionesPendientes();
            }
        },
        methods: {
            calificacionesPendientes(){
                this.$http.get('api/user/me/calificaciones/pendientes').then(response => {
                    this.pendientes = response.data.length;
                }, response => {});
            }
        }
    }
</script>