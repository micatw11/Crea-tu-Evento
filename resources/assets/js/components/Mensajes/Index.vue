<template>
	<div class="default-content">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Mensajes</h3>

					<div class="box-tools pull-right">
						<div class="has-feedback">
							<input type="text" class="form-control input-sm" v-model="filter" @change="onFilterSet" placeholder="Buscar Mensajes">
							<span class="glyphicon glyphicon-search form-control-feedback"></span>
						</div>
					</div>
					<!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body no-padding">
					<div class="mailbox-controls">
						<!-- /.btn-group -->
						<button type="button" class="btn btn-default btn-sm" @click.prevent="getMensajes">
							<i class="fa fa-refresh"></i>
						</button>
						<!-- /.pull-right -->
					</div>
					<div class="table-responsive mailbox-messages">
						<table class="table table-hover table-striped">
							<tbody>
							<tr v-for="mensaje in mensajes">
								<td class="mailbox-name">
	 							<router-link  tag="a" v-bind:to="'/mensaje/'+ mensaje.id">
									{{ getNameFromUser }}
								</router-link>
								</td>
								<td class="mailbox-subject">
									<b>{{ mensaje.reserva.publicacion.titulo}}</b> - 
									<router-link v-bind:to="'/mensaje/'+ mensaje.id">
										<a v-truncate="30">{{mensaje.mensaje}}</a>
									</router-link>
								</td>
								<td class="mailbox-attachment"></td>
								<td class="mailbox-date">5 mins ago</td>
							</tr>
						</tbody>
						</table>
						<!-- /.table -->
					</div>
					<!-- /.mail-box-messages -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer no-padding">
					<div class="mailbox-controls">
						<button type="button" class="btn btn-default btn-sm" @click.prevent="getMensajes">
							<i class="fa fa-refresh"></i>
						</button>
						<!-- /.pull-right -->
					</div>
				</div>
			</div>
			<!-- /. box -->
		</div>
	</div>
</template>
<script>
	import auth from './../../auth';
    export default {
        data(){
        	return {
            	mensajes: [],
            	filter: '',
            	auth: auth
            }
        },
        beforeMount(){
        	this.getMensajes();
        },
        methods: {
            getMensajes: function(){
            	this.mensajes = [];
            	var api = 'api/mensaje'
            	if(this.filter != '')
            	{
            		api = api + '?filter=' + this.filter;
            	}

                this.$http.get(api)
                .then(response => {
                    this.mensajes = response.data.data;
                }, response => {
                    
                })
            },
        	onFilterSet(){
        		this.getMensajes();
        	}	
        },
        directives: {
            truncate: {
                bind: function(el, binding) {
                    el.textContent = el.textContent.substring(0, binding.value) + '...';
                }
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