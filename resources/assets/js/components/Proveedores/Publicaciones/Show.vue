<template>
	<div class="default-content">

		<section v-if= "publicacion != null " class="content">

			<br>
		    <!-- Default box -->
		    <div class="box box-primary">
		    	<div class="box-header" v-if="auth.user.authenticated">
				    <div class="col-sm-12" v-if="
				    	auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.proveedor.user_id == auth.user.profile.id" >
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
						  	<div class="col-md-offset-0.5 col-sm-7">
						  		
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
	                                       <div style="cursor: pointer" v-if="verificar_favorite()">
		                                        <div @click="favorite_icon(publicacion.id)">
		                                            <i class="fa fa-fw fa-heart"></i>
		                                        </div>
		                                    </div>

		                                    <div style="cursor: pointer" v-else>
		                                        <div @click="favorite_icon(publicacion.id)">
		                                            <i class="fa fa-fw fa-heart-o"></i>
		                                        </div>
		                                    </div>
				                        </div>
			                        </h3>

			                        <p>
							      		<strong>Categoria: </strong> {{publicacion.subcategoria.categoria.nombre}}
							      	</p>
						      		<p>
						      			<strong>Fecha de publicaci&oacute;n:</strong> {{formatData(publicacion.created_at)}}
						      		</p>

						      		<p>
						      			<strong>Proveedor:</strong> {{publicacion.proveedor.nombre}}
						      		</p>

							       	<p>
					                	<strong>Email:</strong> {{publicacion.proveedor.email}}
						          	</p>
						          	
						          	<hr>

	                                <div class="col-sm-12 col-xs-12">

	                                	<el-rate
											v-model="puntos"
											disabled
											show-text
											text-color="#ff9900"
											text-template="{value} puntos.">
										</el-rate>

	                                </div> 
							        <div class="col-sm-12 col-xs-12">
							        	<hr>

							        	<div class="col-sm-11 col-sm-offset-1">

									        <button type="button" class="btn btn-block btn-success" @click.prevent="showPresupuestoModal()"
									        	v-if="!auth.user.authenticated ||
							                	(auth.user.authenticated && !(auth.user.profile.roles_id != role.USUARIO))">
									        	<i class="fa fa-calendar-check-o" aria-hidden="true"> Solicitar Presupuesto</i> 	
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
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#informacion" @click.prevent data-toggle="tab">
						Informaci&oacute;n</a>
					</li>
					<li><a href="#opiniones" @click.prevent data-toggle="tab">
						Opiniones</a>
					</li>
					<li v-if="auth.user.authenticated && (auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.proveedor.user_id == auth.user.profile.id)"><a href="#proveedor" @click.prevent data-toggle="tab">Eventos y Estadisticas</a>
				    </li>
				</ul>
				<div class="tab-content">

             		<div class="tab-pane active" id="informacion">
	             		<div class="box-body">
							<div v-if="publicacion.caracteristicas.length != 0" class="col-sm-12">
								<h4><u>Caracteristicas</u></h4>
								<ul>
									<div v-for="item in publicacion.caracteristicas">
										<div class="col-sm-4">
											<li>
												<div class="col-sm-6">{{ strUpper(item.nombre) }}</div>
												<div class="col-sm-6" 
													v-if="item.adicional && item.pivot.informacion != null"> 
													{{ strUpper(item.pivot.informacion) }}
												</div>
											</li>
											
										</div>
									</div>
								</ul>
								<br><br><hr>
							</div>

					        <div v-if="publicacion.oferta != null && publicacion.oferta.length!=0" class="col-sm-12">
					          	<h4><u>Oferta</u></h4>
					          	{{publicacion.oferta}}
					          	<p v-if=" publicacion.fecha_finalizacion != '0000-00-00' && publicacion.fecha_finalizacion != null ">
				          			Fecha Finalizacion: {{ publicacion.fecha_finalizacion }}
				         		</p>
				         		<p v-else>
				         			Oferta permanente
				         		</p>
				         		<br><hr>
					        </div>
					        <div v-if="publicacion.articulos.length > 0" class="col-sm-12">
					        	<h4><u>Servicios y Productos</u></h4>
					        	<div class="col-sm-12" v-for="(rubro, index) in publicacion.prestacion.rubros" v-if=" showRubroInfo(rubro.id)">
					        		<h5><b>{{ strUpper(rubro.nombre) }}</b></h5>
					        		{{ printArticles(rubro.id)}}
					        		<hr v-if="(index+1) == publicacion.prestacion.rubros.length">
					        	</div>
					        </div>
					        <div class="col-sm-12">
				               	<div class="col-sm-12" v-html='publicacion.descripcion'></div>
				            </div>
				        </div>
             		</div>
             		<div class="tab-pane" id="opiniones">
             		</div>
             		<div v-if="auth.user.authenticated && (auth.user.profile.roles_id == role.PROVEEDOR && 
				    	publicacion.proveedor.user_id == auth.user.profile.id)" class="tab-pane" id="proveedor">
				    	<div class="box-body">
							<index-reservas :publicacionId="publicacion.id"></index-reservas>
						</div>
             		</div>
             	</div>
            </div>

	        <div <div class="box box-default">
	        	<div class="box-header with-border">
	        		<i class="fa fa-fw fa-star-o margin-r-5"></i><h3 class="box-title">Opiniones</h3>
	        	</div>
	        	<div class="box-body">
	        		<div class="col-sm-3" style="text-align:center;">
		        			<h2>2,5</h2>
		        			<p>
                            	<el-rate
									v-model="puntos"
									disabled
									text-color="#ff9900">
								</el-rate>
							</p>
							<p style="color:#ff9900"><i class="fa fa-users" aria-hidden="true"></i> 1 en total.</p>

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
                            	<el-rate
									v-model="puntos"
									disabled
									show-text
									text-color="#ff9900"
									text-template="{value} puntos.">
								</el-rate>
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

	    <!-- modal presupuesto -->
	    <div v-if="auth.user.authenticated && showPresupuesto" id="modificar" class="modal" role="dialog" :style="{ display : showPresupuesto  ? 'block' : 'none' }">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Solicitar presupuesto a {{ publicacion.proveedor.nombre }}</h4>
	                </div>
	                <new-presupuesto :publicacion="publicacion"></new-presupuesto>
	            </div>
	        </div>
	    </div>

	</div>
