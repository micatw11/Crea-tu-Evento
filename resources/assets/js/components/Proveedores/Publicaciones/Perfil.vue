<template>
	<div class="default-content">
	       <div class="col-sm-12">
            	<div class="col-sm-12">
	                <div class="col-sm-8">
	                    <p>
	                        <h3>{{publicacion.titulo}}</h3>
	                    </p>
	                   	<p>
	                        {{publicacion.descripcion}}
	                    </p>
	                </div>
	                
	            </div>
	            <div class="col-sm-12">

                	<div class="col-sm-2" v-for="img in publicacion.fotos">
                    	<img 
                    		:src="'/storage/proveedores/publicaciones/'+img.nombre" 
                    		class="img-responsive" 
                    		style="max-width:130px;">
                    </div>
                </div>
            </div>
	        
	        <!--</div>-->
	        <hr>
	
	</div>
</template>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	export default {
		
		data() {
			return {
				publicacion: [],
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
	            this.$http.get('api/publicacion/'+this.$route.params.publicacionId )
	                .then(response => {
	                    this.publicacion = response.data.publicaciones
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido cargar las publicaciones. :('
	                    });

	                })
			}
		}
	}
</script>