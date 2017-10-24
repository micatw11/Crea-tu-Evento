<template>
	<div class="default-content">
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-success" v-if="presupuesto != null">
						<div class="box-header with-border">
							<i class="fa fa-credit-card margin-r-5"></i>
							<h3 class="box-title">Presupuesto</h3>
							<div class="pull-right">
								{{formatDate(presupuesto.fecha)}} 
								<template v-if="presupuesto.hora_inicio!=null">Desde {{formatTime(presupuesto.hora_inicio)}}hs a {{formatTime(presupuesto.hora_finalizacion)}}hs</template>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="col-md-12">
								<h4 style="text-align:center">
									<router-link :to="/publicacion/ + presupuesto.publicacion.id">
										<u>{{ presupuesto.publicacion.titulo }}</u>
									</router-link>
								</h4>
							</div>
							<div class="col-md-12" v-if="presupuesto.rubros.length > 0">
								<h5><label>Servicios</label></h5>
								<div v-for="rubro in presupuesto.rubros" v-if="rubro.servicio" class="col-sm-3 col-xs-6">
									<li>{{rubro.nombre}}</li>
								</div>
							</div>
							<div class="col-md-12" v-if="presupuesto.articulos.length > 0">
								<hr>
								<h5><label>Articulos</label></h5>
								<div v-for="articulo in presupuesto.articulos" class="col-sm-3 col-xs-6">
									<li>{{articulo.nombre}} <template v-if="articulo.pivot.cantidad > 0"> x {{articulo.pivot.cantidad}}</template></li>
								</div>
							</div>
							<div class="col-md-12">
								<hr>
								<label>Conste total: </label> $ {{presupuesto.precio_total}}
							</div>
						</div>
						<div class="box-footer clearfix">
							<div style="text-align: center;">
			            		<button class="btn btn-sm btn-primary btn-flat" 
			            			v-if="role.PROVEEDOR == auth.user.profile.roles_id"
			            			@click.prevent="getPublicacion()">
			            			Modificar Presupuesto
			            		</button>
			            		<button class="btn btn-sm btn-default btn-fla" 
			            			v-if="role.USUARIO == auth.user.profile.roles_id"
			            			@click.prevent>
			            			Modificar
			            		</button>
			            	</div>
			            </div>
					</div>
					<div class="box box-success direct-chat direct-chat-success">
						<div class="box-header with-border">
							<i class="fa fa-comments-o margin-r-5"></i>
							<h3 class="box-title">Mensajes</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<!-- Conversations are loaded here -->
							<div class="direct-chat-messages" style="height: 350px;padding: 20px;">
								<div v-for="mensaje in mensajes">
									<!-- Message. Default to the left -->
									<div v-if="mensaje.from_user_id != auth.user.profile.id" class="direct-chat-msg">
										<div class="direct-chat-info clearfix">
											<span class="direct-chat-name pull-left">{{ getNameFromUser }}</span>
										</div>
										<div class="direct-chat-text">
											{{ mensaje.mensaje }}
											<div class="direct-chat-info clearfix">
												<span class="direct-chat-timestamp pull-right" style="color:black">
													{{ formatDateTime(mensaje.created_at) }}
												</span>
											</div>
										</div>
									</div>

									<!-- Message to the right -->
									<div v-else class="direct-chat-msg right">
										<div class="direct-chat-text">
											{{ mensaje.mensaje }}
											<div class="direct-chat-info clearfix">
												<span class="direct-chat-timestamp pull-right" style="color:black">
													{{ formatDateTime(mensaje.created_at) }}
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<form action="#" method="post">
							<div class="input-group">
								<input type="text" name="message" v-model="newMensaje" v-validate="'required'" placeholder="Mensaje" class="form-control">
								<span class="input-group-btn">
									<button @click.prevent="validateBeforeSend" type="button" class="btn btn-success btn-flat">Enviar</button>
								</span>
							</div>
							</form>
						</div>
					    <!-- /.box-footer-->
					</div>
				</div>
			</div>
		</section>

	    <!-- modal presupuesto -->
	    <div v-if="showPresupuesto" id="modificar" class="modal" role="dialog" :style="{ display : showPresupuesto  ? 'block' : 'none' }">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Presupuesto - {{ publicacion.titulo }}</h4>
	                </div>
	                <form-presupuesto 
	                	:estado="presupuesto" 
	                	:publicacion="publicacion" 
	                	:reserva_id="presupuesto.id">
	                </form-presupuesto>
	            </div>
	        </div>
	    </div>

	</div>
</template>
<script>
	import auth from './../../auth';
	import Role from './../../config';
	import FormPresupuesto from './../Proveedores/Reservas/Edit';
	import moment from 'moment';
	export default {
		data(){
			return {
				mensajes: [],
				presupuesto: null,
				publicacion: {},
				showPresupuesto: false,
				newMensaje: '',
				auth: auth,
				validar: false,
				listPath: [
					{route: '/', name: 'Inicio'}, 
					{route: '/mensajes', name: 'Mensajes'}],
					role: Role
			}
		},
		beforeMount(){
			this.getMenensajes()
		},
		mounted() {
			this.$events.on('cerrar', () => this.closeModal());
		},
		components: {
			FormPresupuesto
		},
		methods: {
			getMenensajes(){
	            this.$http.get('api/mensaje/'+this.$route.params.mensajeId )
	                .then(response => {
	                	this.mensajes = response.data.mensajes;
	                	this.presupuesto = response.data.presupuesto;
	                	this.listPath.push({route: 'mensaje/'+this.$route.params.mensajeId, name: this.getNameFromUser });
						this.$events.fire('changePath', this.listPath, this.getNameFromUser );
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar los mensajes.'
	                    });
	                })
			},
			getPublicacion: function(){
	            this.$http.get('api/publicacion/'+this.presupuesto.publicacion.id )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
	                    this.showPresupuesto = true;
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar los datos. :('
	                    });
	                })
			},
			formatTime (value) {
                return (value == null)
                    ? ''
                    : moment(value, 'hh:mm:ss').format('hh:mm')
            },
			formatDate (value) {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format('D MMM YYYY')
            },
            formatDateTime(value){
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD hh:mm:ss').format('D MMM YYYY hh:mm')
            },
			validateBeforeSend(){
                this.$validator.validateAll().then(() => {
                	this.validar = false;
           			this.sendNewMensaje();
                }).catch(() => {
                    this.validar = true;
                });
			},
			sendNewMensaje(){
				var ultimo_mensaje_id = (this.mensajes.length - 1 );
				this.$http.post('api/mensaje/', {
						mensaje: this.newMensaje,
						prev_message_id: this.mensajes[ultimo_mensaje_id].id
					}).then(response => {
						this.getMenensajes();
						this.newMensaje = ''
					}, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar los mensajes.'
	                    });
					})
			},
			closeModal(){
				this.showPresupuesto = false;
			}
		},
		computed: {
			getNameFromUser(){
				for(var mensaje of this.mensajes){
					if(mensaje.to_user_id != auth.user.profile.id){
						return mensaje.to_user.usuario.nombre + ' ' +mensaje.to_user.usuario.apellido
					} else{
						return mensaje.from_user.usuario.nombre + ' ' +mensaje.from_user.usuario.apellido
					}
				}
			}

		}
	}
</script>
<style>
	.direct-chat-text {
	    margin: 5px 40% 0 0px;
	}
	.right .direct-chat-text {
	    margin-right: 0px;
	    margin-left: 40%;
	}
</style>