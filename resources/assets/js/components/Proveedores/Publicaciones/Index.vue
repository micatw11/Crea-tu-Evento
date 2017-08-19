<template>
	<div>
	    <div class="default-content" >
			<br>
			<div>
				<button class="btn btn-primary" @click="goToNewPublicacion()">Nueva Publicaci&oacute;n</button>
			</div>
			<br><br>
            <div class="body">
	            <template v-if="publicaciones.length > 0" class="row">
	            	<div  v-for="item in publicaciones" class="col-sm-6">

		                <div class="box box-default">
		                    <div class="box-header">

		                        <div class="box-tools pull-right">


				                 	<button type="button" class="btn btn-box-tool" @click="ver(item.id)" data-toggle="tooltip">
										<i class="fa fa-eye"></i>
				                 	</button>
				                    <button type="button" class="btn btn-box-tool" @click="modificar(item.id)"data-toggle="tooltip" >
				                    	<i class="fa fa-pencil"></i>
				                    </button>
				                    <button type="button" class="btn btn-box-tool" @click="baja(item.id)" data-toggle="tooltip">
				                    	<i class="fa fa-trash"></i>
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
			                    		style="max-width:130px;">
			                    </div>
				                <div class="col-sm-8">
				                    <div class="col-sm-12">
				                        <p><h3>{{item.titulo}}</h3></p>
				                    </div>
				                    <div class="col-sm-12">
				                    	<p>Fecha de Publicación: {{formatData(item.created_at)}}</p>
				                    </div>
				                   	<div class="col-sm-12">
				                    	<p ref="descripcion" v-html="item.descripcion" v-truncate="30"></p>
				                   	</div>
				                </div>
		                    </div>
		                </div>
	                </div>
	            </template>
	            <template v-else>
		            <div class="col-sm-12">
		            	<h3 class="text-center">No posee publicaciones realizadas!</h3>
		            </div>
	            </template>
            </div>
	    </div>
	</div>
</template>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	import moment from 'moment';

	export default {
		props: {
			proveedorId: {
				required: true
			}
		},
		data() {
			return {
				publicaciones: [],
				productoId: null,
				listPath : [{route: '/', name: 'Home'}, {route: '/usuario/'+auth.user.profile.id +'/perfil', name: 'Perfil'}],
				auth: auth
			}
		},
		mounted(){
			this.getProductos();
		},
		methods: {
			getProductos: function(){
	            this.$http.get('api/proveedor/'+this.proveedorId+'/publicacion' )
	                .then(response => {
	                    this.publicaciones = response.data.publicaciones
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido cargar los productos. :('
	                    });

	                })
			},
			modificar(id){
				this.$events.fire('changePath', this.listPath, 'Modificar Publicacion');
				route.push('/publicacion/'+id+'/edit');
			},
			baja(id){
	            this.$http.delete('api/publicacion/'+id )
	                .then(response => {
	                    this.$toast.success({
	                        title:'Acción realizada!',
	                        message:'La acción se han realizado correctamente. :D'
	                    });
	                    this.getProductos()
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido realizar la acción. :('
	                    });

	                })
			},
			ver(id){
				this.$events.fire('changePath', this.listPath, 'Ver Publicacion');
				route.push('/publicacion/'+id);
			},
			goToNewPublicacion(){
				this.$events.fire('changePath', this.listPath, 'Nueva Publicacion');
				route.push('/publicacion/new');
			},
            formatData: function(value){

                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY');
            }
		},
		directives: {
			truncate: {
			  	bind: function(el, binding) {
				    el.textContent = el.textContent.substring(0, binding.value) + '...';
				}
			}
		}
	}
</script>
