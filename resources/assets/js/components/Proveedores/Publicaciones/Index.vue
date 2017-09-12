<template>
    <div>

            <template v-if="publicaciones.length > 0" class="row">
                <div  v-for="(item, index) in publicaciones" class="col-sm-12">
                    <div>
                        <hr v-if="index > 0">
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
                                <div v-if="!optionsProveedor" class="col-sm-2">
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
                        <div class="col-sm-12" v-if="optionsProveedor && auth.user.profile.roles_id == role.PROVEEDOR && 
                                    item.rubros_detalle.proveedor.user_id == auth.user.profile.id">
                            <div style="text-align:center">
                                <div class="btn-group" >
                                    <button class="btn btn-sm btn-default" @click="modificar(item.id)">
                                        Modificar
                                    </button>
                                    <button class="btn btn-sm" v-bind:class="styleButton(item.estado)" @click="showModalDelete = true, publicacion = item,
                                    accion = item.estado">
                                        {{ item.estado == 1 ? 'Dar de baja' : 'Dar de alta'}}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br><br>
                </div>

            </template>
            <template v-else>
                <div class="col-sm-12">
                    <h3 v-if="!optionsProveedor" class="text-center">No se encontraron resultados :(</h3>
                    <h3 v-else class="text-center">No se encontraron publicaciones realizadas :(</h3>
                </div>
            </template>
             <template>
                <!-- Modal cambiar observaciones (baja/alta)-->
                <div class="modal" role="dialog" :style="{ display : showModalDelete  ? 'block' : 'none' }">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" @click="showModalDelete = false">&times;</button>
                                <h4 class="modal-title">Dar de baja publicacion</h4>
                            </div>
                            <div class="modal-body">

                                <div class="box-body">
                                    <p>¿Esta seguro de dar de {{ accion != 1 ? 'Alta' : 'Baja'}} esta publicación?</p>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <button type="button" @click="showModalDelete = false" class="btn btn-default">
                                            Atras
                                        </button>
                                        <button
                                            @click="estado(publicacion)" 
                                            type="button" class="btn btn-danger">
                                         Guargar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

    </div>
</template>

<script>
    import route from './../../../routes.js';
    import auth from './../../../auth.js';
    import role from './../../../config.js';
    import moment from 'moment';

    export default {
        props: {
            publicaciones: {
                required: true
            },
            optionsProveedor: {
                required: false,
                default: false
            },

        },
        data(){
            return {
                favorite: false,
                auth: auth,
                showModalDelete: false,
                role: role,
                accion: '',
                publicacion: {}
            }
        },
        methods: {

            ver(id){
                var listPath = [
                        {route: '/', name: 'Inicio'}, 
                        {route: '/publicacion/'+id, name: 'Ver Publicacion'}
                    ]
                this.$events.fire('changePath', listPath, 'Ver Publicacion');
                route.push('/publicacion/'+id);
            },
            modificar(id){
                route.push('/publicacion/'+id+'/edit');
            },
            estado(publicacion){
                this.$http.delete('api/publicacion/'+publicacion.id )
                    .then(response => {
                        this.$toast.success({
                            title:'Acción realizada!',
                            message:'La acción se han realizado correctamente. :D'
                        });
                        if(publicacion.estado == 1) publicacion.estado = 0;
                        else publicacion.estado = 1;
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido realizar la acción. :('
                        });
                    })
                    this.showModalDelete = false;
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
            },
            styleButton: function(estado){
                if(estado == 1) return ' btn-danger';
                else return ' btn-success';
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