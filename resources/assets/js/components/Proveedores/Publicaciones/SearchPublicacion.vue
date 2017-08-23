<template>
    <div class="box box-default">
         <div class="box-body">
            <template v-if="publicaciones.length > 0" class="row">
                <div  v-for="item in publicaciones" class="col-sm-6">

                    <div class="box box-default">
                        <div class="box-header">

                            <div class="box-tools pull-right">


                                <button type="button" class="btn btn-box-tool" @click="ver(item.id)" data-toggle="tooltip">
                                    <i class="fa fa-eye"></i>
                                </button>

                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="col-sm-4" 
                                v-for="(img, index) in item.fotos" 
                                v-if="index==0">
                                <img 
                                    :src="'/storage/proveedores/publicaciones/'+img.nombre" 
                                    class="img-responsive" 
                                    style="max-width: 75px; min-width: 75px; height:75px;">
                            </div>
                            <div class="col-sm-8">
                                <div class="col-sm-12">
                                    <p><h3>{{item.titulo}}</h3></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="col-sm-12">
                    <h3 class="text-center">No se encontraron resultados :(</h3>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import route from './../../../routes.js';
    export default {
        props: {
            publicaciones: {
                required: true
            }
        },
        methods: {
            ver(id){
                this.$events.fire('changePath', this.listPath, 'Ver Publicacion');
                route.push('/publicacion/'+id);
            }
        }
    }

</script>