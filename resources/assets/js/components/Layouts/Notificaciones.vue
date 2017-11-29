<template>
    <!-- Notifications: style can be found in dropdown.less -->
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span v-if="pendientes > 0" class="label label-warning">
                1
            </span>
            <span v-if="mensajesPresupuesto > 0" class="label label-warning">
                1
            </span>
        </a>
        <ul class="dropdown-menu">
            <li v-if="auth.user.profile.roles_id == role.ADMINISTRADOR" class="header">
                Sin Notificaciones
            </li>
            <li v-if="auth.user.profile.roles_id == role.SUPERVISOR" class="header">
                Sin Notificaciones
            </li>
            <li v-if="auth.user.profile.roles_id == role.PROVEEDOR" class="header">
                {{mensajesPresupuesto == 0 ? 'Sin Notificaciones' : '1 Notificaci&oacute;n' }}
            </li>
            <li v-if="auth.user.profile.roles_id == role.USUARIO" class="header">
                {{pendientes == 0 ? 'Sin Notificaciones' : '1 Notificaci&oacute;n' }}
            </li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <template v-if="pendientes > 0  && auth.user.profile.roles_id == role.USUARIO">
                        <li>                     
                            <router-link 
                                tag="a" 
                                to="/calificaciones">                
                                <i class="fa fa-smile-o text-aqua"></i> 
                                {{pendientes}} {{pendientes == 1 ? 'calificacion pendiente' : 'calificaciones pendientes'}}.
                            </router-link>
                        </li>
                    </template>
                    <template v-if="mensajesPresupuesto > 0  && auth.user.profile.roles_id == role.PROVEEDOR">
                        <li>                     
                            <router-link 
                                tag="a" 
                                to="/mensajes">
                                <i class="fa fa-envelope-o text-aqua"></i>               
                                    {{mensajesPresupuesto}} {{mensajesPresupuesto == 1 ? 'presupuesto pendiente' : 'presupuestos pendientes'}}.
                            </router-link>
                        </li>
                    </template>
                </ul>
            </li>
        </ul>
    </li>
</template>
<script>
    import auth from '../../auth.js';
    import role from '../../config.js';
    import moment from 'moment';

    export default {
        data() {
            return {
                pendientes: 0,
                role: role,
                auth: auth,
                mensajesPresupuesto: 0
            }
        },
        beforeMount(){
            if(auth.user.profile.roles_id == role.USUARIO)
                this.calificacionesPendientes();
            if(auth.user.profile.roles_id == role.PROVEEDOR)
                this.getMensajes();
        },
        methods: {
            calificacionesPendientes(){
                this.$http.get('api/user/me/calificaciones/pendientes').then(response => {
                    this.pendientes = response.data.length;
                }, response => {});
            },
            /** 
            * Consulta de todas los mensajes.
            * 
            * @getMensajes 
            */
            getMensajes: function(){
                this.mensajesPresupuesto = 0;
                this.$http.get('api/mensaje')
                .then(response => {
                    for (let mensaje of response.data){
                        if(this.isAfterNow(mensaje.reserva.fecha) && mensaje.reserva.presupuestado == false && mensaje.reserva.estado == 'presupuesto')
                            this.mensajesPresupuesto++;

                    }
                })
            },
            isAfterNow(value){
                return moment(value, 'YYYY-MM-DD').isAfter(moment({}));
            }
        }
    }
</script>