<template>
        <div class="modal-body">
			<div class="box-body">
				<form-horario v-if="showModificarHorario==true" :horario="Horario" @validoFormHorario="sendHorario">
				</form-horario>
			</div>
	        <div class="modal-footer" style="text-align:center;">
	            <button class="btn btn-default" @click="closeModal()">
	                <i class="glyphicon glyphicon-chevron-left"></i>
	                Atras
	            </button>
	            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
	                Agregar
	            </button>
	            <button @click="deleteH()" type="button" class="btn btn-danger">
	                Eliminar
	            </button>
	        </div>
		</div>
</template>
<script>
	import FormHorario from './Form'
	export default {
		props: {
			horarioId:{
				required: true
			},
			publicacionId:{
				required: true
			}
		},
		data(){
			return {
				Horario:{
					hora_inicio: '',
					hora_fin: '',
					dias: {
							id:'',
							nombre: ''
						},
					precio: 0,
					publicacion_id: ''
				},
				showModificarHorario: false
			}
		},
		beforeMount: function(){
			this.getHorario()
		},
		components:{
			FormHorario
		},
		methods:{
			validateBeforeSubmit(){
				this.$events.fire('validarFormHorario')
			},
			sendHorario(){
				this.$http.patch('api/horario/'+this.horarioId,{
					hora_inicio: this.Horario.hora_inicio,
					hora_fin: this.Horario.hora_fin,
					dia: this.Horario.dias,
					precio: this.Horario.precio,
					publicacion_id: this.publicacionId
				}).then(response => {
					this.Horario = {
						hora_inicio: '',
						hora_fin: '',
						dias: {},
						precio: 0,
						publicacion_id: ''
					}
					this.$events.fire('reloadIndexHorario');
					this.showModificarHorario= false; 
				}, response => {
					console.log('Error en Horario');
				});
			},
			getHorario: function(){
				console.log(this.horarioId)
	        	this.$http.get('api/horario/'+this.horarioId)
	        	.then(response =>{
	        		this.Horario = response.data
	        		this.showModificarHorario=true
	        	}, response => {
                    if(response.status === 404){
                        router.go(-1)
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha cargado su publicación. :('
	                    });
                    }
	        	});
	        },
			closeModal(){
				this.$events.fire('close');
			},
			deleteH(){
				console.log('hola')
			}

		}
	}
</script>