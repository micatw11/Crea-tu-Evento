<template>
	<div class="default-content">
		<section v-if= "publicacion != null " class="content">
	      <!-- Default box -->
	      <div class="box">
	        <div class="box-header with-border">
	          <h3 class="box-title">{{publicacion.titulo}}</h3>
	         
	        </div>
	        <div class="box-body">
		        <section>
		        	<div >
		        		<div class="col-sm-1"></div>
					  	<div class="col-sm-5" v-for="(img,index,i) in publicacion.fotos">
					  		
						   	 <div data-target="#myCarousel"class="item" v-bind:class="{'active': data_slide_to == index, ' ': data-slide-to != index }">

						      <img 
			            		:src="'/storage/proveedores/publicaciones/'+img.nombre" 
			            		class="img-thumbnail"
			            		style="width:300px;height:300px;"
			            	 >
			            	 </div>
					    </div>
						<div class="col-sm-6">
							 <h4>
			        	  <br><br>
		        	      <p class="inline-block">
			                <span>{{publicacion.rubros_detalle.proveedor.nombre}}</span>
				          </p>
			        	 <strong style="color: rgb(0, 41, 102);"><u>Provedor de  {{publicacion.rubros_detalle.rubro.subcategoria.categoria.tipo_proveedor}}</u></strong><br>
					      <strong style="color: rgb(0, 41, 102);">{{publicacion.rubros_detalle.rubro.subcategoria.categoria.nombre}}</strong><br>
					      <strong style="color: rgb(0, 41, 102);"> {{publicacion.rubros_detalle.rubro.subcategoria.nombre}}</strong><br></h4>
					      <br>
					       <p class="inline-block">
			                	<span>Email: {{publicacion.rubros_detalle.proveedor.email}}</span>
				          </p>
					      
				          </div>
			        </div>
			    </section>
	          	<hr>
		        <div class="row">
					<div class="col-xs-12 text-center box-group" id="accordion">
		          		<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded= "false">
		                    Ver Datos Proveedor
		                 </a>
				      </div>
				      <div id="collapseOne" class="panel-collapse collapse" aria-expanded= "false">
		                   	 <div class="box-body">
		                       <!--vista proveedor-->
		                       <show-proveedor :proveedor="publicacion.rubros_detalle.proveedor"></show-proveedor>
		                    </div>
		              </div>
		        </div>
		        <hr>
		        <div style="text-align: center;">
		          	<h4>{{publicacion.oferta}}</h4>
		        </div>
		        <div class="nav-tabs-custom">
	               	<div class="col-sm-12" style="text-align: center;" v-html= 'publicacion.descripcion'></div>
	            </div>
	        </div>
	        <!-- /.box-body -->
	        <div  class="box-footer">
	          <div v-if="publicacion.fecha_finalizacion">
	          		Fecha Finalizacion: {{publicacion.fecha_finalizacion}}
	          </div>
	          <div>
	          		<div class="col-sm-4">
		                 	<button type="button" class="btn-block" @click="" >Reservar</button>
		            </div>
	          </div>
	        </div>
	        <!-- /.box-footer-->
	      </div>
	      <!-- /.box -->

	    </section>
	</div>
</template>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	import ShowProveedor from './../Show.vue'
	import { Carousel, Slide } from 'vue-carousel';
	export default {
		
		data() {
			return {
				publicacion: null,
				productoId: null,
				listPath : [{route: '/', name: 'Home'}, {route: '/usuario/'+auth.user.profile.id +'/perfil', name: 'Perfil'}],
				auth: auth
			}
		},
		mounted(){
			this.getProductos();
		},
		components: {
        	ShowProveedor, Carousel, Slide
    	},
		methods: {
			getProductos: function(){
	            this.$http.get('api/publicacion/'+this.$route.params.publicacionId )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
	                    
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar la publicacion. :('
	                    });

	                })
			},
			verProveedor(id){
				this.$events.fire('changePath', this.listPath, 'Ver Proveedor');
				route.push('/proveedor/'+id);
			}
		}
	}
</script>

  <style>
  .carousel-inner > .item > img,
  .carousel-inner > .item > a > img {
      width: 80%;
      margin: auto;
  }
  </style>