<template>
    <div v-if="actividades.length > 0">
        <!-- The timeline -->
            <ul class="timeline">
                <!-- timeline time label -->
                <template v-for="(actividad, index) in actividades" v-if="index <= cantTimes">
                    <template v-if="index > 0 && compareTimeLabel(index)">

                            <li class="time-label">
                                <span  class="bg-red">
                                    {{formatDate(actividad.fecha)}} 
                                </span>
                            </li>
                    </template>
                    <template v-if="index == 0">
                        <li class="time-label">
                            <span class="bg-red">
                                {{formatDate(actividad.fecha)}} 
                            </span>
                        </li>
                    </template>

                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <li>
                        <i v-bind:class="iconsAction(actividad.accion)"></i>

                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> {{actividad.created_at}}</span>

                            <h3 class="timeline-header">Se realizo un <a href="#">{{actividad.accion}}</a> en la tabla {{actividad.tabla}} con rol {{actividad.roles_id}}</h3>

                            <div v-if="actividad.valor_antiguo" class="timeline-body">
                              Los valores antiguos son:
                                {{actividad.valor_antiguo}} 
                            </div>
                        </div>
                        
                    </li>
                    <li v-if="index == cantTimes">
                        <div class="timeline-item">
                            <button class="btn 23btn-default btn-block" @click="verMas">Ver Mas</button>
                        </div>
                    </li>
                </template>
                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>


    </div>
    <div v-else class="text-center">
        No se econtraron actividades!.
    </div>
</template>

<script>
    import router from '../../routes.js';
    import auth from '../../auth.js';
    import moment from 'moment';

    export default {
        data(){
            return {
                titleContent: 'Linea de Tiempo',
                actividades: [],
                auth: auth,
                cantTimes: 10

            }
        },
        beforeMount: function() {
            this.getUserlog()

        },
        methods:{
            getUserlog: function(){
                this.$http.get('api/user/actividad/'+ this.$route.params.userId )
                    .then(response => {
                        this.actividades = response.data
                    }, response => {

                        if(response.status === 404){
                            router.push('/404');
                        }

                    })
            },
            reload: function(){
                this.getUserlog();
            },
            formatDate: function(value){
                moment.locale('es');
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('DD MMM YYYY');
            },
            compareTimeLabel: function(index){

                if(!moment(this.actividades[index-1].fecha, 'YYYY-MM-DD')
                    .isSame(moment(this.actividades[index].fecha, 'YYYY-MM-DD'))){
                    return true;
                } else {
                    return false;
                }
            },
            verMas: function(){
                if((this.cantTimes + 10) > this.actividades.length )
                {
                    this.cantTimes = this.cantTimes + 10;
                } else {
                    this.cantTimes = this.actividades.length;
                }
            },
            iconsAction: function(action){
                return 'fa fa-check-circle bg-blue'
            },
        }
    }
</script>
<style>
    .text-center {
        margin-top: 75px;
    }
</style>