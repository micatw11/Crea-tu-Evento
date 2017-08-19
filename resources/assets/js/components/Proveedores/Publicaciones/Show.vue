<template>
	<div>
	    <div class="default-content" >
	    	<br>
	    	<div>
				<button class="btn btn-primary" @click="goToNewPublicacion()">Nueva Publicaci&oacute;n</button>
			</div>
			<br><br>
            <div class="body">
	            <template v-if="publicaciones.length > 0">
	            	<div  v-for="item in publicaciones" class="col-sm-12">
	                	<div class="col-sm-2" v-for="(img, index) in item.fotos" v-if="index==0">
	                    	<img 
	                    		:src="'/storage/proveedores/publicaciones/'+img.nombre" 
	                    		class="img-responsive" 
	                    		style="max-width:130px;">
	                    </div>
		                 <div class="col-sm-10">
			                <div class="col-sm-6">
			                    <div class="col-sm-10">
			                        <p><h3>{{item.titulo}}</h3></p>
			                    </div>
			                   	<div class="col-sm-10">
			                    	<p ref="descripcion" v-html="item.descripcion" v-truncate="40"></p>
			                   	</div>
			                </div>
			                <div class="col-sm-4" style="text-align: center;">
			                 	<button type="button" class="btn-block" @click="ver(item.id)" >Ver</button>
			                    <button type="button" class="btn-block" @click="modificar(item.id)" >Modificar</button>
			                    <button type="button" class="btn-block" @click="baja(item.id)" >Eliminar</button>
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
