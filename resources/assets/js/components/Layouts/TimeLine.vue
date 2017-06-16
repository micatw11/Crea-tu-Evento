<template>
  <div>
    <div v-for="actividad in actividades">
    <!-- The timeline -->
        <ul class="timeline timeline-inverse">
            <!-- timeline time label -->
            <li class="time-label">
                    <span class="bg-red">
                    {{actividad.created_at}}
                    </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-envelope bg-blue"></i>

                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{actividad.created_at}}</span>

                    <h3 class="timeline-header">Se realizo un <a href="#">{{actividad.accion}}</a> en la tabla {{actividad.tabla}} con rol {{actividad.roles_id}}</h3>

                    <div class="timeline-body">
                      Los valores antiguos son:
                        {{actividad.valor_antiguo}} 
                    </div>
                </div>
            </li>
        </ul>
    </div>
  </div>
</template>

<script>
import router from '../../routes.js';
import auth from '../../auth.js';

export default {
    data(){
        return {
            titleContent: 'Linea de Tiempo',
            actividades: [],
            auth: auth
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
        }
    }
}
</script>