</template>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	import Role from '../../../config.js'
	import { Carousel, Slide } from 'vue-carousel';
	import moment from 'moment';
	import NewPresupuesto from './../Presupuestos/New';

	Vue.component('index-reservas', require('./../Reservas/Index'));
	export default {
		
		data() {
			return {
				publicacion: null,
				puntos: 2.5,
				productoId: null,
				auth: auth,
				role: Role,
				showPresupuesto: false
			}
		},
		mounted(){
			this.$events.on('cerrar', () => this.closeModal());
		    this.$nextTick(function() {
		      this.getPublicacion();
		    })
			
		},
		components: {
        	Carousel, Slide, NewPresupuesto
    	},
		methods: {
			getPublicacion: function(){
	            this.$http.get('api/publicacion/'+this.$route.params.publicacionId )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
						var listPath = [
								{route: '/', name: 'Inicio'}, 
								{
									route: '/?with_category='+ this.publicacion.subcategoria.categoria.id, 
									name: this.publicacion.subcategoria.categoria.nombre
								},
								{
									route: '/?with_subcategory='+ this.publicacion.subcategoria.id, 
									name: this.publicacion.subcategoria.nombre},

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
			showPresupuestoModal(){
				if(auth.user.authenticated){
					this.showPresupuesto = true
				}
				else
				{
					route.push('/login');
				}
			},
			verificar_favorite(){
				if (auth.user.authenticated){
					if (this.publicacion.favoritos != null){
		                for (var i = 0; i < this.publicacion.favoritos.length; i++) {
		                    if (this.publicacion.favoritos[i].user_id == auth.user.profile.id){
		                        return true
		                    }
		                }
		            }
		         }
                return false
            },
			favorite_icon(id){
				if (auth.user.authenticated){
                this.$http.post(
                    'api/favoritos/', 
                    {
                        user_id: auth.user.profile.id,
                        publicacion_id : id
                    }).then(response => {
                            this.publicacion.favoritos = response.data.favoritos
                             this.$events.$emit('changeFavorite', eventData => this.getFavourite());
                             this.getPublicacion();
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se ha podido completar la accion. :('
                        });
                    }) 
                 }else{
                    var listPath = [
                        {route: '/login', name: 'Login'}
                    ]
                    this.$events.fire('changePath', listPath, 'Login');
                    route.push('/login');
                 }  
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
			showRubroInfo(rubro_id){
				for(var articulo of this.publicacion.articulos){
					if(articulo.rubro_id == rubro_id){
						return true;
					}
				}
				return false;
			},
			printArticles(rubro_id){
				var list = '';
				var primero = true;
				for(var articulo of this.publicacion.articulos){
					if(articulo.rubro_id == rubro_id){
						if(primero){
							list = this.strUpper(articulo.nombre)
						} else {
							list = list + ', ' + articulo.nombre;
						}
						primero = false;
					}
				}
				return list+'.';
			},
			closeModal: function(){
                this.showPresupuesto = false;
            },
	        strUpper: function(str){
	                var res = str.substring(1, str.length);
	                var res  = res.toLowerCase();
	                var res1 = str.substring(0, 1);
	                var res1 = res1.toUpperCase();
	                return  res1+''+res;
	        }
		}
	}
</script>