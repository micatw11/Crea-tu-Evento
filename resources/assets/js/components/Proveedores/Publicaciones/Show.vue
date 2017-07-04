<template>
	<div>
		<div class="box-header">
            <button type="button" @click="goToNewPublicacion()" class="btn  btn-primary btn-sm">Crear Publicaci&oacute;n</button>
        </div>
	    <div class="content" v-for="item in productos">
	        <!--<div class="content">
	            <div class="content" style="border-style: double;">-->
	            <div class="box-body">
	            	<div class="col-sm-12">
		            	<div class="col-sm-12">
			                <div class="col-sm-8">
			                    <p>
			                        <h3>{{item.titulo}}</h3>
			                    </p>
			                   	<p>
			                        {{item.descripcion}}
			                    </p>
			                </div>
			                <div class="col-sm-4" style="text-align: center;">
			                    <button type="button" class="btn-block" @click="modificar(item.id)" >Modificar</button>
			                    <button type="button" class="btn-block" @click="baja(item.id)" >Eliminar</button>
			                </div>
			            </div>
			            <div class="col-sm-12">

		                	<div class="col-sm-2" v-for="img in item.fotos">
		                    	<img 
		                    		:src="'/storage/proveedores/publicaciones/'+img.nombre" 
		                    		class="img-responsive" 
		                    		style="max-width:130px;">
		                    </div>
		                </div>
		            </div>
	            </div>
	        <!--</div>-->
	        <hr>
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
				productos: [],
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
	                    this.productos = response.data
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
			goToNewPublicacion(){
				this.$events.fire('changePath', this.listPath, 'Nueva Publicacion');
				route.push('/publicacion/new');
			}
		}
	}
</script>
