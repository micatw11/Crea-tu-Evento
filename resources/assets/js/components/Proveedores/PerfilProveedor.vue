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
                                <a href="#publicaciones" data-toggle="tab">Publicaciones</a>
                            </li>
                            <li>
                                <a href="#rubro" data-toggle="tab">Rubro</a>
                            </li>
                        </ul>
                    
                        <div class="tab-content">
                            
                            <div class="active tab-pane" id="publicaciones">
                               <index-publicaciones :proveedorId="perfil.user.proveedor.id"></index-publicaciones>
                            </div>
                            <div class="tab-pane" id="rubro">

                                 <div v-if="perfil.user.proveedor">
                                        <index-rubros :perfil="perfil"></index-rubros>
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

import IndexRubros from './RubrosDetalle/Index.vue';
import IndexPublicaciones from './Publicaciones/Index.vue';

export default {
    props: {
            perfil: {
                type: Object,
                required: true
            }
    },
    data(){
        return {
            perfilProveedor: []
        }
    },
    components: {
        IndexRubros, IndexPublicaciones
    }
}
</script>