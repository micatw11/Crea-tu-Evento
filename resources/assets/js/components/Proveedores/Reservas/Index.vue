<template>
	<div class="col-sm-12">
		<vue-event-calendar 
			:events="dataEvents">				
	      <template scope="props">
	        <div v-for="(event, index) in props.showEvents" class="event-item">
	          	<!-- In here do whatever you want, make you owner event template -->
	          	<h3 class="title"> 
	          		<router-link :to="/publicacion/ + event.publicacion.id">
	          			{{event.publicacion.titulo}}
					</router-link>
				</h3>
		        <p v-if="event.hora_inicio != null">Desde {{ formatTime(event.hora_inicio) }}hs hasta {{ formatTime(event.hora_finalizacion) }} hs</p>
		        <p>Total: ${{ event.precio_total}}</p>
		        <p v-if="auth.user.profile.roles_id == role.PROVEEDOR"> 
		        	Reservado por: {{event.user.usuario.nombre}} {{event.user.usuario.apellido}}
		        </p>
		        <p v-else>
	          		Proveedor: {{event.publicacion.proveedor.nombre}}
	            </p>
	        </div>
	      </template>
		</vue-event-calendar>
	</div>
</template>
<script>
	import moment from 'moment';
	import auth from './../../../auth.js'
	import role from '../../../config.js'
	export default {
		props: {
			publicacionId:{
				dafault: null
			}
		},
		data(){
		    return {
				dataEvents: [],
				auth: auth,
				role: role
		    }
		},
		beforeMount(){
			this.getReservas();
		},
		methods: {
			getReservas() {
				var url = '';
				this.dataEvents = [];
				if(this.publicacionId != null)
					url = 'api/publicacion/'+this.publicacionId+'/reserva';
				else
					url = 'api/user/'+this.auth.user.profile.id+'/reserva';

				this.$http.get(url).then(response => {
					var data = response.data;
					for(var reserva of data){
						this.dataEvents.push({
							id: reserva,
							publicacion_id: reserva.publicacion_id,
							user_id: reserva.user_id,
							domicilio_id: reserva.domicilio_id,
							date: reserva.fecha,
							hora_inicio: reserva.hora_inicio,
							hora_finalizacion: reserva.hora_finalizacion,
							estado: reserva.estado,
							precio_total: reserva.precio_total,
							created_at: reserva.created_at,
							updated_at: reserva.updated_at,
							publicacion: reserva.publicacion,
							user: reserva.user,
							rubros: reserva.rubros,
							articulos: reserva.articulos
						});
					}
				}, response => {

				});
			},
			formatTime (value) {
                return (value == null)
                    ? ''
                    : moment(value, 'hh:mm:ss').format('hh:mm')
            }
		}
	}
</script>