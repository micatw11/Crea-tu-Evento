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
                                <a href="#h1" data-toggle="tab">proveedor</a>
                            </li>
                            <li >
                                <a href="#h2" data-toggle="tab">Proveedor2</a>
                            </li>
                        </ul>
                    

                        <div class="tab-content">
                            
                            <!--<div class="active tab-pane" id="activity">
                                <activity></activity>
                            </div>-->

                            <div class="active tab-pane" id="notificacion">
                                <div v-if="!perfilProveedor.rubro">
                                    Estas Habilitado a Ser Proveedor con Habilitación de ingresos brutos: {{perfilProveedor.ingresos_brutos}}
                                    
                                    Completar Formulario de Rubro:
                                          <new-rubro></new-rubro>
                                </div>
                            </div>

                            <div class="tab-pane" id="h1">
                                recontra datssss
                            </div>

                             <div class="tab-pane content" 
                                id="h2">
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
import NewRubro from './NewRubro.vue';

export default {
    data(){
        return {
            perfilProveedor: []
        }
    },
    components: {
        NewRubro
    },
    beforeMount: function() {
        this.getProveedorPerfil()
    },
    methods: {
        getProveedorPerfil: function(){
            this.$http.get('api/proveedor/'+ this.$route.params.userId+'/perfil' )
                .then(response => {
                    this.perfilProveedor = response.data.data
                    //this.srcUrl = '/storage/avatars/'+this.perfil.avatar
                }, response => {
                })
        }
    }
}
</script>