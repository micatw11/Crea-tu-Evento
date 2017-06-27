<template>
    <div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Proveedor</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

                <div class="box-body" style="display: block;">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <!--<li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>-->
                            <li class="active">
                                <a href="#notificacion" data-toggle="tab">Notificación</a>
                            </li>
                            <li>
                                <a href="1" data-toggle="tab">proveedor</a>
                            </li>
                            <li >
                                <a href="2" data-toggle="tab">Proveedor2</a>
                            </li>
                        </ul>
                    

                        <div class="tab-content">
                            
                            <!--<div class="active tab-pane" id="activity">
                                <activity></activity>
                            </div>-->

                            <div class="active tab-pane" id="notificacion">
                                Estas Habilitado a Ser Proveedor con Habilitación de ingresos brutos: {{perfil.ingresos_brutos}}
                                
                                Completar Formulario de Rubro:
                                      <form-rubro></form-rubro>
                                
                            </div>

                            <div class="tab-pane" id="1">
                                recontra datssss
                            </div>

                             <div class="tab-pane content" 
                                id="2">
                                     <div> 
                                     mas datos
                                    </div>
                                    <br><hr>
                                    <div>
                                        datosss
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
    </div>
</template>

<script>
import FormRubro from './FormRubro.vue';

export default {
    data(){
        return {
            perfil: []
        }
    },
    components: {
        FormRubro
    },
    beforeMount: function() {
        this.getProveedorPerfil()
    },
    methods: {
        getProveedorPerfil: function(){
            this.$http.get('api/proveedor/'+ this.$route.params.userId+'/perfil' )
                .then(response => {
                    this.perfil = response.data.data,
                    this.srcUrl = '/storage/avatars/'+this.perfil.avatar
                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        }
    }
}
</script>