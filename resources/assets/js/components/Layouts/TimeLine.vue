<template>
    <div v-if="actividades.length > 0">
        <!-- The timeline -->
            <ul class="timeline">
                <!-- timeline time label -->
                <template v-for="actividad in actividades">
                    <template v-if="compareTimeLabel(actividad.fecha)">
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
                dateCompare: null
            }
        },
        beforeMount: function() {
            this.getUserlog()

        },
        components: {
            
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
            compareTimeLabel: function(value){
                let newDate = value;
                if(this.dateCompare === null){

                    this.dateCompare = newDate;
                    return true;
                } else {
                    if(!moment(this.dateCompare, 'YYYY-MM-DD').isSame(moment(value, 'YYYY-MM-DD'))){
                        this.dateCompare = newDate;

                        return true;
                    } else {
                        return false;
                    }
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