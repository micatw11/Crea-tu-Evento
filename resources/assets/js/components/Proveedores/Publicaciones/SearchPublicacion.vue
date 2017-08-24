<template>
    <div class="box box-default">
         <div class="box-body">
            <template v-if="publicaciones.length > 0" class="row">
                <div  v-for="item in publicaciones" class="col-sm-12">
                    <div>
                        <hr>
                        <div class="col-sm-3" 
                            v-for="(img, index) in item.fotos" 
                            v-if="index==0" style= "text-align: center;">
                            <img 
                                :src="'/storage/proveedores/publicaciones/'+img.nombre" 
                                class="img-responsive" 
                                style="width: 120px; height: 100px; cursor:pointer;" @click="ver(item.id)">
                        </div>
                        <div class="col-sm-9">
                            <div class="col-sm-12">
                                <div class="col-sm-9" @click="ver(item.id)">
                                    <strong style="color: rgb(139, 116, 178); cursor:pointer"><h4><p ref="descripcion" v-html="item.titulo" v-truncate="30"></p></h4></strong>
                                </div>
                                <div class="col-sm-3">
                                    <h6><p>{{formatData(item.created_at)}}</p></h6>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-8">
                                    <p><h6>{{item.rubros_detalle.domicilio.localidad.provincia.nombre}} - {{item.rubros_detalle.domicilio.localidad.nombre}}</h6></p>
                                </div>
                                 <div class="col-sm-4">
                                    <div>
                                        <i class="fa fa-fw fa-star"></i>
                                        <i class="fa fa-fw fa-star"></i>
                                        <i class="fa fa-fw fa-star-half-o"></i>
                                        <i class="fa fa-fw fa-star-o"></i>
                                        <i class="fa fa-fw fa-star-o"></i>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-10">
                                    <p><h6>{{item.rubros_detalle.rubro.subcategoria.categoria.nombre}} - {{item.rubros_detalle.rubro.subcategoria.nombre}} - {{item.rubros_detalle.rubro.nombre}} </h6></p>
                                </div>
                                <div class="col-sm-2">
                                       <div v-if="favorite==false">
                                            <div  @click="favorite_icon(item.id)">
                                                <i class="fa fa-fw fa-heart-o"></i>
                                            </div> 
                                        </div>
                                        <div v-else>
                                            <div @click="favorite_icon(item.id)">
                                                <i class="fa fa-fw fa-heart"></i>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
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
import moment from 'moment';

    export default {
        props: {
            publicaciones: {
                required: true
            }
        },
        data(){
            return {
                favorite: false
            }
        },
        methods: {
            ver(id){
                this.$events.fire('changePath', this.listPath, 'Ver Publicacion');
                route.push('/publicacion/'+id);
            },
            formatData: function(value){

                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY');
            },
            favorite_icon(id){
                //guardarlo en bbdd
                if (this.favorite){
                    this.favorite= false
                }
                else{
                    this.favorite= true
                }
            }
        },
        directives: {
            truncate: {
                bind: function(el, binding) {
                    el.textContent = el.textContent.substring(0, binding.value);
                }
            }
        }
    }

</script>