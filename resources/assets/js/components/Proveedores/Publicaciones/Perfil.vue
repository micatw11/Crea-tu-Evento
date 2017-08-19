<template>
	<div class="default-content">
		<section v-if= "publicacion != null " class="content">
	      <!-- Default box -->
	      <div class="box">
		    <div v-if="auth.user.profile.roles_id == role.PROVEEDOR" >
				<button class="btn btn-primary" @click="goToNewPublicacion()">Nueva Publicaci&oacute;n</button>
			</div>
			<br>
	        <div class="box-header with-border" style="text-align: center;">
	          <h3 class="box-title">{{publicacion.titulo}}</h3>
	        </div>
	        <div class="box-body">
		        <section>
		        	<div >
		        		<div class="col-sm-1"></div>
					  	<div class="col-sm-5">
			          		<div>
				          		<carousel :perPage="1" :perPageCustom="[[480, 1], [768, 1]]" :autoplay="true">
				          			<slide v-for="item in publicacion.fotos" :key="item.nombre">
				          				<img :src="'/storage/proveedores/publicaciones/'+item.nombre" class="img-responsive" style="max-height: 350px;">
				          			</slide>
				          		</carousel>
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
	          	<div style="text-align:center;">
	          		<div v-if="auth.user.profile.roles_id == role.USUARIO" class="col-sm-4">
	          				<button @click="goBack()" class="btn btn-default">
	                        <i class="glyphicon glyphicon-chevron-left"></i>
	                        Atras
	                    </button>
		                 	<button type="button" class="btn-block" @click="" >Reservar</button>
		            </div>

		        	<div v-if="auth.user.profile.roles_id == role.PROVEEDOR" >
			            <button @click="goBack()" class="btn btn-default">
	                        <i class="glyphicon glyphicon-chevron-left"></i>
	                        Atras
	                    </button>
		        		<button class="btn btn-primary" @click="modificar(publicacion.id)">
		        			<i class="glyphicon glyphicon-pencil"></i>
		        			Editar Publicaci&oacute;n</button>
		        		<button class="btn btn-danger" @click="baja(publicacion.id)">
		        			<i class="glyphicon glyphicon-trash"></i>
		        			Eliminar Publicaci&oacute;n</button>
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
	import Role from '../../../config.js'
	import ShowProveedor from './../Show.vue'
	import { Carousel, Slide } from 'vue-carousel';
	export default {
		
		data() {
			return {
				publicacion: null,
				productoId: null,
				listPath : [{route: '/', name: 'Home'}, {route: '/usuario/'+auth.user.profile.id +'/perfil', name: 'Perfil'}],
				auth: auth,
            	role: Role
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
	                    this.goBack()
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
			},
			goBack: function(){
	            route.go(-1)
	        }
		}
	}
</script>
<style>

.VueCarousel-slide {
  position: relative;
  color: #fff;
  font-family: Arial;
  font-size: 24px;
  text-align: center;
  min-height: 100px;
}
.VueCarousel {
    max-width: 350px;
    max-height: 350px;
}

</style>