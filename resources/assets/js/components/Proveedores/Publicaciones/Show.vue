<template>
	<div class="default-content">

		<section v-if= "publicacion != null " class="content">

			<br>
		    <!-- Default box -->
		    <div class="box box-primary">
		    	<div class="box-header" v-if="auth.user.authenticated">
				    <div class="col-sm-12" v-if="
				    	auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.rubros_detalle.proveedor.user_id == auth.user.profile.id" >
						<button class="btn btn-primary pull-left" @click="goToNewPublicacion()">Nueva Publicaci&oacute;n</button>

			        	<div class="pull-right">

				        		<button class="btn btn-primary" @click="modificar(publicacion.id)">
				        			<i class="glyphicon glyphicon-pencil"></i>
				        			Editar Publicaci&oacute;n</button>
				        		<button class="btn" v-bind:class="btnStyle(publicacion.estado)" @click="baja(publicacion.id)">
				        			<i class="glyphicon glyphicon-trash"></i>
				        			{{ publicacion.estado == 1 ? 'Dar de baja' : 'Dar de alta'}}
				        		</button>

			        	</div>
					</div>
		    	</div>
		        <div class="box-body">
		        	<div class="col-sm-12">
				        <section>
						  	<div class="col-md-offset-0.5 col-sm-7" v-if="showCarousel" >
						  		
				          		<carousel 
				          			:perPage="1" 
				          			:perPageCustom="[[480, 1], [768, 1]]" 
				          			:autoplay="false" 
				          			:autoplayTimeout="15000" 
				          			ref="carousel">
				          			<slide v-for="item in publicacion.fotos" :key="item.nombre">
				          				<img :src="'/storage/proveedores/publicaciones/'+item.nombre" class="img-responsive" style="max-height: 350px;    position: absolute;top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%); min-height:100%">

				          			</slide>
				          		</carousel>

						    </div>
							<div class="col-sm-5">
								<div class="col-sm-12">
									<h3 class="text-uppercase">{{publicacion.titulo}}
				                        <div class="pull-right">
				                            <button class="btn btn-box-tool" data-toggle="tooltip" @click.prevent >
				                                <i class="fa fa-fw fa-heart-o fa-2x"></i>
				                            </button>
				                        </div>
			                        </h3>


				        			<p>
							      		<strong>Rubro:</strong> {{publicacion.rubros_detalle.rubro.subcategoria.categoria.nombre}}
						      		</p>

						      		<p>
						      			<strong>Fecha de publicaci&oacute;n:</strong> {{formatData(publicacion.created_at)}}
						      		</p>

						      		<p>
						      			<strong>Proveedor:</strong> {{publicacion.rubros_detalle.proveedor.nombre}}
						      		</p>

							       	<p>
					                	<strong>Email:</strong> {{publicacion.rubros_detalle.proveedor.email}}
						          	</p>
						          	
						          	<hr>

	                                <div>
	                                	<p>Opiniones</p>
	                                    <i class="fa fa-fw fa-star fa-2x"></i>
	                                    <i class="fa fa-fw fa-star fa-2x"></i>
	                                    <i class="fa fa-fw fa-star-half-o fa-2x"></i>
	                                    <i class="fa fa-fw fa-star-o fa-2x"></i>
	                                    <i class="fa fa-fw fa-star-o fa-2x"></i>
	                                </div> 
						        </div>
						        <div v-if="auth.user.authenticated">
							        <div class="col-sm-12" v-if="auth.user.profile.roles_id == role.USUARIO">
							        	<div class="col-sm-6 col-sm-offset-3">
							                <button type="button" class="btn btn-block btn-success" @click.prevent>
							                	<i class="fa fa-calendar-check-o"></i> Reservar
							                </button>
							        	</div>
							        </div>
						        </div>
					        </div>

					    </section>
					</div>
		        </div>
		    </div>
		    <!-- /.box -->
	        <div <div class="box box-default">
	        	<div class="box-header with-border">
	        		<i class="fa fa-bars margin-r-5"></i><h3 class="box-title">Informaci&oacute;n</h3>
	        	</div>
	        	<div class="box-body">
					<div class="col-sm-12">
						<h4>Caracteristicas</h4>
						<ul>
							<div class="col-sm-4">
									<li></li>
									<li></li>
							</div>
							<div class="col-sm-4">
									<li></li>
									<li></li>
							</div>
							<div class="col-sm-4">
									<li></li>
									<li></li>
							</div>
						</ul>
					</div>


			        <div class="col-sm-12">
			        	<hr>
			          	<h4>{{publicacion.oferta}}</h4>
			          	<p v-if="publicacion.fecha_finalizacion">
		          			Fecha Finalizacion: {{publicacion.fecha_finalizacion}}
		         		</p>
			        </div>

			        <div class="nav-tabs-custom">
		               	<div class="col-sm-12" v-html='publicacion.descripcion'></div>
		            </div>
	        	</div>
		        <!-- /.box-body -->
	        </div>
	        <!-- /.box -->

	        <div <div class="box box-default">
	        	<div class="box-header with-border">
	        		<i class="fa fa-fw fa-star-o margin-r-5"></i><h3 class="box-title">Opiniones</h3>
	        	</div>
	        	<div class="box-body">
	        		<div class="col-sm-3" style="text-align:center;">
		        			<h2>2,5</h2>
		        			<p>
	                            <i class="fa fa-fw fa-star"></i>
	                            <i class="fa fa-fw fa-star"></i>
	                            <i class="fa fa-fw fa-star-half-o"></i>
	                            <i class="fa fa-fw fa-star-o"></i>
	                            <i class="fa fa-fw fa-star-o"></i>
							</p>
							<p><i class="fa fa-users" aria-hidden="true"></i> 1 en total.</p>

	        		</div>
	        		<div class="col-md-9">
						<!-- Post -->
						<div class="post clearfix">
							<div class="user-block">
								<img class="img-circle img-bordered-sm" src="" alt="avatar">
								<span class="username">
									Sarah Ross
								</span>
								<span class="description">Sent you a message - 3 days ago</span>
							</div>
							<!-- /.user-block -->
							<p>
	                            <i class="fa fa-fw fa-star"></i>
	                            <i class="fa fa-fw fa-star"></i>
	                            <i class="fa fa-fw fa-star-half-o"></i>
	                            <i class="fa fa-fw fa-star-o"></i>
	                            <i class="fa fa-fw fa-star-o"></i>
							</p>
							<p>
								Lorem ipsum represents a long-held tradition for designers,
								typographers and the like. Some people hate it and argue for
								its demise, but others ignore the hate as they create awesome
								tools to help create filler text for everyone from bacon lovers
								to Charlie Sheen fans.
							</p>

						</div>
						<!-- /.post -->
	        		</div>
	        	</div>
	        </div>

	        <div <div class="box box-default">
	        	<div class="box-header with-border">
	        		<i class="fa fa-comments-o margin-r-5"></i><h3 class="box-title">Comentarios</h3>
	        	</div>
	        	<div class="box-body">
	        		<div class="col-sm-10 col-sm-offset-1">
						<!-- Post -->
						<div class="post clearfix">
							<div class="user-block">
								<img class="img-circle img-bordered-sm" src="" alt="avatar">
									<span class="username">
										Sarah Ross
										<a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
									</span>
									<span class="description">Sent you a message - 3 days ago</span>
							</div>
							<!-- /.user-block -->
							<p>
								Lorem ipsum represents a long-held tradition for designers,
								typographers and the like. Some people hate it and argue for
								its demise, but others ignore the hate as they create awesome
								tools to help create filler text for everyone from bacon lovers
								to Charlie Sheen fans.
							</p>

							<form class="form-horizontal">
								<div class="form-group margin-bottom-none">
									<div class="col-sm-9">
										<textarea class="form-control"></textarea>
									</div>
									<div class="col-sm-3">
										<button type="submit" class="btn btn-default pull-right btn-block btn-sm">Comentar</button>
									</div>
								</div>
							</form>
						</div>
						<!-- /.post -->
					</div>
	        	</div>
		        <!-- /.box-body -->
		        <div  class="box-footer">
		          	<div style="text-align:center;">
		          		<div class="col-sm-4">
		          			<button @click="goBack()" class="btn btn-default">
		                        <i class="glyphicon glyphicon-chevron-left"></i> Atras
		                    </button>
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
	import moment from 'moment';

	export default {
		
		data() {
			return {
				publicacion: null,
				productoId: null,
				auth: auth,
				role: Role,
				showCarousel: true
			}
		},
		mounted(){
		    this.$nextTick(function() {
		      this.getProductos();
		    })
			
		},
		created() {
			window.addEventListener('resize', this.handleResize)
		},
		components: {
        	ShowProveedor, Carousel, Slide
    	},
		methods: {
			getProductos: function(){
	            this.$http.get('api/publicacion/'+this.$route.params.publicacionId )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
						var listPath = [
								{route: '/', name: 'Inicio'}, 
								{
									route: '/?with_category='+ this.publicacion.rubros_detalle.rubro.subcategoria.categoria.id, 
									name: this.publicacion.rubros_detalle.rubro.subcategoria.categoria.nombre
								},
								{
									route: '/?with_subcategory='+ this.publicacion.rubros_detalle.rubro.subcategoria.id, 
									name: this.publicacion.rubros_detalle.rubro.subcategoria.nombre},
								{
									route: '/?with_denomination='+this.publicacion.rubros_detalle.rubro.id, 
									name: this.publicacion.rubros_detalle.rubro.nombre},
								{
									route: '/publicacion/'+this.$route.params.publicacionId, 
									name: this.publicacion.titulo
								}
							]
						this.$events.fire('changePath', listPath, this.publicacion.titulo);
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar la publicacion. :('
	                    });
	                })
			},
			modificar(id){
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
				route.push('/publicacion/new');
			},
			goBack: function(){
	            route.go(-1)
	        },
            formatData: function(value){

                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY');
            },
			btnStyle: function(estado){
				if(estado == 1) return ' btn-danger';
				else return ' btn-success';
			},
			handleResize: function(){
				this.showCarousel = false;
				setTimeout(this.showComponentCarousel, 100);
			},
			showComponentCarousel: function(){
				this.showCarousel = true
				console.log('paso')
			}
		},
		beforeDestroy: function () {
		    window.removeEventListener('resize', this.handleResize)
		},
	}
</script>
<style>

.VueCarousel-slide {
	position: relative;
	color: #fff;
	font-family: Arial;
	font-size: 24px;
	text-align: center;
	min-height: 350px;
	border-style: solid;
	border-color: rgba(241, 242, 243, 0.8);
	background: rgba(241, 242, 243, 0.8);
}
.VueCarousel {
    max-width: 100%;
    max-height: 100%;
}

</style>